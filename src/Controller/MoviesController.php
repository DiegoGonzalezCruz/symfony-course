<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieFormType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoviesController extends AbstractController
{
    private $em;
    private $movieRepository;
    public function __construct(MovieRepository $movieRepository, EntityManagerInterface $em)
    {

        $this->movieRepository = $movieRepository;
        $this->em = $em;
    }


    #[Route('/movies', name: 'movies',)]
    public function index(): Response
    {
        $movies = $this->movieRepository->findAll();
        return $this->render('movies/index.html.twig', [
            'movies' => $movies,
        ]);
    }


    #[Route('/movies/create', name: 'create_movie')]
    public function create(Request $request): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieFormType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newMovie = $form->getData();
            $imagePath = $form->get('imagePath')->getData();
            if ($imagePath) {
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                try {
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }
                $newMovie->setImagePath('/uploads/' . $newFileName);
            }
            $this->em->persist($newMovie);
            $this->em->flush();

            return $this->redirectToRoute('movies');
        }

        return $this->render('movies/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/movies/edit/{id}', name: 'edit_movie')]
    public function edit($id, Request $request): Response
    {
        $movie = $this->movieRepository->find($id);
        if (!$movie) {
            $this->addFlash('error', 'The requested movie does not exist.');
            return $this->redirectToRoute('movies');
        }

        $form = $this->createForm(MovieFormType::class, $movie);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $imagePath = $form->get('imagePath')->getData();
            if ($imagePath) {
                $oldImagePath = $movie->getImagePath();
                if ($oldImagePath !== null && file_exists($this->getParameter('kernel.project_dir') . '/public' . $oldImagePath)) {
                    unlink($this->getParameter('kernel.project_dir') . '/public' . $oldImagePath); // Delete the old image
                }
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();
                try {
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }
                $movie->setImagePath('/uploads/' . $newFileName);

                $this->em->flush(); // Apply all changes to the database

                $this->addFlash('success', 'Movie updated successfully.');
                return $this->redirectToRoute('movies');
            }
        }

        return $this->render('movies/edit.html.twig', [
            'movie' => $movie,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/movies/{id}', methods: ['GET'],   name: 'movie')]
    public function show($id): Response
    {
        $movie = $this->movieRepository->find($id);

        return $this->render('movies/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[Route('movies/delete/{id}', methods: ['GET', 'DELETE'], name: 'delete_movie')]
    public function delete($id): Response
    {
        $movie = $this->movieRepository->find($id);
        if (!$movie) {
            return new JsonResponse(['status' => 'error', 'message' => 'Movie not found'], 404);
        }
        $this->em->remove($movie);
        $this->em->flush();

        return $this->redirectToRoute('movies');
    }
}

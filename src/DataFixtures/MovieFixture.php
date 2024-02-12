<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MovieFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $movie = new Movie();
        $movie->setTitle('The Shawshank Redemption');
        $movie->setReleaseYear('1994');
        $movie->setImagePath('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQjzGdfg4HcAI-yCi9gXofi7JLT8T8vgcufIfTyTxqL4_3UUiNJ');
        $movie->setDescription('Two imprisoned men bond over a number of years, finding solace and eventual redemption through.');
        $movie->addActor($this->getReference('actor_1'));
        $movie->addActor($this->getReference('actor_2'));
        $manager->persist($movie);

        $movie2 = new Movie();
        $movie2->setTitle('The Godfather');
        $movie2->setReleaseYear('1972');
        $movie2->setImagePath('https://m.media-amazon.com/images/M/MV5BM2MyNjYxNmUtYTAwNi00MTYxLWJmNWYtYzZlODY3ZTk3OTFlXkEyXkFqcGdeQXVyNzkwMjQ5NzM@._V1_.jpg');
        $movie2->setDescription('The aging patriarch of an organized crime dynasty transfers control of his clandestine empire to his reluctant son.');
        $movie2->addActor($this->getReference('actor_3'));
        $movie2->addActor($this->getReference('actor_4'));
        $manager->persist($movie2);

        $movie3 = new Movie();
        $movie3->setTitle('The Dark Knight');
        $movie3->setReleaseYear('2008');
        $movie3->setImagePath('https://musicart.xboxlive.com/7/abb02f00-0000-0000-0000-000000000002/504/image.jpg?w=1920&h=1080');
        $movie3->setDescription('When the menace known as the Joker wreaks havoc and chaos on the people of Gotham, Batman must accept one of the greatest psychological and physical tests.');
        $movie3->addActor($this->getReference('actor_5'));
        $movie3->addActor($this->getReference('actor_6'));
        $movie3->addActor($this->getReference('actor_7'));
        $manager->persist($movie3);

        $manager->flush();
    }
}

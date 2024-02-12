<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ActorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $actor = new Actor();
        $actor->setName('Christian Bale');
        $manager->persist($actor);
        // create actors for movies in clipboard
        $actor2 = new Actor();
        $actor2->setName('Heath Ledger');
        $manager->persist($actor2);

        $actor3 = new Actor();
        $actor3->setName('Morgan Freeman');
        $manager->persist($actor3);

        $actor4 = new Actor();
        $actor4->setName('Al Pacino');
        $manager->persist($actor4);

        $actor5 = new Actor();
        $actor5->setName('Marlon Brando');
        $manager->persist($actor5);

        $actor6 = new Actor();
        $actor6->setName('Tim Robbins');
        $manager->persist($actor6);

        $actor7 = new Actor();
        $actor7->setName('Bob Gunton');
        $manager->persist($actor7);

        $manager->flush();

        $this->addReference('actor_1', $actor);
        $this->addReference('actor_2', $actor2);
        $this->addReference('actor_3', $actor3);
        $this->addReference('actor_4', $actor4);
        $this->addReference('actor_5', $actor5);
        $this->addReference('actor_6', $actor6);
        $this->addReference('actor_7', $actor7);
    }
}

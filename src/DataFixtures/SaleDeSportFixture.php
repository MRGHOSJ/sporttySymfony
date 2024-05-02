<?php

// src/DataFixtures/SaleDeSportFixture.php

namespace App\DataFixtures;

use App\Entity\SaleDeSport;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SaleDeSportFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        // Define a list of topics or contexts
        $topics = ['Fitness', 'Yoga', 'Swimming', 'Weightlifting', 'Cardio', 'Martial Arts', 'Dance'];

        // Create dummy SaleDeSport entities with specific topics
        for ($i = 0; $i < 10; $i++) {
            $saleDeSport = new SaleDeSport();
            $topicIndex = array_rand($topics);
            $topic = $topics[$topicIndex];

            $saleDeSport->setNomSalle($topic . ' ' . $faker->sentence(2));
            $saleDeSport->setDescr($faker->paragraph(3));
            $saleDeSport->setLieuSalle($faker->city);
            $saleDeSport->setNumSalle($faker->numberBetween(1, 10));
            $saleDeSport->setLienvideo($faker->url);
            $saleDeSport->setImage($faker->imageUrl());
            $saleDeSport->setLocation($faker->address);

            $manager->persist($saleDeSport);
        }

        $manager->flush();
    }
}

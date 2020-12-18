<?php

// src/DataFixtures/EpisodeFixtures.php

namespace App\DataFixtures;

use Faker;
use App\Entity\Episode;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EpisodeFixture extends Fixture implements DependentFixtureInterface
{
    // add Episode objet in database
    public function load(ObjectManager $manager)
    {
        $slugify = new Slugify();

        $faker = Faker\Factory::create('fr_FR');

        // create 50 episodes!
        for ($i =0; $i < 50; $i++) {
            $episode = new Episode();
            $episode->setTitle($faker->sentence(5, true));
            $episode->setNumber($faker->randomDigitNotNull);
            $episode->setSynopsis($faker->paragraph(15, true));
            $episode->setSeason($this->getReference('season_' . rand(0, 49)));
            $slug = $slugify->generate($episode->getTitle());
            $episode->setSlug($slug);
            $manager->persist($episode);
        }
        

        $manager->flush();
    }

    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }
}

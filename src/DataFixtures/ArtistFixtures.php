<?php

namespace App\DataFixtures;

use App\Entity\Artist;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class ArtistFixtures extends Fixture
{
    private function __construct(private readonly ParameterBagInterface $parameterBag) {}

    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('en_US');
        $startHour = 19;
        $styleList = $this->parameterBag->get('style');

        for ($i = 0; $i < 60; $i++) {
            $hour = rand(0, 11);
            $hour = ($startHour + $hour) % 24;

            $minute = rand(0, 1) * 30;

            $artist = new Artist();
            try {
                $artist->setName($faker->userName())
                    ->setDescription($faker->paragraph(3, true))
                    ->setStyle($faker->randomElement($styleList))
                    ->setMixDate($faker->dateTimeBetween('+2 days', '+3 days'))
                    ->setMixTime(new DateTime(sprintf('%02d:%02d', $hour, $minute)));
            } catch (Exception $e) {}

            $manager->persist($artist);
        }

        $manager->flush();
    }
}

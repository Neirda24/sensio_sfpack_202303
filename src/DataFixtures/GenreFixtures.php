<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GenreFixtures extends Fixture
{
    private const GENRES = [
        'Action',
        'Adventure',
        'Comedy',
        'Drama',
        'Fantasy',
        'Spy',
        'Thriller',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::GENRES as $genreName) {
            $genre = (new Genre())->setName($genreName);
            $manager->persist($genre);
        }

        $manager->flush();
    }
}

<?php

declare(strict_types=1);

namespace App\Omdb\Bridge;

use App\Entity\Movie as MovieEntity;
use App\Model\Rated;
use App\Omdb\Client\OmdbApiConsumerInterface;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use DateTimeImmutable;
use function explode;

/**
 * @phpstan-import-type OmdbMovieResult from OmdbApiConsumerInterface
 * @phpstan-import-type OmdbMovieSearchResults from OmdbApiConsumerInterface
 */
final class OmdbToDatabaseImporter
{
    public function __construct(
        private readonly MovieRepository $movieRepository,
        private readonly GenreRepository $genreRepository,
    ) {
    }

    public function importFromApiData(array $apiData, bool $flush = false): MovieEntity
    {
        $newMovie = (new MovieEntity())
            ->setTitle($apiData['Title'])
            ->setPoster($apiData['Poster'])
            ->setRated(Rated::tryFrom($apiData['Rated']) ?? Rated::GeneralAudiences)
            ->setPlot($apiData['Plot'])
            ->setReleasedAt(new DateTimeImmutable($apiData['Released']));

        foreach (explode(', ', $apiData['Genre']) as $genreName) {
            $newMovie->addGenre($this->genreRepository->get($genreName));
        }

        $this->movieRepository->save($newMovie, $flush);

        return $newMovie;
    }
}

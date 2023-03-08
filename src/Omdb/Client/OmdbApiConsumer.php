<?php

declare(strict_types=1);

namespace App\Omdb\Client;

use Doctrine\ORM\NoResultException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use function array_key_exists;

/**
 * // TODO : Rated : enum
 * // TODO : Type : enum
 * // TODO : Response : enum
 * @phpstan-type OmdbMovieResult array{Title: string, Year: string, Rated: string, Released: string, Genre: string, Plot: string, Poster: string, imdbID: string, Type: string, Response: string}
 */
final class OmdbApiConsumer
{
    public function __construct(
        private readonly HttpClientInterface $omdbApiClient,
    ) {
    }

    /**
     * @return OmdbMovieResult
     */
    public function getById(string $imdbId): array
    {
        $response = $this->omdbApiClient->request('GET', '/', [
            'query' => [
                'i' => $imdbId,
                'plot' => 'full',
                'r' => 'json'
            ],
        ]);

        /** @var OmdbMovieResult $result */
        $result = $response->toArray(true);

        if (array_key_exists('Response', $result) === true && 'False' === $result['Response']) {
            match ($result['Error']) {
                'Error getting data.' => throw new NoResultException(),
            };
        }

        return $result;
    }
}

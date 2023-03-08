<?php

declare(strict_types=1);

namespace App\Omdb\Client;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;
use function array_key_exists;

/**
 * // TODO : Rated : enum
 * // TODO : Type : enum
 * // TODO : Response : enum
 * @phpstan-type OmdbMovieResult array{Title: string, Year: string, Rated: string, Released: string, Genre: string, Plot: string, Poster: string, imdbID: string, Type: string, Response: string}
 * @phpstan-type OmdbMovieSearchResults list<array{Title: string, Year: string, imdbID: string, Type: string, Poster: string}>
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
                'i'    => $imdbId,
                'plot' => 'full',
                'r'    => 'json',
            ],
        ]);

        /** @var OmdbMovieResult $result */
        try {
            $result = $response->toArray(true);
        } catch (Throwable $throwable) {
            throw new NoResultException($throwable);
        }

        if (array_key_exists('Response', $result) === true && 'False' === $result['Response']) {
            throw new NoResultException();
        }

        return $result;
    }

    /**
     * @return OmdbMovieSearchResults
     */
    public function searchByName(string $name): array
    {
        $response = $this->omdbApiClient->request('GET', '/', [
            'query' => [
                'type' => 'movie',
                'r'    => 'json',
                'page' => '1',
                's'    => $name,
            ],
        ]);

        /** @var OmdbMovieSearchResults $result */
        try {
            $result = $response->toArray(true);
        } catch (Throwable $throwable) {
            throw new NoResultException($throwable);
        }

        if (array_key_exists('Response', $result) === true && 'False' === $result['Response']) {
            throw new NoResultException();
        }

        return $result['Search'];
    }
}

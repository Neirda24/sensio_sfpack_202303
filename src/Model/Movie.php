<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\Genre as GenreEntity;
use App\Entity\Movie as MovieEntity;
use App\Omdb\Client\OmdbApiConsumer;
use DateTimeImmutable;
use Symfony\Component\String\Slugger\SluggerInterface;
use function array_map;
use function explode;
use function str_starts_with;

/**
 * @phpstan-import-type OmdbMovieResult from OmdbApiConsumer
 */
final class Movie
{
    /**
     * @param list<string> $genres
     */
    public function __construct(
        public readonly string            $title,
        public readonly string            $slug,
        public readonly string            $poster,
        public readonly DateTimeImmutable $releasedAt,
        public readonly string            $plot,
        public readonly array             $genres,
        public readonly Rated             $rated,
    ) {
    }

    public static function fromMovieEntity(MovieEntity $movieEntity): self
    {
        return new self(
            title: $movieEntity->getTitle(),
            slug: $movieEntity->getSlug(),
            poster: $movieEntity->getPoster(),
            releasedAt: $movieEntity->getReleasedAt(),
            plot: $movieEntity->getPlot(),
            genres: array_map(static function (GenreEntity $genre): string {
                return $genre->getName();
            }, $movieEntity->getGenres()->toArray()),
            rated: $movieEntity->getRated(),
        );
    }

    /**
     * @param OmdbMovieResult $omdbApiResult
     */
    public static function fromOmdbApiResult(array $omdbApiResult, SluggerInterface $slugger): self
    {
        return new self(
            title: $omdbApiResult['Title'],
            slug: $slugger->slug($omdbApiResult['Title'])->toString(),
            poster: $omdbApiResult['Poster'],
            releasedAt: new DateTimeImmutable($omdbApiResult['Released']),
            plot: $omdbApiResult['Plot'],
            genres: explode(', ', $omdbApiResult['Genre']),
            rated: Rated::tryFrom($omdbApiResult['Rated']) ?? Rated::GeneralAudiences,
        );
    }

    public function isRemotePoster(): bool
    {
        return str_starts_with($this->poster, 'http') === true;
    }
}

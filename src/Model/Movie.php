<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\Genre as GenreEntity;
use App\Entity\Movie as MovieEntity;
use DateTimeImmutable;
use function array_map;

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
        );
    }
}

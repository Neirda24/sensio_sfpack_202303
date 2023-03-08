<?php

declare(strict_types=1);

namespace App\Omdb\Bridge;

use App\Omdb\Client\OmdbApiConsumerInterface;

final class AutoImportInDatabaseConsumer implements OmdbApiConsumerInterface
{
    public function __construct(
        private readonly AutoImportConfig         $autoImportConfig,
        private readonly OmdbApiConsumerInterface $omdbApiConsumer,
        private readonly OmdbToDatabaseImporter   $omdbToDatabaseImporter,
    ) {
    }

    public function getById(string $imdbId): array
    {
        $result = $this->omdbApiConsumer->getById($imdbId);

        if (true === $this->autoImportConfig->getValue()) {
            $this->omdbToDatabaseImporter->importFromApiData($result, true);
        }

        return $result;
    }

    public function searchByName(string $name): array
    {
        return $this->omdbApiConsumer->searchByName($name);
    }
}

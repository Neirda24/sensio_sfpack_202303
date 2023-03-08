<?php

declare(strict_types=1);

namespace App\Omdb\Client;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

final class NoResultException extends Exception implements HttpExceptionInterface
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('Result not found on OMDB API.', 404, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->getCode();
    }

    public function getHeaders(): array
    {
        return [];
    }
}

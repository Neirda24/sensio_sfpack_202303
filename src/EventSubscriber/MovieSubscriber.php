<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class MovieSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            MovieAddedEvent::class => [
                ['notifyAdmins', 0],
            ],
        ];
    }

    public function notifyAdmins(MovieAddedEvent $event): void
    {
        dump($this->userRepository->listAllAdmins());
    }
}

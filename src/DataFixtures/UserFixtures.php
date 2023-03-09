<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Psr\Clock\ClockInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class UserFixtures extends Fixture
{
    /** @var list<array{username: string, password: string, dateOfBirth: string, age: int, admin: bool}> */
    private const USERS = [
        [
            'username'    => 'adrien',
            'password'    => 'adrien',
            'dateOfBirth' => '10/05',
            'age'         => 30,
            'admin'       => true,
        ],
        [
            'username'    => 'max',
            'password'    => 'max',
            'dateOfBirth' => '12/03',
            'age'         => 15,
            'admin'       => false,
        ],
        [
            'username'    => 'louise',
            'password'    => 'louise',
            'dateOfBirth' => '02/12',
            'age'         => 2,
            'admin'       => false,
        ],
    ];

    public function __construct(
        private readonly PasswordHasherFactoryInterface $passwordHasherFactory,
        private readonly ClockInterface                 $clock,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::USERS as $userData) {
            $user = (new User())
                ->setUsername($userData['username'])
                ->setDateOfBirth(DateTimeImmutable::createFromFormat('!d/m/Y',
                    $userData['dateOfBirth'] . '/' . $this->clock->now()->modify("-{$userData['age']} years")->format('Y'),
                ))
                ->setPassword($this->passwordHasherFactory->getPasswordHasher(User::class)->hash($userData['password']));

            if (true === $userData['admin']) {
                $user->setRoles(['ROLE_ADMIN']);
            }

            $manager->persist($user);
        }

        $manager->flush();
    }
}

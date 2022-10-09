<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends BaseFixtures implements FixtureGroupInterface
{
    private const COUNT_USER = 5;
    private const PLAIN_PASSWORD = 'password';
    public const USER_REFERENCE = 'user_';

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::COUNT_USER; $i++) {
            $user = new User();
            $user->setEmail($this->faker->unique()->safeEmail);
            $user->setFirstname($this->faker->firstName);
            $user->setLastname($this->faker->lastName);
            $user->setPhoneNumber((string) $this->faker->unique()->randomNumber());
            $user->setWhenConvenientReceiveCalls($this->faker->words(15, true));
            $user->setPassword($this->passwordHasher->hashPassword($user, self::PLAIN_PASSWORD));

            $manager->persist($user);

            $this->addReference(self::USER_REFERENCE . $i, $user);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return [
            'users',
        ];
    }
}

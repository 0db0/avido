<?php

namespace App\DataFixtures;

use App\Entity\Advert;
use App\Enum\AdvertStatus;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;

class AdvertFixtures extends BaseFixtures
{
    private const COUNT_ADVERT = 5;

    public function __construct(private readonly UserRepository $userRepository)
    {
        parent::__construct();
    }

    public function load(ObjectManager $manager): void
    {
//        dd($this->faker->randomNumber());

        for ($i = 0; $i < self::COUNT_ADVERT; $i++) {
            $advert = new Advert();
            $advert->setName($this->faker->words(asText: true));
            $advert->setDescription($this->faker->words(20, asText: true));

//            $advert->setSeller($this->getReference(UserFixtures::USER_REFERENCE . $i));
            $advert->setSeller($this->userRepository->find(14));

            $advert->setStatus($this->faker->randomElement(AdvertStatus::cases()));
            $advert->setCost($this->faker->randomNumber());
            $advert->setCountViews($this->faker->numberBetween(9, 99));

            $manager->persist($advert);
        }

        $manager->flush();
    }
}

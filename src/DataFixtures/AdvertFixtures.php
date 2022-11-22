<?php

namespace App\DataFixtures;

use App\Entity\Advert;
use App\Enum\AdvertStatus;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AdvertFixtures extends BaseFixtures implements DependentFixtureInterface
{
    private const COUNT_ADVERT = 5;

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::COUNT_ADVERT; $i++) {
            $advert = new Advert();
            $advert->setName($this->faker->words(asText: true));
            $advert->setDescription($this->faker->words(20, asText: true));
            $advert->setSeller($this->getReference(UserFixtures::USER_REFERENCE . $i));
            $advert->setStatus($this->faker->randomElement(AdvertStatus::cases()));
            $advert->setCost($this->faker->randomNumber());
            $advert->setCountViews($this->faker->numberBetween(9, 99));

            $manager->persist($advert);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}

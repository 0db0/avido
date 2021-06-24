<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends BaseFixtures
{
    private const COUNT_FIXTURES = 5;

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < self::COUNT_FIXTURES; $i++) {
            $category = new Category();
            $category->setName($this->faker->word);
            $category->setDescription($this->faker->words(rand(10, 30), asText: true));
            $category->setUrlCode($this->faker->md5);
            $category->setParentId($i - 1);
            $manager->persist($category);
        }
        $manager->flush();
    }
}

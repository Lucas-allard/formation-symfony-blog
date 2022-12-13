<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0, $i < 5; $i++) {
            $category = new Category();
            $category->setName("test");
            $manager->persist($product);

            $manager->flush();
        }

    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CategoryFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($c = 0; $c < 5; $c++) {
            $category = new Category();
            $category->setName($faker->word());
            $manager->persist($category);

            $this->addReference('category' . $c, $category);
        }

        $manager->flush();


    }
}

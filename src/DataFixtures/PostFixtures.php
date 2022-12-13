<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PostFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($p = 0; $p < 10; $p++) {
            $post = new Post();
            $post->setTitle($faker->words(rand(3, 10), true))
                ->setBody($faker->paragraphs(rand(2, 5), true))
                ->setAuthor($faker->name())
                ->setImg($faker->imageUrl(640, 480, 'animals', true));

            for ($i = 0; $i < rand(1, 3); $i++) {
                $post->addCategory($this->getReference("category" . rand(0, 4)));
            }

            $manager->persist($post);
            $this->addReference("post" . $p, $post);

        }

        $manager->flush();

    }
}

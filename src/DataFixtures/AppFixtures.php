<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');


        for ($co = 0; $co < 1; $co++) {
            $comment = new Comment();
            $comment->setUser($user)
                ->setPost($post)
                ->setBody($faker->words(rand(10, 30), true));

            $manager->persist($comment);

        }
        $manager->flush();
    }
}

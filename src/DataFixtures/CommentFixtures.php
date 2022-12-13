<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CommentFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {

        $faker = Faker\Factory::create('fr_FR');

        for ($u = 0; $u < 5; $u++) {
            $comment = new Comment();
            $comment->setUser($this->getReference('user' . rand(0, 3)))
                ->setPost($this->getReference('post' . rand(0, 9)))
                ->setBody($faker->words(3, true));

            $manager->persist($comment);

        }

        $manager->flush();


    }
}
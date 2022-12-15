<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {

        $faker = Faker\Factory::create('fr_FR');

        for ($u = 0; $u < 20; $u++) {
            $comment = new Comment();
            $comment->setUser($this->getReference('user' . rand(0, 3)))
                ->setPost($this->getReference('post' . rand(0, 9)))
                ->setBody($faker->words(3, true))
                ->setIsValid(true);

            $manager->persist($comment);

        }

        for ($c = 0; $c < 5; $c++) {
            $comment = new Comment();
            $comment->setUser($this->getReference('user' . rand(0, 3)))
                ->setPost($this->getReference('post' . rand(0, 9)))
                ->setBody($faker->words(3, true))
                ->setIsValid(false);

            $manager->persist($comment);

        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserAdminFixtures::class,
            UserFixtures::class,
            CategoryFixtures::class,
            PostFixtures::class,
        ];
    }
}

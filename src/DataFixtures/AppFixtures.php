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

        $userAdmin = new User();
        $userAdmin->setEmail('admin@symblog.com')
            ->setPassword('password');

        $manager->persist($userAdmin);


        for ($c = 0; $c < 5; $c++) {
            $category = new Category();
            $category->setName($faker->words(rand(3, 10), true));
            $manager->persist($category);

            $user = new User();
            $user->setEmail($faker->email())
                ->setPassword('password');

            $manager->persist($user);


            for ($p = 0; $p < 10; $p++) {
                $post = new Post();
                $post->setUser($userAdmin)
                    ->setTitle($faker->words(rand(3, 10), true))
                    ->setBody($faker->paragraphs(rand(2, 5), true))
                    ->setAuthor($faker->name())
                    ->setImg("https://placeimg.com/300/300/any");

                $manager->persist($post);

                for ($co = 0; $co < 1; $co++) {
                    $comment = new Comment();
                    $comment->setUser($user)
                        ->setPost($post)
                        ->setBody($faker->words(rand(10, 30), true));

                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}

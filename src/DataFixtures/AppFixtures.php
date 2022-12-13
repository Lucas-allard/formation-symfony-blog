<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $userAdmin = new User();
        $userAdmin->setEmail('admin@symblog.com')
            ->setPassword('password');

        $manager->persist($userAdmin);


        for ($c = 0; $c < 5; $c++) {
            $category = new Category();
            $category->setName("catégorie " . $c);
            $manager->persist($category);

            $user = new User();
            $user->setEmail("user-" . $c . "@symblog.com")
                ->setPassword('password');

            $manager->persist($user);


            for ($p = 0; $p < 10; $p++) {
                $post = new Post();
                $post->setUser($userAdmin)
                    ->setTitle("post " . $p)
                    ->setBody("du contenu");

                $manager->persist($post);

                for ($co = 0; $co < 1; $co++) {
                    $comment = new Comment();
                    $comment->setUser($user)
                        ->setPost($post)
                        ->setBody("du contenu");

                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}

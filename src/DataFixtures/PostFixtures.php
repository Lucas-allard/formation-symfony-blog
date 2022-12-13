<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PostFixtures extends Fixture
{
    public const POST_REFERENCE = 'post';

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($p = 0; $p < 10; $p++) {
            $post = new Post();
            $post->setUser($this->getReference(UserAdminFixtures::ADMIN_USER_REFERENCE)
                ->setTitle($faker->words(rand(3, 10), true))
                ->setBody($faker->paragraphs(rand(2, 5), true))
                ->setAuthor($faker->name())
                ->setImg($faker->imageUrl(640, 480, 'animals', true)));

            $manager->persist($post);
        }

        $manager->flush();

        $this->addReference(self::POST_REFERENCE, $post);

    }
}

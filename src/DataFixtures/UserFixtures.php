<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class UserFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {

        $faker = Faker\Factory::create('fr_FR');

        for ($u = 0; $u < 5; $u++) {
            $user = new User();
            $user->setEmail($faker->email())
                ->setPassword('password');

            $manager->persist($user);

            $this->addReference("user" . $u, $user);
        }
        $manager->flush();
    }
}

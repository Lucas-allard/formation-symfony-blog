<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }


    public function load(ObjectManager $manager): void
    {

        $faker = Faker\Factory::create('fr_FR');

        $user = new User();

        $user->setEmail("lucas@gmail.com")
            ->setPassword($this->hasher->hashPassword($user, "password"))
            ->setRoles(["ROLE_USER"]);;

        $manager->persist($user);

        for ($u = 0; $u < 5; $u++) {
            $user = new User();
            $user->setEmail($faker->email())
                ->setPassword($this->hasher->hashPassword($user, "password"))
                ->setRoles(["ROLE_USER"]);;

            $manager->persist($user);

            $this->addReference("user" . $u, $user);
        }
        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class UserAdminFixtures extends Fixture
{
    public const ADMIN_USER_REFERENCE = 'admin-user';

    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');


        $userAdmin = new User();
        $userAdmin->setEmail('admin@symblog.com')
            ->setPassword($this->hasher->hashPassword($userAdmin, "password"))
            ->setRoles(["ROLE_USER", "ROLE_ADMIN"]);

        $manager->persist($userAdmin);

        $manager->flush();

        $this->addReference(self::ADMIN_USER_REFERENCE, $userAdmin);

        for ($u = 0; $u < 5; $u++) {
            $user = new User();
            $user->setEmail($faker->email())
                ->setPassword($this->hasher->hashPassword($user, "password"))
                ->setRoles(["ROLE_USER", "ROLE_ADMIN"]);;

            $manager->persist($user);

            $this->addReference("user_admin" . $u, $user);
        }
        $manager->flush();
    }
}

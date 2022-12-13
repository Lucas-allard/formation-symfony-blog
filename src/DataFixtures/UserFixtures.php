<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const USER_REFERENCE = 'user';

    public function load(ObjectManager $manager): void
    {
        for ($u = 0; $u < 5; $u++) {
            $user = new User();
            $user->setEmail('@symblog.com')
                ->setPassword('password');

            $manager->persist($user);
        }

        $manager->flush();

        $this->addReference(self::USER_REFERENCE, $user);
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppUserFixtures extends Fixture
{
    public const USER_FIXTURE = 'user_fixture';

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setName('Admin');
        $manager->persist($user);

        $this->setReference(self::USER_FIXTURE, $user);

        $manager->flush();
    }
}
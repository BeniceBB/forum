<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppAuthorFixtures extends Fixture
{
    public const AUTHOR_FIXTURE = 'author_fixture';

    public function load(ObjectManager $manager)
    {
        $author = new Author();
        $author->setName('Admin');
        $manager->persist($author);

        $this->setReference(self::AUTHOR_FIXTURE, $author);

        $manager->flush();
    }
}
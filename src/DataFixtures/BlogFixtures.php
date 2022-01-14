<?php

namespace App\DataFixtures;

use App\Entity\Blog;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class BlogFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $user = $this->getReference(UserFixtures::USER_FIXTURE);
        for ($i = 0; $i < 4; ++$i) {
            $blog = new Blog();
            $blog->setTitle('Lorem ipsum');
            $blog->setBody('Lorem ipsum dolor sit amet, consectetur adipiscing elit.
              Proin sodales, arcu non commodo vulputate, neque lectus luctus metus,
              ac hendrerit mi erat eu ante. Nullam blandit arcu erat,
              vitae pretium neque suscipit vitae.
              Pellentesque sit amet lacus in metus placerat posuere. Aliquam hendrerit risus elit, non commodo nulla cursus id.
              Vivamus tristique felis leo, vitae laoreet sapien eleifend vitae. Etiam varius sollicitudin tincidunt');
            $blog->setShortDescription('Lorem ipsum description');
            $blog->setUser($user);
            $manager->persist($blog);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
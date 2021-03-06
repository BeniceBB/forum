<?php

namespace App\Tests\Service;

use App\Services\BlogContentManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BlogContentTest extends KernelTestCase
{
    public function testListQuery(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $blogContentGenerator = $container->get(BlogContentManager::class);
        $blogContent = $blogContentGenerator->getBlogContent(1);

        $this->assertEquals('Lorem ipsum', $blogContent->getTitle(1));
    }
}
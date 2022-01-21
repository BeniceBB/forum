<?php


namespace App\Tests\Service;

use App\Services\BlogListManager;
use PHPUnit\Framework\TestCase;

class BlogListTest extends TestCase
{
    public function testBlogTitles(): void
    {
        $stub = $this->createMock(BlogListManager::class);
        $stub->method('checkBlogTitles')->willReturn(1);
        $this->assertEquals(1, $stub->contains_title);
    }

}
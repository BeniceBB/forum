<?php

namespace App\Tests\Entity;

use App\Entity\Blog;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class BlogTest extends TestCase
{
    public function testAdd(): void
    {
        $blog = new Blog();
        $this->assertNull($blog->getId());
    }

    public function testBlog(): void
    {
        $stub = $this->createMock(Blog::class);
        $stub->method('getUser')->willReturn(new User());
        $this->assertEquals(new User(), $stub->getUser());
    }
}
<?php

namespace App\Tests\Entity;

use App\Entity\Blog;
use PHPUnit\Framework\TestCase;

class BlogTest extends TestCase
{
    public function testAdd(): void
    {
        $blog = new Blog();

        $this->assertSame(null, $blog->getId());
    }

}
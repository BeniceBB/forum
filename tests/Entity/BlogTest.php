<?php

namespace App\Tests\Entity;

use App\Entity\Blog;
use PHPUnit\Framework\TestCase;

class BlogTest extends TestCase
{
    public function testAdd()
    {
        $blog = new Blog();

        $this->assertSame(null, $blog->getId());
    }

}
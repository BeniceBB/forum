<?php


namespace App\Tests\Service;

use App\Services\BlogListManager;
use PHPUnit\Framework\TestCase;

class BlogListTest extends TestCase
{
    public function testCheckByPostType(): void
    {
       $newTest = $this->createMock(BlogListManager::class);
       $newTest->method('getFilters');
       $newTest->getFilteredBlogs();
       dump($newTest);exit;
       static::assertSame([], $newTest);
    }

}
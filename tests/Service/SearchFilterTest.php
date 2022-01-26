<?php

namespace App\Tests\Service;

use App\Entity\Blog;
use App\Services\BlogListManager;
use App\Services\SearchFilterManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SearchFilterTest extends KernelTestCase
{
    /**
     * @group Unit
     */

    public function testFilterBlogsWithoutData(): void
    {
        $blogListManager = $this->createMock(BlogListManager::class);

        $searchFilterManager = new SearchFilterManager($blogListManager);

        $data = [];

        $result = $searchFilterManager->filterBlogs($data);
        static::assertIsArray($result);
        static::assertEmpty($result);
    }

    public function testFilterBlogs(): void
    {
        $blogListManager = $this->createMock(BlogListManager::class);

        $searchFilterManager = new SearchFilterManager($blogListManager);

        $data = ['search' => 'test', 'type' => ['all'], 'postsPerPage' => 5];

        $result = $searchFilterManager->filterBlogs($data);
        static::assertIsArray($result);
    }

    public function testGetBlogs(): void
    {
        $blogListManager = $this->createMock(BlogListManager::class);

        $searchFilterManager = new SearchFilterManager($blogListManager);

        $data = ['search' => 'test', 'type' => ['all'], 'postsPerPage' => 5];
        $page = 0;

        $result = $searchFilterManager->getBlogs($data, $page);
        static::assertIsArray($result);
    }

    public function testTotalFilteredBlogs(): void
    {
        $blogListManager = $this->createMock(BlogListManager::class);

        $searchFilterManager = new SearchFilterManager($blogListManager);

        $data = ['search' => 'test', 'type' => ['all'], 'postsPerPage' => 5];
        $result = $searchFilterManager->totalFilteredBlogs($data);

        static::assertIsInt($result);
    }

    /**
     * @group Integration
     */

    public function testFilterBlogsWordNotFound(): void
    {
       self::bootKernel();

       $container = static::getContainer();
       $searchFilterManager = $container->get(SearchFilterManager::class);

       $data = ['search' => 'this string does not exist', 'type' => ['all']];

       $result = $searchFilterManager->filterBlogs($data);
       $this->assertEmpty($result);
    }

    /**
     * @group Integration
     */

    public function testFilterBlogsWordNotFoundInType(): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $searchFilterManager = $container->get(SearchFilterManager::class);

        $data = ['search' => 'Lorem', 'type' => ['author']];

        $result = $searchFilterManager->filterBlogs($data);
        $this->assertEmpty($result);
    }

    /**
     * @group Integration
     */

    public function testFilterBlogsMultipleTypes(): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $searchFilterManager = $container->get(SearchFilterManager::class);

        $data = ['search' => 'Lorem', 'type' => ['author', 'title']];

        $result = $searchFilterManager->filterBlogs($data);
        $this->assertIsArray($result);
        $this->assertCount(4, $result);
    }

    /**
     * @group Integration
     */

    public function testFilterBlogsNoDataSet(): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $searchFilterManager = $container->get(SearchFilterManager::class);

        $data = [];

        $result = $searchFilterManager->filterBlogs($data);
        $this->assertIsArray($result);
        $this->assertCount(0, $result);
    }

    /**
     * @group Integration
     */

    public function testGetBlogsPerPageNoLimitSet(): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $searchFilterManager = $container->get(SearchFilterManager::class);

        $data = ['search' => '', 'type' => ['all']];

        $result = $searchFilterManager->getBlogs($data);
        $this->assertIsArray($result);
        $this->assertCount(5, $result);
    }

    /**
     * @group Integration
     */

    public function testGetBlogsPerPageLimitSet(): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $searchFilterManager = $container->get(SearchFilterManager::class);

        $data = ['search' => '', 'type' => ['all'], 'postsPerPage' => 3];

        $result = $searchFilterManager->getBlogs($data);
        $this->assertIsArray($result);
        $this->assertCount(3, $result, 'Did not count 3');
    }

    /**
     * @group Integration
     */

    public function testTotalFilteredBlogsCount(): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $searchFilterManager = $container->get(SearchFilterManager::class);

        $data = ['search' => 'lorem', 'type' => ['title'], 'postsPerPage' => 3];

        $result = $searchFilterManager->totalFilteredBlogs($data);
        $this->assertIsInt($result);
        $this->assertEquals(4, $result);
    }


}
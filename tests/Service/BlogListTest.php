<?php


namespace App\Tests\Service;

use App\Entity\Blog;
use App\Entity\User;
use App\Repository\BlogRepository;
use App\Services\BlogListManager;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogListTest extends KernelTestCase
{
    /**
     * @group Unit
     */
    public function testGetAllBlogs(): void
    {
        $blogRepository = $this->createMock(BlogRepository::class);
        $blogRepository->expects(static::once())->method('findAll')->willReturn([new Blog()]);

        $blogListManager = new BlogListManager($blogRepository);
        $result = $blogListManager->getAllBlogs();
        static::assertCount(1, $result);
        static::assertInstanceOf(Blog::class, $result[0]);
    }

    public function testCheckByPostTypeNotInFilteredTypes(): void
    {
        $blogRepository = $this->createMock(BlogRepository::class);

        $blogListManager = new BlogListManager($blogRepository);

        $test = 'title';
        $filteredTypes = ['author'];
        $wordToSearch = 'test';
        $value = 'Een woord';

        $result = $blogListManager->checkPostByType($test, $filteredTypes, $wordToSearch, $value);
        static::assertFalse($result);
    }

    public function testCheckByPostTypeNotInFilteredTypesWithoutValue(): void
    {
        $blogRepository = $this->createMock(BlogRepository::class);

        $blogListManager = new BlogListManager($blogRepository);

        $test = 'title';
        $filteredTypes = ['author'];
        $wordToSearch = 'test';

        $result = $blogListManager->checkPostByType($test, $filteredTypes, $wordToSearch);
        static::assertFalse($result);
    }

    public function testCheckByPostTypeWordNotInContent(): void
    {
        $blogRepository = $this->createMock(BlogRepository::class);

        $blogListManager = new BlogListManager($blogRepository);

        $test = 'title';
        $filteredTypes = ['title'];
        $wordToSearch = 'test';
        $value = 'Een woord';

        $result = $blogListManager->checkPostByType($test, $filteredTypes, $wordToSearch, $value);
        static::assertFalse($result);
    }

    public function testCheckByPostTypeWordNotInContentWithoutValue(): void
    {
        $blogRepository = $this->createMock(BlogRepository::class);

        $blogListManager = new BlogListManager($blogRepository);

        $test = 'title';
        $filteredTypes = ['title'];
        $wordToSearch = 'test';

        $result = $blogListManager->checkPostByType($test, $filteredTypes, $wordToSearch);
        static::assertFalse($result);
    }

    public function testCheckByPostType(): void
    {
        $blogRepository = $this->createMock(BlogRepository::class);

        $blogListManager = new BlogListManager($blogRepository);

        $test = 'title';
        $filteredTypes = ['title'];
        $wordToSearch = 'test';
        $value = 'Een woord test';

        $result = $blogListManager->checkPostByType($test, $filteredTypes, $wordToSearch, $value);
        static::assertTrue($result);
    }

    public function testGetFiltersAllNotInArray(): void
    {
        $blogRepository = $this->createMock(BlogRepository::class);

        $blogListManager = new BlogListManager($blogRepository);

        $data = ['type' => ['title', 'author']];

        $result = $blogListManager->getFilters($data);
        static::assertSame(['title', 'author'], $result);
    }

    public function testGetFiltersAllInArray(): void
    {
        $blogRepository = $this->createMock(BlogRepository::class);

        $blogListManager = new BlogListManager($blogRepository);

        $data = ['type' => ['all']];

        $result = $blogListManager->getFilters($data);
        static::assertSame(['title', 'short_description', 'body', 'user', 'all'], $result);
    }

    public function testGetWordToSearch(): void
    {
        $blogRepository = $this->createMock(BlogRepository::class);

        $blogListManager = new BlogListManager($blogRepository);

        $data = ['search' => 'TEST'];

        $result = $blogListManager->getWordToSearch($data);
        static::assertSame('test', $result);
    }

    public function testGetFilteredBlogsContainsNothing(): void
    {
        $blogRepository = $this->createMock(BlogRepository::class);

        $blogListManager = new BlogListManager($blogRepository);

        $blogs = [new Blog()];
        $blogs[0]->setUser(new User());

        $filters = ['title', 'post'];
        $wordToSearch = 'test';

        $result = $blogListManager->getFilteredBlogs($blogs, $filters, $wordToSearch);
        static::assertEmpty($result);
        static::assertIsArray($result);
    }

    public function testGetFilteredBlogs(): void
    {
        $blogRepository = $this->createMock(BlogRepository::class);

        $blogListManager = new BlogListManager($blogRepository);

        $blogs = [new Blog()];
        $blogs[0]->setTitle('test title');
        $blogs[0]->setUser(new User());

        $filters = ['title', 'post'];
        $wordToSearch = 'title';

        $result = $blogListManager->getFilteredBlogs($blogs, $filters, $wordToSearch);
        static::assertSame($blogs, $result);
    }

    public function testLimitBlogsFirstPage(): void
    {
        $blogRepository = $this->createMock(BlogRepository::class);

        $blogListManager = new BlogListManager($blogRepository);

        $data = ['postsPerPage' => 2];
        $filteredBlogs = [new Blog(), new Blog(), new Blog()];
        $page = 0;

        $result = $blogListManager->limitBlogs($data, $filteredBlogs, $page);
        static::assertCount(2, $result);
    }

    public function testLimitBlogsLastPage(): void
    {
        $blogRepository = $this->createMock(BlogRepository::class);

        $blogListManager = new BlogListManager($blogRepository);

        $data = ['postsPerPage' => 2];
        $filteredBlogs = [new Blog(), new Blog(), new Blog()];
        $page = 1;

        $result = $blogListManager->limitBlogs($data, $filteredBlogs, $page);
        static::assertCount(1, $result);
    }

    public function testGetBlogsFromQuery(): void
    {
        $blogRepository = $this->createMock(BlogRepository::class);
        $blogRepository->expects(static::once())->method('findAll')->willReturn([new Blog()]);

        $blogListManager = new BlogListManager($blogRepository);
        $result = $blogListManager->getAllBlogs();
        static::assertCount(1, $result);
        static::assertInstanceOf(Blog::class, $result[0]);
    }

}
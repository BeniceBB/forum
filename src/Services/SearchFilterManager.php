<?php

namespace App\Services;

class SearchFilterManager
{
    private BlogListManager $blogListManager;

    public function __construct(BlogListManager $blogListManager)
    {
        $this->blogListManager = $blogListManager;
    }

    public function filterBlogs(array $data): array
    {
        $result = [];
        if (!empty($data)) {
            $blogs = $this->blogListManager->getAllBlogs();
            $filters = $this->blogListManager->getFilters($data);
            $wordToSearch = $this->blogListManager->getWordToSearch($data);
            $result = $this->blogListManager->getFilteredBlogs($blogs, $filters, $wordToSearch);
        }
        return $result;
    }

    public function getBlogs(array $data, int $page = 0): array
    {
        $filteredBlogs = $this->filterBlogs($data);
        return $this->blogListManager->limitBlogs($data, $filteredBlogs, $page);
    }

    public function totalFilteredBlogs(array $data): int
    {
        $filteredBlogs = $this->filterBlogs($data);
        return count($filteredBlogs);
    }

    public function currentBlogCount(int $page, array $filteredBlogs, array $data): int
    {
        $currentAmountBlogs = ($page + 1) * count($filteredBlogs);
        $numberOfBlogsPerPage = $data['postsPerPage'] ?? 5;
        if(count($filteredBlogs) < $numberOfBlogsPerPage)
        {
            $currentAmountBlogs = $this->totalFilteredBlogs($data);
        }

        return $currentAmountBlogs;
    }

}
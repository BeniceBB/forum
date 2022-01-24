<?php

namespace App\Services;

class SearchFilterManager
{
    private BlogListManager $blogListManager;

    public function __construct(BlogListManager $blogListManager)
    {
        $this->blogListManager = $blogListManager;
    }

    public function getBlogs(array $data, int $page = 0): array
    {
        $result = [];
        if (!empty($data)) {
            $blogs = $this->blogListManager->getAllBlogs();
            $filters = $this->blogListManager->getFilters($data);
            $wordToSearch = $this->blogListManager->getWordToSearch($data);
            $filteredBlogs = $this->blogListManager->getFilteredBlogs($blogs, $filters, $wordToSearch);
            $result = $this->blogListManager->limitBlogs($data, $filteredBlogs, $page);
        }
        return $result;
    }
}
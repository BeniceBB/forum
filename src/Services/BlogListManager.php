<?php

namespace App\Services;

use App\Repository\BlogRepository;

class BlogListManager
{
    private BlogRepository $blogRepository;

    public function __construct(BlogRepository $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }


    public function getAllBlogs(): array
    {
        return $this->blogRepository->findAll();
    }

    public function checkPostByType(string $type, array $filteredTypes, string $wordToSearch, ?string $value = ''): bool
    {
        $contains = false;
        if (in_array($type, $filteredTypes, true)) {
            $content = strtolower($value);
            if (str_contains($content, $wordToSearch) !== false) {
                $contains = true;
            }
        }
        return $contains;
    }

    public function getFilters(array $data): array
    {
        $filteredTypes = [];
        $filters = $data['type'] ?? [];
        if ($data['type'] === [] || in_array('all', $filters, true)) {
            $filteredTypes = ['title', 'description', 'post', 'author'];
        }
        foreach ($filters as $value) {
            $filteredTypes[] = $value;
        }

        return $filteredTypes;
    }

    public function getWordToSearch(array $data): string
    {
        return strtolower($data['search'] ?? '');
    }

    public function getFilteredBlogs(array $blogs, array $filters, string $wordToSearch): array
    {
        $filteredBlogs = [];
        foreach ($blogs as $blog) {
            $containsTitle = $this->checkPostByType('title', $filters, $wordToSearch, $blog->getTitle());
            $containsDescription = $this->checkPostByType('description', $filters, $wordToSearch, $blog->getShortDescription());
            $containsPost = $this->checkPostByType('post', $filters, $wordToSearch, $blog->getBody());
            $containsAuthor = $this->checkPostByType('author', $filters, $wordToSearch, $blog->getUser()->getUsername());

            if ($containsTitle === true || $containsDescription === true || $containsPost === true || $containsAuthor === true) {
                $filteredBlogs[] = $blog;
            }
        }
        return $filteredBlogs;
    }

    public function limitBlogs(array $data, array $filteredBlogs, int $page): array
    {
        $postsPerPage = $data['postsPerPage'] ?? 5;
        return array_slice($filteredBlogs, $page * $postsPerPage, $postsPerPage);
    }
}
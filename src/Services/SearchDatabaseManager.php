<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\BlogRepository;

class SearchDatabaseManager
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

    public function getBlogsByTitle(string $wordToSearch): array
    {
        return $this->blogRepository->findBy([
            'title' => $wordToSearch,
        ]);
    }

    public function getBlogsByDescription(string $wordToSearch): array
    {
        return $this->blogRepository->findBy([
            'short_description' => $wordToSearch,
        ]);
    }

    public function getBlogsByBodyText(string $wordToSearch): array
    {
        return $this->blogRepository->findBy([
            'body' => $wordToSearch,
        ]);
    }

}
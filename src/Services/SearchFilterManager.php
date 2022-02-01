<?php

namespace App\Services;

use JetBrains\PhpStorm\ArrayShape;
use Symfony\Contracts\Translation\TranslatorInterface;

class SearchFilterManager
{
    private BlogListManager $blogListManager;
    private TranslatorInterface $translator;


    public function __construct(BlogListManager $blogListManager, TranslatorInterface $translator)
    {
        $this->blogListManager = $blogListManager;
        $this->translator = $translator;
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
        if (count($filteredBlogs) < $numberOfBlogsPerPage) {
            $currentAmountBlogs = $this->totalFilteredBlogs($data);
        }
        return $currentAmountBlogs;
    }

    public function getBlogsFromQueryTypeFilter(array $data, int $page = 0): array
    {
        $data['type'] = $this->blogListManager->getFilters($data);
        $filteredBlogs = $this->blogListManager->getBlogsFromQuery($data);
        return $this->blogListManager->limitBlogs($data, $filteredBlogs, $page);
    }

    #[ArrayShape(['templateResult' => "array", 'page' => "int|null", 'numberOfBlogs' => "int", 'numberOfBlogsPerPage' => "int|mixed"])]
    public function getAllDataFilteredBlogs(array $data, array $filteredBlogs, ?int $page = 0): array
    {
        $totalFilteredBlogs = $this->totalFilteredBlogs($data);
        $currentAmountBlogs = $this->currentBlogCount($page, $filteredBlogs, $data);

        return ['templateResult' =>
            [
                'blogs' => $filteredBlogs,
                'postAmount' => $this->translator->trans('post.amount', ['amount' => $currentAmountBlogs]),
                'totalFilteredBlogs' => $totalFilteredBlogs,
            ],
            'page' => $page,
            'numberOfBlogs' => count($filteredBlogs),
            'numberOfBlogsPerPage' => $data['postsPerPage'] ?? 5,
        ];

    }

}
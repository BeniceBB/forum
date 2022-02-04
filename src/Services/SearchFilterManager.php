<?php

namespace App\Services;

use App\Entity\Blog;
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
            $result = $this->blogListManager->sortFilteredBlogs($blogs, $filters, $wordToSearch, $data);
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

    public function getBlogsFromQueryTypeFilter(array $data, int $page = 0): array
    {
        $data['orderBy'] = explode(' ', $data['orderBy']);
        $data['type'] = $this->blogListManager->getFilters($data);
        $filteredBlogs = $this->blogListManager->getBlogsFromQuery($data);
        return $this->blogListManager->limitBlogs($data, $filteredBlogs, $page);
    }

    #[ArrayShape(['templateResult' => "array", 'page' => "int|null", 'numberOfBlogs' => "int", 'numberOfBlogsPerPage' => "int|mixed"])]
    public function getAllDataFilteredBlogs(array $data, array $filteredBlogs, ?int $page = 0): array
    {
        $totalFilteredBlogs = $this->totalFilteredBlogs($data);
        $currentAmountBlogs = $this->blogListManager->currentBlogCount($page, $filteredBlogs, $data, $totalFilteredBlogs);

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
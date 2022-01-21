<?php

namespace App\Services;

use App\Entity\Blog;
use App\Repository\BlogRepository;

class SearchFilterManager
{
    private BlogListManager $blogListManager;

    public function __construct(BlogListManager $blogListManager)
    {
        $this->blogListManager = $blogListManager;
    }

    public function getBlogs(array $data, int $offset): array
    {
        $blogs = $this->blogListManager->getAllBlogs(); // array with objects

        if (!empty($data)) {
            $filteredblogs = [];
            $filteredTypes = [];

            if (!empty($data['type'])) {
                foreach ($data['type'] as $value) {
                    $filteredTypes[] = $value;
                }
            }
            $searchParm = ['wordToSearch' => $data['search'], 'type' => $filteredTypes];
            $wordtoSearch = strtolower($searchParm['wordToSearch']);

            foreach ($blogs as $blog)
            {
                $contains_title = $this->blogListManager->checkBlogTitles($blog, $wordtoSearch, $filteredTypes);
                $contains_post = $this->blogListManager->checkBlogPost($blog, $wordtoSearch, $filteredTypes);
                $contains_author = $this->blogListManager->checkBlogAuthor($blog, $wordtoSearch, $filteredTypes);
                $contains_all = $this->blogListManager->checkBlogAll($blog, $wordtoSearch, $filteredTypes);
                $contains_description = $this->blogListManager->checkBlogDescriptions($blog, $wordtoSearch, $filteredTypes);

                if ($contains_title === 1 || $contains_author === 1 || $contains_description === 1 || $contains_post === 1 || $contains_all === 1) {
                    $filteredblogs[] = $blog;
                }
            }
        }

        if (isset($filteredblogs)) {
            $blogs = $filteredblogs;
        }

//        $amountToShow = ['input' => 5];

//        if ($totalBlogs > $amountToShow['input']) {
//            $blogs = array_slice($blogs, $offset, $offset + $amountToShow['input']);
//            // paginamenu
//        }
        return $blogs;
    }
}
<?php

namespace App\Services;

use App\Repository\BlogRepository;

class SearchFilterManager
{
    private BlogRepository $blogRepository;

    public function __construct(BlogRepository $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }

    public function getBlogs(array $data, int $offset): array
    {
        $blogs = $this->blogRepository->findAll(); // array with objects
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
            foreach ($blogs as $blog) {
                $contains_all = 0;
                $contains_post = 0;
                $contains_description = 0;
                $contains_author = 0;
                $contains_title = 0;
                if (in_array('title', $filteredTypes))
                    {
                        $content = strtolower($blog->getTitle());
                        if (str_contains($content, $wordtoSearch) !== false) {
                            $contains_title = 1;
                        }
                    }
                if (in_array('description', $filteredTypes))
                    {
                        $content = strtolower($blog->getShortDescription());
                        if (str_contains($content, $wordtoSearch) !== false) {
                            $contains_description = 1;
                        }
                    }
                if (in_array('post', $filteredTypes))
                    {
                        $content = strtolower($blog->getBody());
                        if (str_contains($content, $wordtoSearch) !== false) {
                            $contains_post = 1;
                        }
                    }
                if (in_array('author', $filteredTypes))
                    {
                        $content = strtolower($blog->getUser()->getUsername());
                        if (str_contains($content, $wordtoSearch) !== false) {
                            $contains_author = 1;
                        }
                    }
                if (in_array('all', $filteredTypes) || empty($filteredTypes))
                    {
                        $content = strtolower($blog->getTitle())
                        . ' ' . $content = strtolower($blog->getBody())
                        . ' ' . $content = strtolower($blog->getShortDescription())
                        . ' ' . $content = strtolower($blog->getUser()->getUsername());
                        if (str_contains($content, $wordtoSearch) !== false) {
                            $contains_all = 1;
                        }
                    }


                    if ($contains_title === 1 || $contains_author === 1 || $contains_description === 1 || $contains_post === 1 || $contains_all === 1) {
                        $filteredblogs[] = $blog;
                    }
//                if (str_contains($content, $wordtoSearch) !== false) {
//                    $filteredblogs[] = $blog;
//                }
            }
        }

        $totalBlogs = count($blogs);

        if (isset($filteredblogs)) {

            $blogs = $filteredblogs;
            $totalBlogs = count($filteredblogs);
        }

//        $amountToShow = ['input' => 5];

//        if ($totalBlogs > $amountToShow['input']) {
//            $blogs = array_slice($blogs, $offset, $offset + $amountToShow['input']);
//            // paginamenu
//        }
        return $blogs;
    }
}
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
            dump($data['type']);exit;

            $searchParm = ['wordToSearch' => $data['search'], 'type' => [$data['type']]];

            $wordtoSearch = strtolower($searchParm['wordToSearch']);
            foreach ($blogs as $blog) {
                switch ($searchParm['type']) { // arr
                    case ['title']:
                        $content = strtolower($blog->getTitle());
                        break;
                    case ['content']:
                        $content = strtolower($blog->getBody()); // string
                        break;
                    case ['shortDescription']:
                        $content = strtolower($blog->getShortDescription());
                        break;
                    case ['author']:
                        $content = strtolower($blog->getUser()->getUsername());
                        break;
                    default:
                        $content = strtolower($blog->getTitle())
                            . ' ' . $content = strtolower($blog->getBody())
                                . ' ' . $content = strtolower($blog->getShortDescription())
                                    . ' ' . $content = strtolower($blog->getUser()->getUsername());
                }

                if (str_contains($content, $wordtoSearch) !== false) {
                    $filteredblogs[] = $blog;
                }
            }
        }

        $totalBlogs = count($blogs);

        if (isset($filteredblogs)) {
            $blogs = $filteredblogs;
            $totalBlogs = count($filteredblogs);
        }

        $amountToShow = ['input' => 7];

        if ($totalBlogs > $amountToShow['input']) {
            $blogs = array_slice($blogs, $offset, $offset + $amountToShow['input']);
            // paginamenu
        }
        return $blogs;
    }
}
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

    public function getBlogs(int $offset): array
    {
        $blogs = $this->blogRepository->findAll(); // array with objects

        $filteredblogs = [];
        $searchParm = ['wordToSearch' => 'sit amet', 'type' => ['']]; // filter op type (checkboxes)
        // if titel, omschrijving, bericht of auteur == checked : uncheck 'alle'

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

        $totalBlogs = count($filteredblogs);

        $amountToShow = ['input' => 5];

        if ($totalBlogs > $amountToShow['input']) {
            $filteredblogs = array_slice($filteredblogs, $offset, $offset + $amountToShow['input']);
            // paginamenu
        }
        // Eerste 5 op pagina
        // Doorklikken: volgende 5 etc.


        // if i=0
        return $filteredblogs;
    }
}
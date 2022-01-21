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

    public function allToLowerCase(Blog $blog)
    {
        $lowercaseTitle = strtolower($blog->getTitle());
        $lowercaseBody = strtolower($blog->getBody());
        $lowercaseDescription = strtolower($blog->getShortDescription());
        $lowercaseAuthor = strtolower($blog->getUser()->getUsername());

        $content = $lowercaseAuthor . $lowercaseBody . $lowercaseDescription . $lowercaseTitle;

        return $content;
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
//            dump($filteredTypes);exit;

            $searchParm = ['wordToSearch' => $data['search'], 'type' => $filteredTypes];
            $wordtoSearch = strtolower($searchParm['wordToSearch']);


            foreach ($blogs as $blog) {
                $contains_title = 0;
                $contains_author = 0;
                $contains_description = 0;
                $contains_post = 0;
                $contains_all = 0;

                if (in_array('title', $filteredTypes)) {
                    $contains_title = $this->blogListManager->checkBlogTitles($blog, $wordtoSearch);
                }
                if (in_array('description', $filteredTypes)) {
                    $contains_description = $this->blogListManager->checkBlogDescriptions($blog, $wordtoSearch);
                }
                if (in_array('post', $filteredTypes)) {
                    $contains_post = $this->blogListManager->checkBlogPost($blog, $wordtoSearch);
                }
                if (in_array('author', $filteredTypes)) {
                    $contains_author = $this->blogListManager->checkBlogAuthor($blog, $wordtoSearch);
                }
                if (in_array('all', $filteredTypes) || empty($filteredTypes)) {
                    $contains_all = $this->blogListManager->checkBlogAll($blog, $wordtoSearch);
                }

                if ($contains_title === 1 || $contains_author === 1 || $contains_description === 1 || $contains_post === 1 || $contains_all === 1) {
                    $filteredblogs[] = $blog; // Alle teruggekregen blogobjecten
//                        dump($filteredblogs);exit;
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
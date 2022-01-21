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
        $blogs = $this->blogRepository->findAll(); // array with objects
        return $blogs;
    }

    public function checkBlogTitles($blog, $wordtoSearch, $filteredTypes){
        $contains_title = 0;
        if (in_array('title', $filteredTypes)) {

            $content = strtolower($blog->getTitle());
            if (str_contains($content, $wordtoSearch) !== false) {
                $contains_title = 1;
            }
        }
        return $contains_title;
    }

    public function checkBlogDescriptions($blog, $wordtoSearch, $filteredTypes){
        $contains_description = 0;

        if(in_array('description', $filteredTypes)) {

            $content = strtolower($blog->getShortDescription());
            if (str_contains($content, $wordtoSearch) !== false) {
                $contains_description = 1;
            }
        }
        return $contains_description;
    }

    public function checkBlogPost($blog, $wordtoSearch, $filteredTypes){
        $contains_post = 0;
        if (in_array('post', $filteredTypes)) {

            $content = strtolower($blog->getBody());
            if (str_contains($content, $wordtoSearch) !== false) {
                $contains_post = 1;
            }
        }
        return $contains_post;
    }

    public function checkBlogAuthor($blog, $wordtoSearch, $filteredTypes){
        $contains_author = 0;
        if (in_array('author', $filteredTypes)) {

            $content = strtolower($blog->getUser()->getUsername());
            if (str_contains($content, $wordtoSearch) !== false) {
                $contains_author = 1;
            }
        }
        return $contains_author;
    }

    public function checkBlogAll($blog, $wordtoSearch, $filteredTypes){
        $contains_all = 0;
        if (in_array('all', $filteredTypes) || empty($filteredTypes)) {

            $content = strtolower($blog->getUser()->getUsername())
                . ' ' . $content = strtolower($blog->getBody())
                    . ' ' . $content = strtolower($blog->getShortDescription())
                        . ' ' . $content = strtolower($blog->getUser()->getUsername());
            if (str_contains($content, $wordtoSearch) !== false) {
                $contains_all = 1;
            }
        }
        return $contains_all;
    }
}
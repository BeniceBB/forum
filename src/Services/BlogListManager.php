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

    public function getBlogTitles(){
        $blogs = $this->getAllBlogs();
        $blogtitles = [];
        foreach ($blogs as $blog)
        {
            $blogtitles[] = strtolower($blog->getTitle());
        }
        return $blogtitles;
    }

    public function getBlogDescriptions(){
        $blogs = $this->getAllBlogs();
        $blogdescriptions = [];
        foreach ($blogs as $blog)
        {
            $blogdescriptions[] = strtolower($blog->getShortDescription());
        }
        return $blogdescriptions;
    }
}
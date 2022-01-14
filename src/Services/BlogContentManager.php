<?php

namespace App\Services;

use App\Entity\Blog;
use Doctrine\ORM\EntityManagerInterface;

class BlogContentManager
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getBlogContent(int $id): ?Blog
    {
        return $this->entityManager->getRepository(Blog::class)->find($id);
    }
}
<?php

namespace App\Repository;

use App\Entity\Blog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Blog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blog[]    findAll()
 * @method Blog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blog::class);
    }

    #[ArrayShape(['blogs' => "mixed", 'totalFilteredBlogs' => "int", 'totalPages' => "float"])]
    public function findAllBlogsBySearchParam(array $data, $page = 1): array
    {
        $qb = $this->createQueryBuilder('b')
            ->join('b.user', 'u');
        foreach ($data['type'] as $type) {
            if ($type === 'user') {
                $qb->orWhere('u.username LIKE :search');
            } else if ($type !== 'all') {
                $qb->orWhere('b.' . $type . ' LIKE :search');
            }
        }
        $qb->setParameter('search', '%' . $data['search'] . '%');
        $qb->getQuery();

        $pageSize = 5;
        $paginator = new paginator($qb);
        $totalItems = count($paginator);
        $pageCount = ceil($totalItems / $pageSize);

        $paginator
            ->getQuery()
            ->setFirstResult($pageSize * ($page - 1)) // set the offset
            ->setMaxResults($pageSize); // set the limit

        $bloglist = $qb->getQuery()->execute();

        return [
            'blogs' => $bloglist,
            'totalFilteredBlogs' => $totalItems,
            'totalPages' => $pageCount,
        ];
    }

}
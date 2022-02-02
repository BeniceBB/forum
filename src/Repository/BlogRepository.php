<?php

namespace App\Repository;

use App\Entity\Blog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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


    public function findAllBlogsBySearchParam(array $data): array
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
        $qb->orderBy('b.'.$data['orderBy'][0], $data['orderBy'][1]);
        $qb->getQuery();
        return $qb->getQuery()->execute();
    }
}
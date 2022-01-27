<?php

namespace App\Repository;

use App\Entity\Blog;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
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


    /**
     * @return Blog[]
     */
    public function findAllBlogsBySearchParam(string $wordToSearch, array $types, int $postsPerPage, User $user)
    {
        $qb = $this->createQueryBuilder('b');
        foreach ($types as $type) {
            if ($type === 'author') {
                $qb->orWhere('b.user LIKE '. $user->getUsername());
                $qb->setParameter('user', '%' . $wordToSearch . '%');
            }
            else {
                $qb->orWhere('b.' . $type . ' LIKE :' . $type);
                $qb->setParameter($type, '%' . $wordToSearch . '%');
            }
        }

        $qb->setMaxResults($postsPerPage);

        return $qb->getQuery()->execute();
    }



//    /**
//     * @return Blog[]
//     */
//    public function findAllBlogsBySearchParam(string $wordToSearch, array $types, int $postsPerPage)
//    {
//
//        $expr = $this->_em->getExpressionBuilder();
//        // SELECT * FROM `blog` WHERE `blog`.`user_id` IN (SELECT `user`.`id` FROM `user` WHERE `user`.`id` = `blog`.`user_id` AND `user`.`username` LIKE '%n%');
//        $qb = $this->createQueryBuilder('b');
//
//        foreach ($types as $type) {
//            if($type === 'author')
//            {
//                $sub = $this->_em->createQueryBuilder()
//                    ->select('u.id')
//                    ->from(User::class, 'u')
//                    ->where('u.username LIKE :username')
//                    ->setParameter('username', '%' . $wordToSearch . '%');
////                dump($sub->getQuery()->execute());exit;
//                $qb->orWhere($qb->expr()->in('b.user', $sub->getDQL()));//                $qb->from(User::class, 'u');
////                $qb->orWhere('u.id LIKE :user_id');
////                $qb->setParameter(':username', $user->getUsername());
//            }
//            else {
//                $qb->orWhere('b.' . $type . ' LIKE :' . $type);
//                $qb->setParameter($type, '%' . $wordToSearch . '%');
//            }
//
//        }
//
//        $qb->setMaxResults($postsPerPage);
////        dump($qb->getQuery());
////        exit;
////        return $qb->getQuery();
//        return $qb->getQuery()->execute();
//    }
}

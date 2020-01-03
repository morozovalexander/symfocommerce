<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Orders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Entity\User;

/**
 * OrdersRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrdersRepository extends ServiceEntityRepository
{
    /**
     * Repository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Orders::class);
    }

    /**
     * query for admin paginator
     *
     * @return QueryBuilder
     */
    public function getAllOrdersAdminQB(): QueryBuilder
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->select(['o', 'u'])
            ->from(Orders::class, 'o')
            ->leftJoin('o.user', 'u')
            ->addOrderBy('o.date', 'DESC');

        return $qb;
    }

    /**
     * return query to get users orders
     *
     * @param User $user
     * @return QueryBuilder
     */
    public function getUserOrdersAdminQB(User $user): QueryBuilder
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('o')
            ->from(Orders::class, 'o')
            ->innerJoin('o.user', 'ou')
            ->where('ou = :user')
            ->addOrderBy('o.date', 'DESC')
            ->setParameter('user', $user);

        return $qb;
    }
}

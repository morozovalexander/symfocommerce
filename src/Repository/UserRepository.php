<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * UserRepository
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * Repository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * query for admin paginator
     *
     * @return QueryBuilder
     */
    public function getAllUsersAdminQB(): QueryBuilder
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->select(['u', 'uo'])
            ->from(User::class, 'u')
            ->leftJoin('u.orders', 'uo')
            ->where('u.roles NOT LIKE :roles')
            ->setParameter('roles', '%"ROLE_ADMIN"%');

        return $qb;
    }
}

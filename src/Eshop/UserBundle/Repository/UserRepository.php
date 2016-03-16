<?php

namespace Eshop\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * UserRepository
 */
class UserRepository extends EntityRepository
{
    /**
     * query for admin paginator
     *
     * @return QueryBuilder
     */
    public function getAllUsersAdminQB()
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('a')
            ->from('UserBundle:User', 'a')
            ->where('a.roles NOT LIKE :roles')
            ->setParameter('roles', '%"ROLE_ADMIN"%');;

        return $qb;
    }
}

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
            ->select('u')
            ->from('UserBundle:User', 'u');

        return $qb;
    }
}

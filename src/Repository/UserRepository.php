<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findByUserRole($role)
    {
        $queryBuilder = $this->createQueryBuilder('user')
            ->select('user')
            ->where('user.roles LIKE :role')
            ->setParameter('role', '%' . $role . '%');
        $query = $queryBuilder->getQuery();

        return $query->execute();
    }
}

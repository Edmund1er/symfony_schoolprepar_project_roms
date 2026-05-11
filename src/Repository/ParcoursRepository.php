<?php

namespace App\Repository;

use App\Entity\Parcours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ParcoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Parcours::class);
    }

    public function findByUser($userId)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('p.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
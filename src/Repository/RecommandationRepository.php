<?php

namespace App\Repository;

use App\Entity\Recommandation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RecommandationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recommandation::class);
    }

    public function findByUserNotVue($userId)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :userId')
            ->andWhere('r.estVue = false')
            ->setParameter('userId', $userId)
            ->orderBy('r.score', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
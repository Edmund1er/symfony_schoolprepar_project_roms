<?php

namespace App\Repository;

use App\Entity\Quiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class QuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quiz::class);
    }

    public function findActive()
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.estActif = :actif')
            ->setParameter('actif', true)
            ->getQuery()
            ->getResult();
    }
}
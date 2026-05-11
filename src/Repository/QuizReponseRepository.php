<?php

namespace App\Repository;

use App\Entity\QuizReponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class QuizReponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizReponse::class);
    }

    public function findByQuestion($questionId)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.question = :questionId')
            ->setParameter('questionId', $questionId)
            ->getQuery()
            ->getResult();
    }
}
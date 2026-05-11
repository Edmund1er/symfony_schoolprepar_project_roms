<?php

namespace App\Repository;

use App\Entity\QuizQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class QuizQuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizQuestion::class);
    }

    public function findByQuizOrdered($quizId)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.quiz = :quizId')
            ->setParameter('quizId', $quizId)
            ->orderBy('q.ordre', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
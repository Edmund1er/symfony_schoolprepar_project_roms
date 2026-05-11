<?php

namespace App\Repository;

use App\Entity\ParcoursEtape;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ParcoursEtapeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParcoursEtape::class);
    }

    public function findByParcoursOrdered($parcoursId)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.parcours = :parcoursId')
            ->setParameter('parcoursId', $parcoursId)
            ->orderBy('e.ordre', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
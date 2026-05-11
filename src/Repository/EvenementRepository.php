<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Evenement>
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    public function getAvailableCategories(): array
    {
        return $this->createQueryBuilder('e')
            ->select('DISTINCT e.categorie')
            ->where('e.categorie IS NOT NULL')
            ->getQuery()
            ->getResult();
    }

    public function findByFiliereAndCategorie($filiereId = null, $categorie = null): array
    {
        $qb = $this->createQueryBuilder('e')
            ->orderBy('e.date', 'ASC');
        
        if ($filiereId && $filiereId !== 'all') {
            $qb->andWhere('e.filiere = :filiereId')
               ->setParameter('filiereId', $filiereId);
        }
        
        if ($categorie && $categorie !== 'all') {
            $qb->andWhere('e.categorie = :categorie')
               ->setParameter('categorie', $categorie);
        }
        
        return $qb->getQuery()->getResult();
    }
}
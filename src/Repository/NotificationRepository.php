<?php

namespace App\Repository;

use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    public function findByUserNotRead($userId)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.user = :userId')
            ->andWhere('n.estLue = false')
            ->setParameter('userId', $userId)
            ->orderBy('n.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
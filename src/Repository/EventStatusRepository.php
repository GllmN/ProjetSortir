<?php

namespace App\Repository;

use App\Entity\EventStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventStatus[]    findAll()
 * @method EventStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventStatus::class);
    }

}

<?php

namespace App\Repository;

use App\Entity\Bookings;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bookings>
 */
class BookingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bookings::class);
    }

    /**
     * @param User $user
     * @return Booking[]
     */
    
     public function findByUser(User $user): array
     {
         return $this->createQueryBuilder('b')
             ->andWhere('b.username = :user')
             ->setParameter('user', $user)
             ->orderBy('b.date', 'DESC')
             ->getQuery()
             ->getResult();
     }
}
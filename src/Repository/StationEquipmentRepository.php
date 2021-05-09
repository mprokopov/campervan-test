<?php

namespace App\Repository;

use App\Entity\StationEquipment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StationEquipment|null find($id, $lockMode = null, $lockVersion = null)
 * @method StationEquipment|null findOneBy(array $criteria, array $orderBy = null)
 * @method StationEquipment[]    findAll()
 * @method StationEquipment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StationEquipmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StationEquipment::class);
    }

    // /**
    //  * @return StationEquipment[] Returns an array of StationEquipment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StationEquipment
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

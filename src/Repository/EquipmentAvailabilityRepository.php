<?php

namespace App\Repository;

use App\Entity\EquipmentAvailability;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EquipmentAvailability|null find($id, $lockMode = null, $lockVersion = null)
 * @method EquipmentAvailability|null findOneBy(array $criteria, array $orderBy = null)
 * @method EquipmentAvailability[]    findAll()
 * @method EquipmentAvailability[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipmentAvailabilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EquipmentAvailability::class);
    }

    // /**
    //  * @return EquipmentAvailability[] Returns an array of EquipmentAvailability objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EquipmentAvailability
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findChangesByStationAggregated($station)
    {
        return $this->createQueryBuilder('equipment_availability')
            ->addSelect('SUM(equipment_availability.bookingAmount) AS bookingAggregate')
            ->andWhere('equipment_availability.station = :station')
            ->setParameter('station', $station->getId())
            ->groupBy('equipment_availability.bookingDate, equipment_availability.station, equipment_availability.equipment')
            ->getQuery()
            ->getResult()
            ;
    }

    /*
     * deprecated
     */
    public function findOneByDateAndEquipment(\DateTimeInterface $date, $equipment, $station)
    {
        return $this->createQueryBuilder('equipment_availability')
            ->andWhere('equipment_availability.bookingDate = :booking_date')
            ->setParameter('booking_date', $date->format('Y-m-d'))
            ->andWhere('equipment_availability.station = :station_id')
            ->setParameter('station_id', $station->getId())
            ->andWhere('equipment_availability.equipment = :equipment_id')
            ->setParameter('equipment_id', $equipment->getId())
            ->select('SUM(equipment_availability.bookingAmount) AS bookingAggregate')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}

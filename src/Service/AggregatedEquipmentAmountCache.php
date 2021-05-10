<?php
namespace App\Service;

use App\Entity\Station;
use App\Entity\StationEquipment;
use App\Repository\EquipmentAvailabilityRepository;

/*
 * keeps aggregated equipment amounts
 */
class AggregatetedEquipmentAmountCache
{
    private $repository;
    private $cache = [];

    public function __construct(EquipmentAvailabilityRepository $equipmentAvailabilityRepository)
    {
        $this->repository = $equipmentAvailabilityRepository;
    }

    public function populate(Station $station)
    {
        foreach($this->repository->findChangesByStationAggregated($station) as $item) {
            $record = $item[0];
            $change = $item['bookingAggregate'];
            $this->cache[$record->getBookingDate()->format('Y-m-d')][$record->getEquipment()->getId()] = $change;
        }
    }

    public function findByDateAndEquipment(\DateTimeInterface $date, StationEquipment $equipment): int
    {
        $id = $equipment->getEquipment()->getId();
        $dt = $date->format('Y-m-d');

        if (array_key_exists($dt, $this->cache) && array_key_exists($id, $this->cache[$dt])) {
            return $this->cache[$dt][$id];
        }
        return 0;
    }
}

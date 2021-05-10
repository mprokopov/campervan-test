<?php

namespace App\Service;
use App\Entity\Equipment;
use App\Entity\Station;
use App\Entity\EquipmentBookedDate;
use App\Entity\EquipmentBookedDateCollection;
use App\Repository\EquipmentAvailabilityRepository;
use Symfony\Component\Serializer\Annotation\Ignore;

class GenerateEquipmentCalendarService
{
    private $station;
    private $collection;
    private $dateRange;
    private $initialEquipment;
    private $equipmentAvailabilityRepo;

    public function __construct(Station $station,
                                ?\DateTimeInterface $start,
                                \DateTimeInterface $end,
                                EquipmentAvailabilityRepository $equipmentAvailabilityRepo)
    {
        $this->station = $station;
        $this->collection = new EquipmentBookedDateCollection();
        $this->equipmentAvailabilityRepo = $equipmentAvailabilityRepo;

        $start ??= new \DateTimeImmutable();

        $interval = new \DateInterval('P1D');
        $this->dateRange = new \DatePeriod($start, $interval, $end);
        $this->initialEquipment = $station->getStationEquipment();
        $this->equipment = $station->getEquipmentAvailabilities();
    }

    /*
     * iterates over each day in dateRange and sets changed and availability value
     * uses StationEquipment initial values as basis
     */
    public function call(): void
    {
        $cache=$this->equipmentAvailabilityRepo->aggregatedCache($this->station);

        $this->collection->setInitialAmounts($this->station->getStationEquipment());

        foreach ($this->dateRange as $date) {
            foreach ($this->station->getStationEquipment() as $equipment) {
                $bookedDate = $this->collection->generateNextBookedDate($equipment);

                $change = 0;

                $id = $equipment->getEquipment()->getId();
                $dt = $date->format('Y-m-d');
                if (array_key_exists($dt, $cache) && array_key_exists($id, $cache[$dt])) {
                    $change = $cache[$dt][$id];
                }

                $bookedDate->setBooked($change);
                $this->collection->add($date, $bookedDate);
            }
        }
    }

    public function getCalendar()
    {
        return $this->collection;
    }

    public function getStation(): ?Station
    {
        return $this->station;
    }

    public function getDateRange(): \DatePeriod
    {
        return $this->dateRange;
    }
}

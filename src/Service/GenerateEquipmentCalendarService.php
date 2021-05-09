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
    /*
     * @Ignore()
     */
    private $collection2;
    private $dateRange;
    private $initialEquipment;
    private $equipmentAvailabilityRepo;

    public function __construct(Station $station,
                                ?\DateTimeInterface $start,
                                \DateTimeInterface $end,
                                EquipmentAvailabilityRepository $equipmentAvailabilityRepo)
    {
        $this->station = $station;
        $this->collection2 = new EquipmentBookedDateCollection();
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
        $initialEquipment = [];
        foreach ($this->initialEquipment as $equipment) {
            $initialEquipment[]= EquipmentBookedDate::fromStationEquipment($equipment);
        }
        $cache = $this->equipmentAvailabilityRepo->findAllByStationAggregated($this->station);

        foreach ($this->dateRange as $date) {
            foreach ($initialEquipment as &$equipment) {
                // N+1 implementation
                // $equipmentChange = $this->equipmentAvailabilityRepo->findOneByDateAndEquipment($date, $equipment->getEquipment(), $this->station);

                // avoid N+1 problem with cached table, iterate over using filter
                $filtered = array_filter($cache, fn($item) => $item[0]->getStation() == $this->station
                                      && $item[0]->getEquipment() == $equipment->getEquipment()
                                      && $item[0]->getBookingDate()->format('Y-m-d') == $date->format('Y-m-d')
                );
                if ($filtered) {
                    $equipmentChange = intval(reset($filtered)['bookingAggregate']); // bookingAggregate fetched as string
                    $equipment->updateByEquipmentAvailabilityChange($equipmentChange);
                }
                // reason for clone is that $equipment is mutable
                 $this->collection[$date->format('Y-m-d')][]= clone $equipment;
                 // refactor to use EquipmentBookedDateCollection
                 $this->collection2->add($date, clone $equipment);
            }
        }
    }

    public function getCalendar(): array
    {
        return $this->collection;
    }

    public function getCalendar2()
    {
        return $this->collection2;
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

<?php

namespace App\Service;
use App\Entity\Equipment;
use App\Entity\Station;
use App\Entity\EquipmentBookedDate;
use App\Entity\EquipmentBookedDateCollection;
use App\Repository\EquipmentAvailabilityRepository;
use Symfony\Component\Serializer\Annotation\Ignore;
use App\Service\AggregatetedEquipmentAmountCache;

class GenerateEquipmentCalendarService
{
    private EquipmentBookedDateCollection $collection;
    private Station $station;
    private \DatePeriod $dateRange;
    private EquipmentAvailabilityRepository $equipmentAvailabilityRepo;

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
    }

    /*
     * populates EquipmentBookedDateCollection with EquipmentBookedDate
     */
    public function call(): void
    {
        $cache = new AggregatetedEquipmentAmountCache($this->equipmentAvailabilityRepo);
        $cache->populate($this->station);

        $this->collection->setInitialAmounts($this->station->getStationEquipment());

        foreach ($this->dateRange as $date) {
            foreach ($this->station->getStationEquipment() as $stationEquipment) {
                $equipmentBookedDate = $this->collection->generateNextEquipmentBookedDate($stationEquipment);

                $amount = $cache->findByDateAndEquipment($date, $stationEquipment);

                $equipmentBookedDate->setBooked($amount);

                $this->collection->add($date, $equipmentBookedDate);
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

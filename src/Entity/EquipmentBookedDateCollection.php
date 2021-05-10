<?php
namespace App\Entity;

class EquipmentBookedDateCollection
{
    private array $collection;
    private array $amounts; // keeps tracking on amount

    public function __construct()
    {
        $this->collection = [];
        $this->amounts = [];
    }

    /*
     * adjusts aggregated amounts per equipment
     */
    public function add(\DateTimeInterface $date, EquipmentBookedDate $equipmentBookedDate)
    {
        $dt = $date->format('Y-m-d');
        $id = $equipmentBookedDate->getEquipment()->getId();
        $this->collection[$dt][$id] = $equipmentBookedDate;
        $this->amounts[$equipmentBookedDate->getEquipment()->getId()] += $equipmentBookedDate->getBooked();
    }

    public function getCollection(): array
    {
        return $this->collection;
    }

    public function setInitialAmounts($items)
    {
        foreach ($items as $item) {
            $this->amounts[$item->getEquipment()->getId()] = $item->getAmount();
        }
    }

    /*
     * returns Booked Equipment
     */
    public function generateNextEquipmentBookedDate($stationEquipment): EquipmentBookedDate
    {
        $equipment = $stationEquipment->getEquipment();
        $new = new EquipmentBookedDate();
        $new->setEquipment($equipment);
        $new->setAvailable($this->amounts[$equipment->getId()]);
        return $new;
    }

    public function getAmounts(): array
    {
        return $this->amounts;
    }

    public function findByDateAndEquipment($date, $equipment): ?EquipmentBookedDate
    {
        $dt = $date->format('Y-m-d');
        $id = $equipment->getId();
        try {
            $found = $this->collection[$dt][$id];
            return $found;
        } catch (\ErrorException $ex) {
            throw new \InvalidArgumentException("Item $id doesn't have initial stock at the station");
        }
    }
}

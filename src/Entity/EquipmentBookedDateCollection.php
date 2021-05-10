<?php
namespace App\Entity;

class EquipmentBookedDateCollection
{
    private array $dateIndex;
    private array $amounts; // keeps tracking on amount

    public function __construct()
    {
        $this->dateIndex = [];
    }

    /*
     * adjusts aggregated amounts per equipment
     */
    public function add(\DateTimeInterface $date, EquipmentBookedDate $equipmentChange)
    {
        $dt = $date->format('Y-m-d');
        $id =$equipmentChange->getEquipment()->getId();
        $this->dateIndex[$dt][$id] = $equipmentChange;
        $this->amounts[$equipmentChange->getEquipment()->getId()] += $equipmentChange->getBooked();
    }

    public function getCollection(): array
    {
        return $this->dateIndex;
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

    public function findByDateAndEquipment($date, $equipment): ?EquipmentBookedDate
    {
        $dt = $date->format('Y-m-d');
        $id = $equipment->getId();
        try {
            $found = $this->dateIndex[$dt][$id];
            return $found;
        } catch (\ErrorException $ex) {
            throw new \InvalidArgumentException("Item $id doesn't have initial stock at the station");
        }
    }
}

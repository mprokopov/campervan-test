<?php
namespace App\Entity;

class EquipmentBookedDateCollection
{
    /*
     * @Ignore()
     */
    private array $dateIndex;
    // private $equipmentIndex;

    public function __construct()
    {
        $this->dateIndex = [];
        // $this->equipmentIndex = [];
    }

    public function add(\DateTimeInterface $date, EquipmentBookedDate $equipmentChange)
    {
        $dt = $date->format('Y-m-d');
        $id =$equipmentChange->getEquipment()->getId();
        $this->dateIndex[$dt][$id] = $equipmentChange;
        // $this->equipmentIndex[$equipmentChange->getEquipment()->getId()] []= $dt;
    }

    public function getCollection()
    {
        return $this->dateIndex;
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

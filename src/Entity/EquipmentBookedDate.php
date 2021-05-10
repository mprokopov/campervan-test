<?php

namespace App\Entity;

use App\Entity\Equipment;
use Symfony\Component\Serializer\Annotation\Ignore;

class EquipmentBookedDate
{
    /*
     * @Ignore()
     */
    private Equipment $equipment;
    private int $available;
    private int $booked;
    private int $change;

    public function __construct()
    {
        $this->booked = 0;
        $this->change = 0;
        $this->available = 0;
    }

    public function setAvailable(int $amount)
    {
        $this->available = $amount;
    }

    public function setBooked(int $booked)
    {
        $this->booked = $booked;

        $this->available += $booked;
    }

    public function setEquipment(Equipment $equipment)
    {
        $this->equipment = $equipment;
    }

    public static function fromStationEquipment($equipment)
    {
        $new = new self;
        $new->setAvailable($equipment->getAmount());
        $new->setEquipment($equipment->getEquipment());
        return $new;
    }

    public function getBooked()
    {
        return $this->booked;
    }

    public function getChange()
    {
        return $this->change;
    }

    public function getAvailable()
    {
        return $this->available;
    }

    public function getEquipment()
    {
        return $this->equipment;
    }

    public function updateByEquipmentAvailabilityChange(int $equipmentChange)
    {
        $this->booked = $equipmentChange;
        $this->change += $equipmentChange;
        $this->available += $this->change;
    }

    public function isEnough(int $amount): bool
    {
        return $this->available > $amount;
    }
}

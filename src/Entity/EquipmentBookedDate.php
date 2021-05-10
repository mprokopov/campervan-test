<?php

namespace App\Entity;

use App\Entity\Equipment;
use App\Entity\StationEquipment;

class EquipmentBookedDate
{
    private Equipment $equipment;
    private int $available;
    private int $booked;

    public function __construct()
    {
        $this->booked = 0;
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

    public static function fromStationEquipment(StationEquipment $equipment): EquipmentBookedDate
    {
        $new = new self;
        $new->setAvailable($equipment->getAmount());
        $new->setEquipment($equipment->getEquipment());
        return $new;
    }

    public function getBooked(): int
    {
        return $this->booked;
    }

    public function getAvailable(): int
    {
        return $this->available;
    }

    public function getEquipment(): Equipment
    {
        return $this->equipment;
    }

    public function isEnough(int $amount): bool
    {
        return $this->available > $amount;
    }
}

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

    public function setAvailable(int $amount)
    {
        $this->available = $amount;
    }

    public function setBooked(int $booked)
    {
        $this->booked = $booked;
    }

    public function setEquipment(Equipment $equipment)
    {
        $this->equipment = $equipment;
    }

    public static function fromStationEquipment($equipment)
    {
        $new = new self;
        $new->setAvailable($equipment->getAmount());
        $new->setBooked(0);
        $new->setEquipment($equipment->getEquipment());
        return $new;
    }
    public function getBooked()
    {
        return $this->booked;
    }

    public function getAvailable()
    {
        return $this->available;
    }

    /*
     * @Ignore()
     */
    public function getEquipment()
    {
        return $this->equipment;
    }

    public function updateByEquipmentAvailabilityChange(int $equipmentChange)
    {
        $this->booked += $equipmentChange;
        $this->available += $this->booked;
    }

    public function isEnough(int $amount): bool
    {
        return $this->available > $amount;
    }
}

<?php

namespace App\Entity;

use App\Repository\EquipmentAvailabilityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EquipmentAvailabilityRepository::class)
 */
class EquipmentAvailability
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Station::class, inversedBy="equipmentAvailabilities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $station;

    /**
     * @ORM\ManyToOne(targetEntity=Equipment::class, inversedBy="equipmentAvailabilities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $equipment;

    /**
     * @ORM\Column(type="date")
     */
    private $bookingDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $bookingAmount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStation(): ?Station
    {
        return $this->station;
    }

    public function setStation(?Station $station): self
    {
        $this->station = $station;

        return $this;
    }

    public function getEquipment(): ?Equipment
    {
        return $this->equipment;
    }

    public function setEquipment(?Equipment $equipment): self
    {
        $this->equipment = $equipment;

        return $this;
    }

    public function getBookingDate(): ?\DateTimeInterface
    {
        return $this->bookingDate;
    }

    public function setBookingDate(\DateTimeInterface $bookingDate): self
    {
        $this->bookingDate = $bookingDate;

        return $this;
    }

    public function getBookingAmount(): ?int
    {
        return $this->bookingAmount;
    }

    public function setBookingAmount(int $bookingAmount): self
    {
        $this->bookingAmount = $bookingAmount;

        return $this;
    }
}

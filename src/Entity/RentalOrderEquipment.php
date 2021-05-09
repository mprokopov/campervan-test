<?php

namespace App\Entity;

use App\Repository\RentalOrderEquipmentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RentalOrderEquipmentRepository::class)
 */
class RentalOrderEquipment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=RentalOrder::class, inversedBy="rentalOrderEquipment")
     * @ORM\JoinColumn(nullable=false)
     */
    private $rentalOrder;

    /**
     * @ORM\ManyToOne(targetEntity=Equipment::class, inversedBy="rentalOrderEquipment")
     * @ORM\JoinColumn(nullable=false)
     */
    private $equipment;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRentalOrder(): ?RentalOrder
    {
        return $this->rentalOrder;
    }

    public function setRentalOrder(?RentalOrder $rentalOrder): self
    {
        $this->rentalOrder = $rentalOrder;

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

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\RentalOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RentalOrderRepository::class)
 */
class RentalOrder
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Campervan::class, inversedBy="rentalOrders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campervan;

    /**
     * @ORM\ManyToOne(targetEntity=Station::class, inversedBy="rentalOrders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $startStation;

    /**
     * @ORM\ManyToOne(targetEntity=Station::class, inversedBy="rentalOrders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $endStation;

    /**
     * @ORM\Column(type="date")
     */
    private $startDate;

    /**
     * @ORM\Column(type="date")
     */
    private $endDate;

    /**
     * @ORM\OneToMany(targetEntity=RentalOrderEquipment::class, mappedBy="rentalOrder")
     */
    private $rentalOrderEquipment;

    public function __construct()
    {
        $this->rentalOrderEquipment = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCampervan(): ?Campervan
    {
        return $this->campervan;
    }

    public function setCampervan(?Campervan $campervan): self
    {
        $this->campervan = $campervan;

        return $this;
    }

    // public function getStartStation(): ?Station
    // {
    //     return $this->startStation;
    // }

    public function setStartStation(?Station $startStation): self
    {
        $this->startStation = $startStation;

        return $this;
    }

    // public function getEndStation(): ?Station
    // {
    //     return $this->endStation;
    // }

    public function setEndStation(?Station $endStation): self
    {
        $this->endStation = $endStation;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return Collection|RentalOrderEquipment[]
     */
    public function getRentalOrderEquipment(): Collection
    {
        return $this->rentalOrderEquipment;
    }

    public function addRentalOrderEquipment(RentalOrderEquipment $rentalOrderEquipment): self
    {
        if (!$this->rentalOrderEquipment->contains($rentalOrderEquipment)) {
            $this->rentalOrderEquipment[] = $rentalOrderEquipment;
            $rentalOrderEquipment->setRentalOrder($this);
        }

        return $this;
    }

    public function removeRentalOrderEquipment(RentalOrderEquipment $rentalOrderEquipment): self
    {
        if ($this->rentalOrderEquipment->removeElement($rentalOrderEquipment)) {
            // set the owning side to null (unless already changed)
            if ($rentalOrderEquipment->getRentalOrder() === $this) {
                $rentalOrderEquipment->setRentalOrder(null);
            }
        }

        return $this;
    }
}

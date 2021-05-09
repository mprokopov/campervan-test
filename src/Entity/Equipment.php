<?php

namespace App\Entity;

use App\Repository\EquipmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * @ORM\Entity(repositoryClass=EquipmentRepository::class)
 */
class Equipment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Ignore()
     * @ORM\OneToMany(targetEntity=RentalOrderEquipment::class, mappedBy="equipment")
     */
    private $rentalOrderEquipment;

    /**
     * @Ignore()
     * @ORM\OneToMany(targetEntity=StationEquipment::class, mappedBy="equipment")
     */
    private $stationEquipment;

    /**
     * @Ignore()
     * @ORM\OneToMany(targetEntity=EquipmentAvailability::class, mappedBy="equipment")
     */
    private $equipmentAvailabilities;

    public function __construct()
    {
        $this->rentalOrderEquipment = new ArrayCollection();
        $this->stationEquipment = new ArrayCollection();
        $this->equipmentAvailabilities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @Ignore()
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
            $rentalOrderEquipment->setEquipment($this);
        }

        return $this;
    }

    public function removeRentalOrderEquipment(RentalOrderEquipment $rentalOrderEquipment): self
    {
        if ($this->rentalOrderEquipment->removeElement($rentalOrderEquipment)) {
            // set the owning side to null (unless already changed)
            if ($rentalOrderEquipment->getEquipment() === $this) {
                $rentalOrderEquipment->setEquipment(null);
            }
        }

        return $this;
    }

    /**
     * @Ignore()
     * @return Collection|StationEquipment[]
     */
    public function getStationEquipment(): Collection
    {
        return $this->stationEquipment;
    }

    public function addStationEquipment(StationEquipment $stationEquipment): self
    {
        if (!$this->stationEquipment->contains($stationEquipment)) {
            $this->stationEquipment[] = $stationEquipment;
            $stationEquipment->setEquipment($this);
        }

        return $this;
    }

    public function removeStationEquipment(StationEquipment $stationEquipment): self
    {
        if ($this->stationEquipment->removeElement($stationEquipment)) {
            // set the owning side to null (unless already changed)
            if ($stationEquipment->getEquipment() === $this) {
                $stationEquipment->setEquipment(null);
            }
        }

        return $this;
    }

    /**
     * @Ignore()
     * @return Collection|EquipmentAvailability[]
     */
    public function getEquipmentAvailabilities(): Collection
    {
        return $this->equipmentAvailabilities;
    }

    public function addEquipmentAvailability(EquipmentAvailability $equipmentAvailability): self
    {
        if (!$this->equipmentAvailabilities->contains($equipmentAvailability)) {
            $this->equipmentAvailabilities[] = $equipmentAvailability;
            $equipmentAvailability->setEquipment($this);
        }

        return $this;
    }

    public function removeEquipmentAvailability(EquipmentAvailability $equipmentAvailability): self
    {
        if ($this->equipmentAvailabilities->removeElement($equipmentAvailability)) {
            // set the owning side to null (unless already changed)
            if ($equipmentAvailability->getEquipment() === $this) {
                $equipmentAvailability->setEquipment(null);
            }
        }

        return $this;
    }
}

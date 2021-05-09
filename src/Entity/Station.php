<?php

namespace App\Entity;

use App\Repository\StationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * @ORM\Entity(repositoryClass=StationRepository::class)
 */
class Station
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
    private $location;

    /**
     * @ORM\OneToMany(targetEntity=StationEquipment::class, mappedBy="station")
     */
    private $stationEquipment;

    /**
     * @Ignore()
     * @ORM\OneToMany(targetEntity=EquipmentAvailability::class, mappedBy="station")
     */
    private $equipmentAvailabilities;

    public function __construct()
    {
        $this->stationEquipment = new ArrayCollection();
        $this->equipmentAvailabilities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
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
            $stationEquipment->setStation($this);
        }

        return $this;
    }

    public function removeStationEquipment(StationEquipment $stationEquipment): self
    {
        if ($this->stationEquipment->removeElement($stationEquipment)) {
            // set the owning side to null (unless already changed)
            if ($stationEquipment->getStation() === $this) {
                $stationEquipment->setStation(null);
            }
        }

        return $this;
    }

    /**
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
            $equipmentAvailability->setStation($this);
        }

        return $this;
    }

    public function removeEquipmentAvailability(EquipmentAvailability $equipmentAvailability): self
    {
        if ($this->equipmentAvailabilities->removeElement($equipmentAvailability)) {
            // set the owning side to null (unless already changed)
            if ($equipmentAvailability->getStation() === $this) {
                $equipmentAvailability->setStation(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\CampervanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CampervanRepository::class)
 */
class Campervan
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=RentalOrder::class, mappedBy="campervan")
     */
    private $rentalOrders;

    public function __construct()
    {
        $this->rentalOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|RentalOrder[]
     */
    public function getRentalOrders(): Collection
    {
        return $this->rentalOrders;
    }

    public function addRentalOrder(RentalOrder $rentalOrder): self
    {
        if (!$this->rentalOrders->contains($rentalOrder)) {
            $this->rentalOrders[] = $rentalOrder;
            $rentalOrder->setCampervan($this);
        }

        return $this;
    }

    public function removeRentalOrder(RentalOrder $rentalOrder): self
    {
        if ($this->rentalOrders->removeElement($rentalOrder)) {
            // set the owning side to null (unless already changed)
            if ($rentalOrder->getCampervan() === $this) {
                $rentalOrder->setCampervan(null);
            }
        }

        return $this;
    }
}

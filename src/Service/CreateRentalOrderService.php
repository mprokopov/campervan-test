<?php

namespace App\Service;

use App\Repository\EquipmentAvailabilityRepository;
use App\Repository\RentalOrderEquipmentRepository;
use App\Repository\StationEquipmentRepository;
use App\Repository\StationRepository;
use App\Repository\CampervanRepository;
use App\Repository\EquipmentRepository;
use App\Repository\RentalOrderRepository;
use App\Entity\RentalOrder;
use App\Entity\RentalOrderEquipment;
use App\Entity\Campervan;
use App\Entity\Equipment;
use App\Entity\Station;
use App\Entity\EquipmentAvailability;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\GenerateEquipmentCalendarService;

class CreateRentalOrderService {
    private $rentalOrderRepo;
    private $rentalOrderEquip;
    private $eqipmentAvailability;
    private $rentalOrder;
    private $eqipmentAvailabilityRepo;
    private $entityManager;
    private $campervanRepo;
    private $equipmentRepo;
    private $stationRepo;

    public function __construct(RentalOrderRepository $rentalOrderRepo,
                                RentalOrderEquipmentRepository $rentalOrderEqRepo,
                                EquipmentAvailabilityRepository $equipmentAvailabilityRepo,
                                CampervanRepository $campervanRepo,
                                StationRepository $stationRepo,
                                EquipmentRepository $equipmentRepo,
                                EntityManagerInterface $entityManager
    ) {
        $this->rentalOrderRepo = $rentalOrderRepo;
        $this->rentalOrderEqip = $rentalOrderEqRepo;
        $this->equipmentAvailabilityRepo = $equipmentAvailabilityRepo;
        $this->campervanRepo = $campervanRepo;
        $this->stationRepo = $stationRepo;
        $this->entityManager = $entityManager;
        $this->equipmentRepo = $equipmentRepo;
        $this->rentalOrder = new RentalOrder();
    }

    private function createEquipmentAvailability(Equipment $equipment,
                                                 Station $station,
                                                 \DateTimeImmutable $date,
                                                 int $deltaAmount) {
        $equipmentAvailability = new EquipmentAvailability();

        $equipmentAvailability->setBookingAmount($deltaAmount);
        $equipmentAvailability->setEquipment($equipment);
        $equipmentAvailability->setStation($station);
        $equipmentAvailability->setBookingDate($date);

        $this->entityManager->persist($equipmentAvailability);
        return $equipmentAvailability;
    }

    private function createOrderEquipment(Equipment $equipment,
                                          int $amount)
    {

        $orderEquipment = new RentalOrderEquipment();
        $orderEquipment->setAmount($amount);
        $orderEquipment->setEquipment($equipment);
        $orderEquipment->setRentalOrder($this->rentalOrder);

        $this->entityManager->persist($orderEquipment);
        return $orderEquipment;
    }

    public function call(int $campervanId,
                         int $startStationId,
                         int $endStationId,
                         \DateTimeImmutable $startDate,
                         \DateTimeImmutable $endDate,
                         array $orderEquipment
    ) {
        $camperVan = $this->campervanRepo->find($campervanId);

        if (! $camperVan) {
            throw new \InvalidArgumentException("Campervan $campervanId doesn't exist");
        }

        $startStation = $this->stationRepo->find($startStationId);

        if (! $startStation) {
            throw new \InvalidArgumentException("Station $startStationId doesn't exist");
        }

        $endStation = $this->stationRepo->find($endStationId);

        if (! $endStation) {
            throw new \InvalidArgumentException("Station $endStationId doesn't exist");
        }

        $this->rentalOrder->setCampervan($camperVan);
        $this->rentalOrder->setStartDate($startDate);
        $this->rentalOrder->setEndDate($endDate);
        $this->rentalOrder->setStartStation($startStation);
        $this->rentalOrder->setEndStation($endStation);

        foreach($orderEquipment as $orderEquipmentItem) {
            $equipment = $this->equipmentRepo->find($orderEquipmentItem['equipment_id']);
            $amount = $orderEquipmentItem['amount'];

            /* availability check */
            $calendar = new GenerateEquipmentCalendarService($startStation, null, $endDate, $this->equipmentAvailabilityRepo);
            $calendar->call();

            $availability = $calendar->getCalendar2();

            if(! $availability->findByDateAndEquipment($startDate, $equipment)->isEnough($amount)){
                throw new \InvalidArgumentException("Not enough estimated equipment on the required date");
            }

            $this->createEquipmentAvailability($equipment, $startStation, $startDate, -$amount);
            $this->createEquipmentAvailability($equipment, $endStation, $endDate, $amount);
            $this->createOrderEquipment($equipment, $amount);
        }

        $this->entityManager->persist($this->rentalOrder);
        $this->entityManager->flush();

        return $this->rentalOrder;
    }
}

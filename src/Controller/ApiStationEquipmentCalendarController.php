<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\GenerateEquipmentCalendarService;
use App\Repository\EquipmentAvailabilityRepository;
use App\Repository\StationRepository;

class ApiStationEquipmentCalendarController extends AbstractController
{
    /**
     * @Route("/api/station/equipment/calendar", name="api_station_equipment_calendar", methods="GET")
     */
    public function index(EquipmentAvailabilityRepository $availabilityRepo, StationRepository $stationRepo): Response
    {
        $stations = $stationRepo->findAll();

        $end = (new \DateTime())->add(new \DateInterval('P14D'));
        $calendar = [];

        foreach ($stations as $station) {
            $stationCalendar = new GenerateEquipmentCalendarService($station, null, $end, $availabilityRepo);
            $stationCalendar->call();
            $calendar[$station->getLocation()] = $stationCalendar->getCalendar();
        }

        // var_dump($calendar['Munich']->getCollection()['2021-05-09']);

        // Issue https://github.com/symfony/symfony/issues/37334 still unresolved
        // The problem can be solved by creating normalizers for your entities.
        // TODO: create normalizer for the Equipment class
        return $this->json($calendar);
    }
}

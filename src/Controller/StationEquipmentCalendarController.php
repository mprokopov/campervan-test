<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EquipmentAvailabilityRepository;
use App\Repository\StationRepository;
use App\Service\GenerateEquipmentCalendarService;

class StationEquipmentCalendarController extends AbstractController
{
    /**
     * @Route("/station/equipment/calendar", name="station_equipment_calendar")
     */
    public function index(EquipmentAvailabilityRepository $availabilityRepo, StationRepository $stationRepo): Response
    {
        $stations = $stationRepo->findAll();

        $calendars = [];
        foreach($stations as $station) {
            $end = (new \DateTime())->add(new \DateInterval('P14D'));
            $calendar = new GenerateEquipmentCalendarService($station, null, $end, $availabilityRepo);
            $calendar->call();
            $calendars []= clone $calendar;
            // var_dump($calendar->getCalendar2());
        }

        return $this->render('station_equipment_calendar/index.html.twig', [
            'controller_name' => 'StationEquipmentCalendarController',
            'calendars' => $calendars
        ]);
    }
}

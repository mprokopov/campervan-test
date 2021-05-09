<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\CreateRentalOrderService;

class ApiRentalOrderController extends AbstractController
{
    /**
     * @Route("/api/rental/order", name="api_rental_order", methods="POST")
     */
    public function index(EntityManagerInterface $entityManager, CreateRentalOrderService $rentalOrder): Response
    {
        $request = Request::createFromGlobals();
        $data = $request->toArray();

        ['campervan_id' => $campervanId,
         'start_station_id' => $startStationId,
         'end_station_id' => $endStationId,
         'start_date' => $startDate,
         'end_date' => $endDate,
         'equipment' => $equipment
        ] = $data;

        try {
            $rentalOrder->call($campervanId,
                               $startStationId,
                               $endStationId,
                               new \DateTimeImmutable($startDate),
                               new \DateTimeImmutable($endDate),
                               $equipment
            );
        } catch (\InvalidArgumentException $e) {
            $response = new Response($e->getMessage(), Response::HTTP_BAD_REQUEST, ['content-type' => 'text/html']);
            return $response;
        };

        $response = new Response('', Response::HTTP_CREATED, ['content-type' => 'text/html']);

        return $response;
    }
}

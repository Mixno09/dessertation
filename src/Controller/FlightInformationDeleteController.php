<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\FlightInformationRepository;
use App\Service\DeleteFlightInformationCommand;
use App\Service\FlightInformationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FlightInformationDeleteController extends AbstractController
{
    private FlightInformationService $flightInformationService;
    private FlightInformationRepository $repository;

    public function __construct(FlightInformationService $flightInformationService, FlightInformationRepository $repository)
    {
        $this->flightInformationService = $flightInformationService;
        $this->repository = $repository;
    }

    /**
     * @Route("/flight_informations/{slug}/delete", name="flight_information_delete", methods={"GET","POST"})
     */
    public function delete(string $slug, Request $request): Response
    {
        $flightInformation = $this->repository->findBySlug($slug);
        if ($flightInformation === null) {
            throw $this->createNotFoundException('Записи с параметрами ' . $slug . ' не существует');
        }

        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $command = new DeleteFlightInformationCommand(
                $flightInformation->getFlightInformationId()->getAirplaneNumber(),
                $flightInformation->getFlightInformationId()->getFlightDate(),
                $flightInformation->getFlightInformationId()->getFlightNumber()
            );
            $this->flightInformationService->delete($command);
            return $this->redirectToRoute('main');
        }

        return $this->render('index/delete.html.twig', [
            'form' => $form->createView(),
            'flightInformation' => $flightInformation,
        ]);
    }
}
<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\FlightInformation;
use App\Repository\FlightInformationRepository;
use App\UseCase\Command\DeleteFlightInformationCommand;
use App\UseCase\Command\DeleteFlightInformationHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FlightInformationDeleteController extends AbstractController
{
    private DeleteFlightInformationHandler $handler;
    private FlightInformationRepository $repository;

    public function __construct(DeleteFlightInformationHandler $handler, FlightInformationRepository $repository)
    {
        $this->handler = $handler;
        $this->repository = $repository;
    }

    /**
     * @Route("/flight_informations/{slug}/delete", name="flight_information_delete", methods={"GET","POST"})
     */
    public function delete(string $slug, Request $request): Response
    {
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $command = new DeleteFlightInformationCommand();
            $command->slug = $slug;
            $this->handler->handle($command);
            return $this->redirectToRoute('main');
        }
        $flightInformation = $this->repository->findBySlug($slug);
        if (! $flightInformation instanceof FlightInformation) {
            throw $this->createNotFoundException(
                'Данных борта ' . $flightInformation->getId()->getAirplane() . ' с вылетом ' . $flightInformation->getId()->getDeparture() . ' не существует');
        }

        return $this->render('index/delete.html.twig', [
            'form' => $form->createView(),
            'flightInformation' => $flightInformation,
        ]);
    }
}
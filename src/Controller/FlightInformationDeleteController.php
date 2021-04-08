<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\FlightInformation\FlightInformation;
use App\Fetcher\FlightInformationFetcher;
use App\UseCase\Command\DeleteFlightInformationCommand;
use App\UseCase\Command\DeleteFlightInformationHandler;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FlightInformationDeleteController extends AbstractController
{
    private DeleteFlightInformationHandler $handler;
    private FlightInformationFetcher $fetcher;

    public function __construct(DeleteFlightInformationHandler $handler, FlightInformationFetcher $fetcher)
    {
        $this->handler = $handler;
        $this->fetcher = $fetcher;
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
            $this->handler->handle($command); //todo как отправить данные с формы методом post
            return $this->redirectToRoute('main');
        }
        try {
            /** @var FlightInformation $flightInformation */
            $flightInformation = $this->fetcher->findBySlug($slug);
        } catch (Exception $e) {
            throw $this->createNotFoundException('Записи с параметрами ' . $slug . ' не существует'); //todo это норм?
        }

        return $this->render('index/delete.html.twig', [
            'form' => $form->createView(),
            'flightInformation' => $flightInformation,
        ]);
    }
}
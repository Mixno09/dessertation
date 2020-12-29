<?php

declare(strict_types=1);

namespace App\Controller;

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
     * @Route("/flight_information/{slug}/delete", name="flight_information_delete", methods={"GET","POST"})
     */
    public function delete(string $slug, Request $request): Response
    {
        $command = new DeleteFlightInformationCommand();

        $queryBuilder = $this->connection->createQueryBuilder();
        $query = $queryBuilder
            ->select('*')
            ->from('departures')
            ->where('id = ' . $queryBuilder->createPositionalParameter($slug));
        $statement = $query->execute();
        $departure = $statement->fetch();
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $queryBuilder = $this->connection->createQueryBuilder();
            $query = $queryBuilder
                ->delete('departures')
                ->where('id = ' . $queryBuilder->createPositionalParameter($slug));
            $query->execute();
            return $this->redirectToRoute('main');
        }
        return $this->render('index/delete.html.twig', [
            'form' => $form->createView(),
            'departures' => $departure,
        ]);

    }
}
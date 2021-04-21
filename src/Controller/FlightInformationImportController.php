<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ImportFlightInformationDto;
use App\Form\ImportFlightInformationType;
use App\Service\FlightInformationImportXlsParser;
use App\Service\FlightInformationService;
use App\Service\CreateFlightInformationCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FlightInformationImportController extends AbstractController
{
    private FlightInformationService $flightInformationService;
    private FlightInformationImportXlsParser $parser;

    public function __construct(FlightInformationService $flightInformationService, FlightInformationImportXlsParser $parser)
    {
        $this->flightInformationService = $flightInformationService;
        $this->parser = $parser;
    }

    /**
     * @Route("/flight-informations/import", name="flight_information_import", methods={"GET","POST"})
     */
    public function __invoke(Request $request): Response
    {
        $importFlightInformationDto = new ImportFlightInformationDto();
        $form = $this->createForm(ImportFlightInformationType::class, $importFlightInformationDto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->parser->parse($importFlightInformationDto->file);
            $command = new CreateFlightInformationCommand(
                $importFlightInformationDto->airplaneNumber,
                $importFlightInformationDto->flightDate,
                $importFlightInformationDto->flightNumber,
                $result->getTime(),
                $result->getT4Right(),
                $result->getT4Left(),
                $result->getAlfaRudLeft(),
                $result->getAlfaRudRight(),
                $result->getRndLeft(),
                $result->getRvdLeft(),
                $result->getRndRight(),
                $result->getRvdRight()
            );
            $this->flightInformationService->create($command);
            return $this->redirectToRoute('main');
        }
        return $this->render('import/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
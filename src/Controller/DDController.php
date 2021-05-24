<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\FlightInformationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DDController extends AbstractController
{
    private FlightInformationRepository $repository;

    public function __construct(FlightInformationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/flight-informations/{slug}/dd-chart", name="flight_information_dd", methods="GET")
     */
    public function __invoke(string $slug): Response
    {
        $flightInformation = $this->repository->findFlightInformationBySlug($slug);
        $flightInformationEnquiry = $this->repository->findFlightInformationBySlug('31_2016-01-01_15');

        if ($flightInformation === null) {
            throw $this->createNotFoundException();
        }
        $calcEngineValue = $flightInformation->getLeftEngineParameters()->calcParameter();
        $calcEngineValueEnquiry = $flightInformationEnquiry->getLeftEngineParameters()->calcParameter();

        $rows = $this->repository->findLeftMutualParameterWithEngineParameterBySlug($slug);
        $data = array_map(
            static fn($row) => [
                'ddT4Rnd' => $row[0]->getDistributionDensityT4Rnd(),
                'ddRndRvd' => $row[0]->getDistributionDensityT4Rnd(),
                'ddT4Rvd' => $row[0]->getDistributionDensityT4Rvd(),
                't4' => (float) $row['t4'],
                'rnd' => (float) $row['rnd'],
                'rvd' => (float) $row['rvd'],
            ],
            $rows
        );
        $rowsEnquiry = $this->repository->findLeftMutualParameterWithEngineParameterBySlug('31_2016-01-01_15');
        $dataEnquiry = array_map(
            static fn($rowEnquiry) => [
                'ddT4Rnd' => $rowEnquiry[0]->getDistributionDensityT4Rnd(),
                'ddRndRvd' => $rowEnquiry[0]->getDistributionDensityT4Rnd(),
                'ddT4Rvd' => $rowEnquiry[0]->getDistributionDensityT4Rvd(),
                't4' => (float) $rowEnquiry['t4'],
                'rnd' => (float) $rowEnquiry['rnd'],
                'rvd' => (float) $rowEnquiry['rvd'],
            ],
            $rowsEnquiry
        );
        return $this->render('chart/dd.html.twig', [
            'dataCurrent' => $data,
            'dataEnquiry' => $dataEnquiry,
            'calcEngineValueCurrent' => $calcEngineValue,
            'calcEngineValueEnquiry' => $calcEngineValueEnquiry,
        ]);
    }
}

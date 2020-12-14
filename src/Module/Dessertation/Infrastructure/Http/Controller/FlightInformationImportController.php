<?php

declare(strict_types=1);

namespace App\Module\Dessertation\Infrastructure\Http\Controller;

use App\Module\Dessertation\Application\Command\ImportFlightInformationFromXlsCommand;
use App\Module\Dessertation\Application\Command\ImportFlightInformationFromXlsHandler;
use App\Module\Dessertation\Application\Form\ImportFlightInformationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FlightInformationImportController extends AbstractController
{
    private ImportFlightInformationFromXlsHandler $handler;

    public function __construct(ImportFlightInformationFromXlsHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @Route("/test", name="test", methods={"GET","POST"})
     */
    public function __invoke(Request $request): Response
    {
        $command = new ImportFlightInformationFromXlsCommand();
        $form = $this->createForm(ImportFlightInformationType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->handler->handle($command);
            return $this->redirectToRoute('main');
        }
        return $this->render('import/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
<?php

namespace App\Controller;

use App\Action\Command;
use App\Action\HandleRequest;
use App\Form\ImportFlightType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImportController extends AbstractController
{
    private HandleRequest $handleRequest;

    public function __construct(HandleRequest $handleRequest)
    {
        $this->handleRequest = $handleRequest;
    }

    /**
     * @Route("/import", name="import", methods={"GET","POST"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request): Response
    {
        $command = new Command();
        $form = $this->createForm(ImportFlightType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleRequest->handle($command);
            return $this->redirectToRoute('main');
        }
        return $this->render('import/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

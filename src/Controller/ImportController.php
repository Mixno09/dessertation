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
    /**
     * @var HandleRequest
     */
    private $handler;

    /**
     * LoadFlightController constructor.
     * @param $handler
     */
    public function __construct(HandleRequest $handler)
    {
        $this->handler = $handler;
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
            $this->handler->handle($command);
            return $this->redirectToRoute('import');
        }
        return $this->render('import/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

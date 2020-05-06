<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LoadFlightController extends AbstractController
{
    /**
     * @Route("/load/flyght", name="load_flight")
     */
    public function index()
    {
        return $this->render('load_flight/index.html.twig');
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ChartController extends AbstractController
{
    /**
     * @Route("/file/chart", name="chart")
     */
    public function index()
    {
        return $this->render('chart/index.html.twig');
    }
}

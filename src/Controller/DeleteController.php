<?php

declare(strict_types=1);

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DeleteController extends AbstractController
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;

    /**
     * DeleteController constructor.
     * @param \Doctrine\DBAL\Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"GET","POST"})
     * @param int $id
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(int $id, Request $request): Response
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $query = $queryBuilder
            ->select('*')
            ->from('departures')
            ->where('id = ' . $queryBuilder->createPositionalParameter($id));
        $statement = $query->execute();
        $departure = $statement->fetch();
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $queryBuilder = $this->connection->createQueryBuilder();
            $query = $queryBuilder
                ->delete('departures')
                ->where('id = ' . $queryBuilder->createPositionalParameter($id));
            $query->execute();
            return $this->redirectToRoute('index');
        }
        return $this->render('index/delete.html.twig', [
            'form' => $form->createView(),
            'departures' => $departure,
        ]);

    }
}
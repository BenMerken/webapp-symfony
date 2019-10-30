<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CustodianController extends AbstractController
{
    /**
     * @Route("/custodian", methods={"GET"}, name="custodian_dashboard")
     */
    public function index()
    {
        return $this->render('custodian/index.html.twig', [
            'controller_name' => 'CustodianController',
        ]);
    }
}

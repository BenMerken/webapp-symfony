<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdministratorController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index()
    {
        return $this->render('administrator/index.html.twig', [
            'controller_name' => 'AdministratorController',
        ]);
    }
}

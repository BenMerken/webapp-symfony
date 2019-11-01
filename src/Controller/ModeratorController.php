<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ModeratorController extends AbstractController
{
    /**
     * @Route("/moderator", name="moderator_dashboard")
     */
    public function index()
    {
        return $this->render('moderator/index.html.twig', [
            'controller_name' => 'ModeratorController',
        ]);
    }
}

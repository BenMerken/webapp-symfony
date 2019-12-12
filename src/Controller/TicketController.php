<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{
    /**
     * @Route("/register_ticket", name="register_ticket")
     */
    public function registerTicket()
    {
        return $this->render('ticket/ticket_form.html.twig', [
        ]);
    }
}

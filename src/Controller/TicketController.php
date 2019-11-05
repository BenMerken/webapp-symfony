<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{
    /**
     * @Route("/ticket", name="ticket-form")
     */
    public function ticketForm()
    {
        return $this->render('ticket/ticket-form.html.twig', [
        ]);
    }
}

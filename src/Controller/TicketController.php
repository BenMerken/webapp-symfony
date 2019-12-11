<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{
    /**
     * @Route("/ticket", name="ticket_form")
     */
    public function ticketForm()
    {
        return $this->render('ticket/ticket_form.html.twig', [
        ]);
    }
}

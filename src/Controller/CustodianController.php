<?php

namespace App\Controller;

use App\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CustodianController extends AbstractController
{
    /**
     * @Route("/custodian", name="custodian_dashboard")
     */
    public function index()
    {
        $tickets = $this->getDoctrine()->getRepository(Ticket::class)->findAllDescendingByNumberOfVotes();

        return $this->render('custodian/index.html.twig', [
            'tickets' => $tickets
        ]);
    }

    /**
     * @Route("/custodian/delete/{ticketId}", name="delete_ticket")
    */
    public function deleteTicket(Request $request, $ticketId)
    {

    }
}

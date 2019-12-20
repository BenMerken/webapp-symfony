<?php

namespace App\Controller;

use App\Entity\Ticket;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 *@IsGranted("ROLE_CUSTODIAN")
 */
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
        $ticketRepository = $this->getDoctrine()->getRepository(Ticket::class);
        $ticket = $ticketRepository->find($ticketId);
        if ($ticket) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ticket);
            $entityManager->flush();
            $this->addFlash('success', 'Ticket successfully deleted.');
        }

        return $this->redirectToRoute('custodian_dashboard');
    }

    /**
     * @Route("/custodian/upvote/{ticketId}", name="upvote_ticket")
     */
    public function upvoteTicket(Request $request, $ticketId)
    {
        $ticketRepository = $this->getDoctrine()->getRepository(Ticket::class);
        $ticket = $ticketRepository->find($ticketId);
        if ($ticket) {
            $entityManager = $this->getDoctrine()->getManager();
            $ticket->setNumberOfVotes($ticket->getNumberOfVotes() + 1);
            $entityManager->flush();
            $this->addFlash('success', 'Ticket successfully upvoted.');
        }

        return $this->redirectToRoute('custodian_dashboard');
    }
}

<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Service\ComplaintService;
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
    public function index(ComplaintService $complaintService)
    {
        $tickets = $this->getDoctrine()->getRepository(Ticket::class)->findAllDescendingByNumberOfVotes();
        $complaints = $complaintService
            ->getComplaintsForUserEmail($this->get('security.token_storage')->getToken()->getUser()->getEmail());

        return $this->render('custodian/index.html.twig', [
            'tickets' => $tickets,
            'complaints' => $complaints
        ]);
    }

    /**
     * @Route("/custodian/delete/{ticketId}", name="delete_ticket")
     */
    public function deleteTicket($ticketId)
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
    public function upvoteTicket($ticketId)
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

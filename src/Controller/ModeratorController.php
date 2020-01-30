<?php

namespace App\Controller;

use App\Entity\Complaint;
use App\Form\ComplaintRegistrationFormType;
use App\Service\ComplaintService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_MOD")
 */
class ModeratorController extends AbstractController
{
    /**
     * @Route("/moderator", name="moderator_dashboard")
     */
    public function index(ComplaintService $complaintService)
    {
        $complaints = $complaintService
            ->getComplaintsForUserEmail($this->get('security.token_storage')->getToken()->getUser()->getEmail());

        return $this->render('moderator/index.html.twig', [
            'controller_name' => 'ModeratorController',
            'complaints' => $complaints
        ]);
    }

    /**
     * @Route("moderator/register_complaint", name="register_complaint")
     */
    public function registerComplaint(Request $request)
    {
        $complaint = new Complaint();
        $form = $this->createForm(ComplaintRegistrationFormType::class, $complaint);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->get('user')->getData();

            $complaint->setUser($user);
            $complaint->setReason($form->get('reason')->getData());

            $entitiyManager = $this->getDoctrine()
                ->getManager();

            $entitiyManager->persist($complaint);
            $entitiyManager->flush();

            $message = 'Your complaint for user ' . $user->getEmail() . ' was successfully registered.';
            $this->addFlash('success', $message);

            return $this->redirectToRoute('moderator_dashboard', [
                'created_complaint' => $complaint,
            ]);
        }

        return $this->render('moderator/register_complaint.html.twig', [
            'register_form' => $form->createView(),
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Complaint;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\ComplaintService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class AdministratorController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(Request $request, ComplaintService $complaintService)
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $moderators = $userRepository->findByUserRole('ROLE_MOD');
        $custodians = $userRepository->findByUserRole('ROLE_CUSTODIAN');
        $personalComplaints = $complaintService->getComplaintsForUserEmail($this->get('security.token_storage')->getToken()->getUser()->getEmail());
        $allComplaints = $this->getDoctrine()->getRepository(Complaint::class)->findAll();

        return $this->render('administrator/index.html.twig', [
            'moderators' => $moderators,
            'custodians' => $custodians,
            'created_user' => $request->query->get('created_user'),
            'personal_complaints' => $personalComplaints,
            'all_complaints' => $allComplaints
        ]);
    }

    /**
     * @Route("/admin/register_user", name="register_user")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'User ' . $form->get('email')->getData() . ' created.');
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plaintextPassword')->getData()
                )
            );
            $user->setRoles($form->get('roles')->getData());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admin_dashboard', [
                'created_user' => $user->getEmail()
            ]);
        }

        return $this->render('administrator/register_user.html.twig', [
            'register_form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/delete/user/{userId}", name="delete_user")
     */
    public function deleteUser($userId)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        if ($user) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'User ' . $user->getEmail() . ' successfully deleted.');
        }

        return $this->redirectToRoute('admin_dashboard');
    }

    /**
     * @Route("/admin/delete/complaint/{complaintId}", name="delete_complaint")
     */
    public function deleteComplaint($complaintId)
    {
        $complaint = $this->getDoctrine()->getRepository(Complaint::class)->find($complaintId);
        if ($complaint) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($complaint);
            $entityManager->flush();
            $this->addFlash('success',
                'Complaint against user ' . $complaint->getUser()->getEmail() . ' successfully deleted.');
        }
    }
}

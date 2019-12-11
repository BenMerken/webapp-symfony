<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdministratorController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(Request $request)
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $moderators = $userRepository->findByUserRole('ROLE_MOD');
        $custodians = $userRepository->findByUserRole('ROLE_CUSTODIAN');

        return $this->render('administrator/index.html.twig', [
            'moderators' => $moderators,
            'custodians' => $custodians,
            'created_user' => $request->query->get('created_user'),
        ]);
    }

    /**
     * @Route("/admin/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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

            return $this->redirectToRoute('admin_dashboard', ['created_user' => $user->getEmail()]);
        }

        return $this->render('administrator/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/delete/{userId}", name="delete_user")
     */
    public function deleteUser($userId)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        if ($user) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_dashboard');
    }
}

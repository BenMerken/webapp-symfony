<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdministratorController extends AbstractController
{


    public function __construct()
    {

    }

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
            'userId' => $request->request->get('userId'),
        ]);
    }

    /**
     * @Route("/admin/create_user", name="create_user")
     */
    public function createUser()
    {
        return $this->render('administrator/create-user.html.twig');
    }

    /**
     * @Route("/admin/delete_user", name="delete_user")
     */
    public function deleteUser(Request $request)
    {

    }
}

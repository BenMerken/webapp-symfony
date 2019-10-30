<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdministratorController extends AbstractController
{
    /**
     * @Route("/admin", methods={"GET"}, name="admin_dashboard")
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
}

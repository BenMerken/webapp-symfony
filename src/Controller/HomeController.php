<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Security $security)
    {
        $user = $security->getUser();
        $username = '';
        $roles = [];
        if ($user) {
            $username = $user->getUsername();
            $roles = $user->getRoles();
        }

        return $this->render('home/index.html.twig',
            [
                'app_name' => 'PXL\'s Asset Management Tool',
                'username' => $username,
                'roles' => $roles,
            ]);
    }
}

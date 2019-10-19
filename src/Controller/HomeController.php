<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $domain = 'http://symfony01.local';

        return $this->render('home/index.html.twig',
            [
                'app_name' => 'PXL\'s Asset Management Tool',
                'actions' =>
                    [
                        [
                            "GET $domain/tickets?assetName={name}",
                            "Gets a list of Tickets for a given assetName."
                        ],
                        [
                            "POST $domain/tickets?assetName={name}",
                            "Posts a new Ticket for a given assetName.",
                            "{name}: the name of an existing Asset.",
                            "Requires a \"description\" field in the JSON body of the request."
                        ],
                        [
                            "PATCH $domain/tickets/{id}]",
                            "Updates the numberOfVotes for a Ticket",
                            "{id}: a Ticket's id."
                        ]
                    ]
            ]);
    }
}

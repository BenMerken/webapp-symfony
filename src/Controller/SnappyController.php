<?php

namespace App\Controller;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
class SnappyController extends AbstractController
{
    /**
     * @Route("/snappy", name="generate_pdf")
     */
    public function generatePDF(Request $request, Pdf $snappy)
    {
        $url = 'https://localhost'. $request->get('route');
        $filename = $this->get('security.token_storage')->getToken()->getUser()->getEmail() . '.pdf';

        return new PdfResponse(
            $snappy->getOutput($url),
            $filename
        );
    }
}

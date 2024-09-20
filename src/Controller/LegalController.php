<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LegalController extends AbstractController
{
    #[Route('/mentions-légales', name: 'app_legal')]
    public function mentions(): Response
    {
        return $this->render('legal/mentions.html.twig', [
            'controller_name' => 'LegalController',
        ]);
    }

    #[Route('/politique-de-confidentialité', name: 'app_confidentialite')]
    public function confid(): Response
    {
        return $this->render('legal/confid.html.twig', [
            'controller_name' => 'LegalController',
        ]);
    }
}

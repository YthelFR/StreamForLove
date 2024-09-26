<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RobotsController extends AbstractController
{
    #[Route("/robots.txt", name: 'robots', format: 'txt')]
    public function robots(): Response
    {
        $sitemap = $this->generateUrl('sitemap', [], UrlGeneratorInterface::ABSOLUTE_URL);
        return $this->render('robots/robots.txt.twig', [
            'sitemap' => $sitemap,
        ]);
    }
}

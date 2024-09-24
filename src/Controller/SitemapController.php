<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    #[Route("sitemap.xml", name: 'sitemap', format: 'xml')]
    public function sitemap(): Response
    {
        $urls = [
            ['loc' => $this->generateUrl('home')],
            ['loc' => $this->generateUrl('streamers')],
            ['loc' => $this->generateUrl('evenements')],
            ['loc' => $this->generateUrl('contact')],
            ['loc' => 'https://coalitionplus.org'], 
        ];

        return $this->render('sitemap/sitemap.xml.twig', [
            'urls' => $urls,
        ]);
    }
}
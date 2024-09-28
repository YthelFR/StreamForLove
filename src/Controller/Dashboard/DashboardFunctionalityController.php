<?php

namespace App\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardFunctionalityController extends AbstractController
{
    #[Route('/dashboard/weather', name: 'dashboard_weather')]
    public function weather(): Response
    {
        // Code pour récupérer la météo
        return $this->render('dashboard/weather.html.twig', []);
    }

    #[Route('/dashboard/resources', name: 'dashboard_resources')]
    public function resources(): Response
    {
        // Code pour afficher les ressources et liens utiles
        return $this->render('dashboard/resources.html.twig', []);
    }

    #[Route('/dashboard/social-media', name: 'dashboard_social_media')]
    public function socialMedia(): Response
    {
        // Code pour afficher le feed Instagram
        return $this->render('dashboard/social_media.html.twig', []);
    }

    #[Route('/dashboard/inspirations', name: 'dashboard_inspirations')]
    public function inspirations(): Response
    {
        // Code pour afficher les inspirations et citations
        return $this->render('dashboard/inspirations.html.twig', []);
    }

    #[Route('/dashboard/updates', name: 'dashboard_updates')]
    public function updates(): Response
    {
        // Code pour afficher les mises à jour et annonces
        return $this->render('dashboard/updates.html.twig', []);
    }
}

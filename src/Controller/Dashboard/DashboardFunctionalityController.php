<?php

namespace App\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardFunctionalityController extends AbstractController
{
    #[Route('/dashboard/weather', name: 'dashboard_weather', requirements: ['_role' => 'ROLE_ADMIN'])]
    public function weather(): Response
    {
        $city = 'Paris'; // Vous pouvez récupérer cela via un formulaire
        $apiKey = 'f2a688e9dce953d3f594f6b89d80ddb7';
        $url = "http://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric";

        $response = file_get_contents($url);
        $weatherData = json_decode($response, true);

        return $this->render('dashboard/weather.html.twig', [
            'weather' => $weatherData,
        ]);
    }

    #[Route('/dashboard/resources', name: 'dashboard_resources', requirements: ['_role' => 'ROLE_ADMIN'])]
    public function resources(): Response
    {
        // Code pour afficher les ressources et liens utiles
        return $this->render('dashboard/resources.html.twig', []);
    }

    #[Route('/dashboard/social-media', name: 'dashboard_social_media', requirements: ['_role' => 'ROLE_ADMIN'])]
    public function socialMedia(): Response
    {
        // Remplacez cela par un appel à l'API Instagram
        $instagramPosts = [
            ['link' => 'https://www.instagram.com/p/EXEMPLE1', 'image_url' => 'https://via.placeholder.com/150'],
            ['link' => 'https://www.instagram.com/p/EXEMPLE2', 'image_url' => 'https://via.placeholder.com/150'],
            // Ajoutez d'autres posts de test ici
        ];

        return $this->render('dashboard/social_media.html.twig', [
            'instagramPosts' => $instagramPosts,
        ]);
    }

    #[Route('/dashboard/inspirations', name: 'dashboard_inspirations', requirements: ['_role' => 'ROLE_ADMIN'])]
    public function inspirations(): Response
    {
        $inspirations = [
            'La vie est un défi, relève-le !',
            'La créativité, c\'est l\'intelligence qui s\'amuse.',
            'Faites de votre vie un rêve, et d\'un rêve, une réalité.',
            // Ajoutez d'autres citations ici
        ];

        // Sélectionner une inspiration au hasard
        $randomInspiration = $inspirations[array_rand($inspirations)];

        return $this->render('dashboard/inspirations.html.twig', [
            'randomInspiration' => $randomInspiration,
        ]);
    }

    #[Route('/dashboard/updates', name: 'dashboard_updates', requirements: ['_role' => 'ROLE_ADMIN'])]
    public function updates(): Response
    {
        // Remplacez cela par une source de données réelle si nécessaire
        return $this->render('dashboard/updates.html.twig', []);
    }
}

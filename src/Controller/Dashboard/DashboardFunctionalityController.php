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

    #[Route('/dashboard/updates', name: 'dashboard_updates', requirements: ['_role' => 'ROLE_ADMIN'])]
    public function updates(): Response
    {
        // Remplacez cela par une source de données réelle si nécessaire
        return $this->render('dashboard/updates.html.twig', []);
    }
}

<?php

namespace App\Controller\Dashboard;

use App\Service\InspirationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StreamerDashboardController extends AbstractController
{
    private InspirationService $inspirationService;

    public function __construct(InspirationService $inspirationService)
    {
        $this->inspirationService = $inspirationService;
    }
    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(Request $request): Response
    {
        $city = $request->query->get('city', 'Paris'); // Récupère la ville depuis la requête ou utilise 'Paris' par défaut
        $apiKey = 'f2a688e9dce953d3f594f6b89d80ddb7';
        $url = "http://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric";

        $response = file_get_contents($url);
        $weatherData = json_decode($response, true);

        // Obtenir une inspiration aléatoire
        $randomInspiration = $this->inspirationService->getRandomInspiration();

        return $this->render('dashboard/streamers/user_dashboard.html.twig', [
            'weather' => $weatherData,
            'randomInspiration' => $randomInspiration,
        ]);
    }
}

<?php

namespace App\Controller\Streamer;

// use App\Service\InspirationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;


class StreamerDashboardController extends AbstractController
{
    // private InspirationService $inspirationService;
    // private WeatherService $weatherService;
    private LoggerInterface $logger;


    public function __construct(LoggerInterface $logger)
    {
        // $this->inspirationService = $inspirationService;
        // $this->weatherService = $weatherService;
        $this->logger = $logger;
    }
    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(Security $security, Request $request): Response
    {
        $currentUser = $security->getUser();
        // $city = $request->query->get('city', 'Paris');

        // try {
        //     $weatherData = $this->weatherService->getWeatherData($city);
        // } catch (\Exception $e) {
        //     // Enregistre l'erreur dans les logs
        //     $this->logger->error('Erreur lors de la récupération des données météo : ' . $e->getMessage());
        //     $weatherData = null;  // Gérer une valeur par défaut ou une erreur utilisateur
        // }

        // $randomInspiration = $this->inspirationService->getRandomInspiration();

        return $this->render('dashboard/streamers/user_dashboard.html.twig', [
            'currentUser' => $currentUser,
            // 'weather' => $weatherData,
            // 'randomInspiration' => $randomInspiration,
        ]);
    }

    // #[Route('/dashboard/weather', name: 'dashboard_weather')]
    // public function weatherDashboard(Request $request): Response
    // {
    //     $city = $request->query->get('city', 'Paris');
    //     $weatherData = $this->weatherService->getWeatherData($city);

    //     return $this->render('dashboard/streamers/views/weather.html.twig', [
    //         'weather' => $weatherData,
    //     ]);
    // }

    #[Route('/dashboard/resources', name: 'dashboard_resources')]
    public function streamerResources(): Response
    {
        return $this->render('dashboard/streamers/views/resources.html.twig', []);
    }

    #[Route('/dashboard/updates', name: 'dashboard_updates')]
    public function streamerUpdates(): Response
    {
        return $this->render('dashboard/streamers/views/updates.html.twig', []);
    }
}

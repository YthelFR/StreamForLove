<?php

namespace App\Controller\Admin;

use App\Service\WeatherService;
use App\Service\InspirationsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;

class AdminDashboardController extends AbstractController
{
    private WeatherService $weatherService;
    private InspirationsService $inspirationsService;
    private LoggerInterface $logger;


    public function __construct(LoggerInterface $logger, WeatherService $weatherService, InspirationsService $inspirationsService)
    {
        $this->weatherService = $weatherService;
        $this->inspirationsService = $inspirationsService;
        $this->logger = $logger;
    }

    #[Route('/admin', name: 'app_admin')]
    public function dashboard(Security $security, Request $request): Response
    {
        $currentUser = $security->getUser();
        $city = $request->query->get('city', 'Paris');

        try {
            $weatherData = $this->weatherService->getWeatherData($city);
        } catch (\Exception $e) {
            // Enregistre l'erreur dans les logs
            $this->logger->error('Erreur lors de la récupération des données météo : ' . $e->getMessage());
            $weatherData = null;  // Gérer une valeur par défaut ou une erreur utilisateur
        }

        $randomInspiration = $this->inspirationsService->getRandomInspiration();

        return $this->render('dashboard/admin/admindashboard.html.twig', [
            'currentUser' => $currentUser,
            'weather' => $weatherData,
            'inspiration' => $randomInspiration,
        ]);
    }

    #[Route('/admin/weather', name: 'admin_weather')]
    public function weatherAdmin(Request $request): Response
    {
        $city = $request->query->get('city', 'Paris');
        $weatherData = $this->weatherService->getWeatherData($city);

        return $this->render('dashboard/admin/views/weather.html.twig', [
            'weather' => $weatherData,
        ]);
    }

    #[Route('/admin/resources', name: 'admin_resources')]
    public function adminResources(): Response
    {
        return $this->render('dashboard/admin/views/resources.html.twig', []);
    }

    #[Route('/admin/updates', name: 'admin_updates')]
    public function adminUpdates(): Response
    {
        return $this->render('dashboard/admin/views/updates.html.twig', []);
    }
}

<?php

namespace App\Controller\Dashboard;

use App\Service\InspirationService;
use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

class DashboardController extends AbstractController
{
    private InspirationService $inspirationService;
    private WeatherService $weatherService;

    public function __construct(InspirationService $inspirationService, WeatherService $weatherService)
    {
        $this->inspirationService = $inspirationService;
        $this->weatherService = $weatherService;
    }

    #[Route('/admin', name: 'app_admin')]
    public function adminDashboard(Security $security, Request $request): Response
    {
        $currentUser = $security->getUser();
        $city = $request->query->get('city', 'Paris');
        $weatherData = $this->weatherService->getWeatherData($city);
        $randomInspiration = $this->inspirationService->getRandomInspiration();

        return $this->render('dashboard/admin/admindashboard.html.twig', [
            'currentUser' => $currentUser,
            'weather' => $weatherData,
            'randomInspiration' => $randomInspiration,
        ]);
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function streamerDashboard(Request $request): Response
    {
        $city = $request->query->get('city', 'Paris');
        $weatherData = $this->weatherService->getWeatherData($city);
        $randomInspiration = $this->inspirationService->getRandomInspiration();

        return $this->render('dashboard/streamers/user_dashboard.html.twig', [
            'weather' => $weatherData,
            'randomInspiration' => $randomInspiration,
        ]);
    }

    #[Route('/admin/weather', name: 'admin_weather')]
    public function weatherAdmin(Request $request): Response
    {
        $city = $request->query->get('city', 'Paris');
        $weatherData = $this->weatherService->getWeatherData($city);

        return $this->render('dashboard/admin/weather.html.twig', [
            'weather' => $weatherData,
        ]);
    }

    #[Route('/dashboard/weather', name: 'dashboard_weather')]
    public function weatherDashboard(Request $request): Response
    {
        $city = $request->query->get('city', 'Paris');
        $weatherData = $this->weatherService->getWeatherData($city);

        return $this->render('dashboard/streamers/weather.html.twig', [
            'weather' => $weatherData,
        ]);
    }

    #[Route('/admin/resources', name: 'admin_resources')]
    #[Route('/dashboard/resources', name: 'dashboard_resources')]
    public function resources(): Response
    {
        return $this->render('dashboard/resources.html.twig', []);
    }

    #[Route('/admin/updates', name: 'admin_updates')]
    #[Route('/dashboard/updates', name: 'dashboard_updates')]
    public function updates(): Response
    {
        return $this->render('dashboard/updates.html.twig', []);
    }
}

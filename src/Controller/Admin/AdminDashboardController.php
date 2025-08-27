<?php

namespace App\Controller\Admin;

use App\Service\InspirationsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;

class AdminDashboardController extends AbstractController
{
    private InspirationsService $inspirationsService;
    private LoggerInterface $logger;


    public function __construct(LoggerInterface $logger,  InspirationsService $inspirationsService)
    {
        $this->inspirationsService = $inspirationsService;
        $this->logger = $logger;
    }

    #[Route('/admin', name: 'app_admin')]
    public function dashboard(Security $security, Request $request): Response
    {
        $currentUser = $security->getUser();

        $randomInspiration = $this->inspirationsService->getRandomInspiration();

        return $this->render('dashboard/admin/admindashboard.html.twig', [
            'currentUser' => $currentUser,
            'inspiration' => $randomInspiration,
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

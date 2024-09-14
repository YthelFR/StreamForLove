<?php

namespace App\Controller\Streamer;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_BLOGUEUR')]
#[IsGranted('ROLE_STREAMER_ACTIF')]
#[IsGranted('ROLE_STREAMER_ABSENT')]
class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard/user_dashboard.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}
<?php

namespace App\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminDashboardController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function dashboard(Security $security): Response
    {
        $currentUser = $security->getUser();

        return $this->render('dashboard/admin/admindashboard.html.twig', [
            'currentUser' => $currentUser,
        ]);
    }

}

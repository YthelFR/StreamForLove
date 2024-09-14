<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function dashboard(Security $security): Response
    {
        $user = $security->getUser(); // Obtenez l'utilisateur connecté

        return $this->render('admin/admindashboard.html.twig', [
            'user' => $user,
        ]);
    }
}

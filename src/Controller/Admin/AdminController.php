<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[IsGranted('ROLE_MANAGER')]
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

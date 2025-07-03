<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AccessController extends AbstractController
{
    #[Route('/access', name: 'access_page')]
    public function access(Request $request, SessionInterface $session): Response
    {
        $error = null;

        if ($request->isMethod('POST')) {
            $password = $request->request->get('password');

            if ($password === $_ENV['SITE_PASSWORD']) {
                $session->set('access_granted', true);
                return $this->redirectToRoute('app_home'); // Change 'app_home' selon ta route
            } else {
                $error = 'Mot de passe incorrect.';
            }
        }

        return $this->render('access/index.html.twig', [
            'error' => $error
        ]);
    }
}

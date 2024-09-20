<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ErrorController extends AbstractController
{

    public function show404(): Response
    {
        return $this->render('error/404.html.twig', [
            'message' => 'Page non trouvée.',
        ]);
    }
}

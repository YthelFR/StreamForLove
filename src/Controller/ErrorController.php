<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ErrorController extends AbstractController
{
    public function show(Request $request): Response
    {
        $exception = $request->attributes->get('exception');

        if ($exception instanceof NotFoundHttpException) {
            return $this->render('bundles/TwigBundle/Exception/error404.html.twig', [
                'message' => $exception->getMessage(),
            ]);
        }

        return $this->render('bundles/TwigBundle/Exception/error404.html.twig');
    }
}
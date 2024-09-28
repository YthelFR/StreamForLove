<?php

namespace App\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class InspirationApiController extends AbstractController
{
    #[Route('/dashboard/inspirations', name: 'api_inspirations')]
    public function getInspirations(): JsonResponse
    {
        $inspirations = [
            'La vie est un défi, relève-le !',
            'La créativité, c\'est l\'intelligence qui s\'amuse.',
            'Faites de votre vie un rêve, et d\'un rêve, une réalité.',
            // Ajoutez d'autres citations ici
        ];

        // Sélectionner une inspiration au hasard
        $randomInspiration = $inspirations[array_rand($inspirations)];

        return new JsonResponse([
            'inspiration' => $randomInspiration,
        ]);
    }
}

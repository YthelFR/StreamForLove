<?php

namespace App\Controller\Dashboard;

use App\Service\InspirationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class InspirationApiController extends AbstractController
{
    private InspirationService $inspirationService;

    public function __construct(InspirationService $inspirationService)
    {
        $this->inspirationService = $inspirationService;
    }

    #[Route('/api/inspirations', name: 'api_inspirations')]
    public function getInspiration(): JsonResponse
    {
        $randomInspiration = $this->inspirationService->getRandomInspiration();

        return new JsonResponse([
            'inspiration' => $randomInspiration,
        ]);
    }
}

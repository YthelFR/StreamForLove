<?php

namespace App\Controller;

use App\Entity\Outsiders;
use App\Repository\OutsidersRepository;
use App\Service\TwitchApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/outsiders')]
class OutsidersController extends AbstractController
{
    private TwitchApiService $twitchApiService;

    public function __construct(TwitchApiService $twitchApiService)
    {
        $this->twitchApiService = $twitchApiService;
    }
    #[Route('/', name: 'app_outsiders_index', methods: ['GET'])]
    public function index(OutsidersRepository $outsidersRepository): Response
    {
        return $this->render('outsiders/index.html.twig', [
            'outsiders' => $outsidersRepository->findAll(),
        ]);
    }

    #[Route('/{pseudo}', name: 'app_outsiders_show', methods: ['GET'])]
    public function show(OutsidersRepository $outsidersRepository, string $pseudo): Response
    {
        $outsider = $outsidersRepository->findOneBy(['pseudo' => $pseudo]);

        if (!$outsider) {
            throw $this->createNotFoundException('Outsider not found');
        }

        // Récupération des informations de la chaîne Twitch
        $channelInfo = $this->twitchApiService->getChannelInfo($outsider->getPseudo());
        $recentGames = $this->twitchApiService->getRecentGames($outsider->getPseudo());

        return $this->render('outsiders/show.html.twig', [
            'outsider' => $outsider,
            'channelInfo' => $channelInfo,
            'recentGames' => $recentGames, // Passing the game details to the view
        ]);
    }


    private function getRecentGames(string $username): array
    {
        $response = $this->twitchApiService->getChannelInfo($username); // Assumes this method can fetch recent games, otherwise implement a new one.
        return $response['data']['recent_games'] ?? []; // Adapt according to actual API response
    }
}

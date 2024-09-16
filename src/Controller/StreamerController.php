<?php

namespace App\Controller;

use App\Entity\Outsiders;
use App\Entity\Users;
use App\Repository\PresentationsRepository;
use App\Repository\UsersRepository;
use App\Service\TwitchApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StreamerController extends AbstractController
{
    private $twitchApiService;
    private $presentationsRepository;
    private $usersRepository;

    public function __construct(TwitchApiService $twitchApiService, PresentationsRepository $presentationsRepository,
    UsersRepository $usersRepository)
    {
        $this->twitchApiService = $twitchApiService;
        $this->presentationsRepository = $presentationsRepository;
        $this->usersRepository = $usersRepository;
    }

    #[Route('/streamers', name: 'app_streamers')]
    public function index(EntityManagerInterface $entityManager, TwitchApiService $twitchApiService): Response
    {
        $activeStreamers = $entityManager->getRepository(Users::class)->findByRole('ROLE_STREAMER_ACTIF');
        $inactiveStreamers = $entityManager->getRepository(Users::class)->findByRole('ROLE_STREAMER_ABSENT');
        $outsiders = $entityManager->getRepository(Outsiders::class)->findAll();
        // Trier les streamers actifs par pseudo
        $sortByPseudo = function($a, $b) {
            return strcmp($a->getPseudo(), $b->getPseudo());
        };
    
        // Trier les streamers actifs, inactifs et outsiders par pseudo
        usort($activeStreamers, $sortByPseudo);
        usort($inactiveStreamers, $sortByPseudo);
        usort($outsiders, $sortByPseudo);

        $populateStreamerData = function($streamer) use ($twitchApiService) {
            $channelInfo = $twitchApiService->getChannelInfo($streamer->getPseudo());
            $streamer->avatarUrl = $channelInfo['profile_image_url'] ?? '';
            $streamer->isLive = $twitchApiService->isUserLive($streamer);
        };
    
        // Récupérer les informations Twitch pour les streamers actifs et inactifs
        foreach ($activeStreamers as $streamer) {
            $populateStreamerData($streamer);
        }
    
        foreach ($inactiveStreamers as $streamer) {
            $populateStreamerData($streamer);
        }
    
        // Récupérer les informations Twitch pour les outsiders
        foreach ($outsiders as $outsider) {
            $channelInfo = $twitchApiService->getChannelInfo($outsider->getPseudo());
            $outsider->avatarUrl = $channelInfo['profile_image_url'] ?? '';
        }
    
        return $this->render('streamer/index.html.twig', [
            'activeStreamers' => $activeStreamers,
            'inactiveStreamers' => $inactiveStreamers,
            'outsiders' => $outsiders,
        ]);
    }

    #[Route('/streamers/{pseudo}', name: 'app_streamers_show', methods: ['GET'])]
    public function showStreamer(
        UsersRepository $usersRepository, 
        TwitchApiService $twitchApiService, 
        string $pseudo
    ): Response {
        $streamer = $usersRepository->findOneByPseudo($pseudo);
    
        if (!$streamer) {
            throw $this->createNotFoundException('Streamer not found');
        }
        $presentations = $this->presentationsRepository->findOneBy(['streamersPresentation' => $streamer]);
        $socialsNetworks = $streamer->getSocialsNetworks();
        $channelInfo = $twitchApiService->getChannelInfo($streamer->getPseudo());
        $broadcasterId = $channelInfo['id'] ?? '';
        $followersCount = $twitchApiService->getChannelFollowers($broadcasterId);
        $recentGames = $twitchApiService->getRecentGames($streamer->getPseudo());
    
        return $this->render('streamer/show.html.twig', [
            'streamer' => $streamer,
            'channelInfo' => $channelInfo,
            'recentGames' => $recentGames, 
            'presentations' => $presentations,
            'socialsNetworks' => $socialsNetworks,
            'followersCount' => $followersCount,
        ]);
    }
}
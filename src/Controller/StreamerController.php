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
        $inactiveStreamers = $entityManager->getRepository(Users::class)->findByRole('ROLE_STREAMER_INACTIF');
        $outsiders = $entityManager->getRepository(Outsiders::class)->findAll();
        // Trier les streamers actifs par pseudo
        usort($activeStreamers, function($a, $b) {
            return strcmp($a->getPseudo(), $b->getPseudo());
        });

        // Trier les streamers inactifs par pseudo
        usort($inactiveStreamers, function($a, $b) {
            return strcmp($a->getPseudo(), $b->getPseudo());
        });

        // Trier les outsiders par pseudo   
        usort($outsiders, function($a, $b) {
            return strcmp($a->getPseudo(), $b->getPseudo());
        });

        foreach ($activeStreamers as $streamer) {
            $channelInfo = $twitchApiService->getChannelInfo($streamer->getPseudo());
            $streamer->avatarUrl = $channelInfo['profile_image_url'] ?? '';
        }
        
        foreach ($inactiveStreamers as $streamer) {
            $channelInfo = $twitchApiService->getChannelInfo($streamer->getPseudo());
            $streamer->avatarUrl = $channelInfo['profile_image_url'] ?? '';
        }

        // Assignez les avatars pour les outsiders
        foreach ($outsiders as $outsider) {
            $channelInfo = $this->twitchApiService->getChannelInfo($outsider->getPseudo());
            $outsider->avatarUrl = $channelInfo['profile_image_url'] ?? '';
        }
        // Rendre le template Twig avec la liste des streamers actifs, absents et outsiders
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
        // Retrieve Twitch channel info
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
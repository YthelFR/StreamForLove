<?php

namespace App\Controller;

use App\Service\TwitchApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TwitchController extends AbstractController
{
    private $twitchApiService;

    public function __construct(TwitchApiService $twitchApiService)
    {
        $this->twitchApiService = $twitchApiService;
    }

    #[Route('/twitch/ythel_channel', name: 'twitch_ythel_channel')]
    public function showChannelInfo(): Response
    {
        $channelInfo = $this->twitchApiService->getChannelInfo('ythel_');

        return $this->render('twitch/channel.html.twig', [
            'channel' => $channelInfo,
        ]);
    }
}
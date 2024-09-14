<?php

namespace App\Service;

use App\Entity\SocialsNetwork;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TwitchApiService
{
    private $client;
    private $clientId;
    private $accessToken;
    private $em;

    public function __construct(HttpClientInterface $client, string $clientId, string $accessToken, EntityManagerInterface $em)
    {
        $this->client = $client;
        $this->clientId = $clientId;
        $this->accessToken = $accessToken;
        $this->em = $em;
    }

    public function getChannelInfo(string $username): array
    {
        $response = $this->client->request('GET', 'https://api.twitch.tv/helix/users', [
            'headers' => [
                'Client-ID' => $this->clientId,
                'Authorization' => 'Bearer ' . $this->accessToken,
            ],
            'query' => [
                'login' => $username,
            ],
        ]);

        return $response->toArray();
    }

    public function isUserLive(Users $user): bool
    {
        // Cherche le réseau social "Twitch" associé à l'utilisateur
        $twitchNetwork = $this->em->getRepository(SocialsNetwork::class)
            ->findOneBy(['user' => $user, 'name' => 'twitch']);

        // Si l'utilisateur n'a pas de réseau Twitch, retourne false
        if (!$twitchNetwork) {
            return false;
        }

        // Extrait le pseudo Twitch de l'URL (ex : https://www.twitch.tv/mandragoule_ -> mandragoule_)
        $twitchUrl = $twitchNetwork->getUrl();
        $username = str_replace('https://www.twitch.tv/', '', $twitchUrl);

        // Si le pseudo est vide après extraction, retourne false
        if (empty($username)) {
            return false;
        }

        // Effectue la requête pour savoir si l'utilisateur est en live
        $response = $this->client->request('GET', 'https://api.twitch.tv/helix/streams', [
            'headers' => [
                'Client-ID' => $this->clientId,
                'Authorization' => 'Bearer ' . $this->accessToken,
            ],
            'query' => [
                'user_login' => $username,
            ],
        ]);

        $data = $response->toArray();

        return !empty($data['data']);
    }
}
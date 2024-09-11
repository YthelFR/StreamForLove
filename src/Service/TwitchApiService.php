<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class TwitchApiService
{
    private $client;
    private $clientId;
    private $accessToken;

    public function __construct(HttpClientInterface $client, string $clientId, string $accessToken)
    {
        $this->client = $client;
        $this->clientId = $clientId;
        $this->accessToken = $accessToken;
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

    public function isUserLive(string $username): bool
    {
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
<?php

namespace App\Service;

class WeatherService
{
    private string $apiKey;

    public function __construct(string $weatherApiKey)
    {
        $this->apiKey = $weatherApiKey;
    }

    public function getWeatherData(string $city = 'Paris'): array
    {
        $url = "http://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$this->apiKey}&units=metric";

        $response = @file_get_contents($url); // Utiliser l'opérateur @ pour éviter les warnings

        if ($response === false) {
            // En cas d'erreur de la requête
            throw new \Exception('Could not retrieve weather data.');
        }

        $data = json_decode($response, true);

        // Vérifier si l'API a retourné une erreur
        if (isset($data['cod']) && $data['cod'] !== 200) {
            throw new \Exception('Weather API error: ' . $data['message']);
        }

        return $data;
    }
}

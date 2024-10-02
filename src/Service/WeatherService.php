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
        $response = file_get_contents($url);
        return json_decode($response, true);
    }
}

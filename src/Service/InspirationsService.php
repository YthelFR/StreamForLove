<?php

namespace App\Service;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class InspirationsService
{
    private string $filePath;

    public function __construct(string $projectDir)
    {
        $this->filePath = $projectDir . '/public/assets/data/inspirations.json';
    }

    public function getInspirations(): array
    {
        if (!file_exists($this->filePath)) {
            throw new FileNotFoundException("Fichier des inspirations non trouvé.");
        }

        $jsonContent = file_get_contents($this->filePath);
        return json_decode($jsonContent, true); 
    }

    public function getRandomInspiration(): ?array
    {
        $inspirations = $this->getInspirations();

        $games = array_keys($inspirations);
        $randomGame = $games[array_rand($games)];

        $characters = array_keys($inspirations[$randomGame]);
        $randomCharacter = $characters[array_rand($characters)];

        $quotes = $inspirations[$randomGame][$randomCharacter]['quotes'];
        $randomQuote = $quotes[array_rand($quotes)];

        return [
            'game' => $randomGame,
            'character' => $randomCharacter,
            'avatar' => $inspirations[$randomGame][$randomCharacter]['avatar'],
            'quote' => $randomQuote
        ];
    }
}

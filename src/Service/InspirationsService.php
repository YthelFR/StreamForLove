<?php

namespace App\Service;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class InspirationsService
{
    private string $filePath;

    public function __construct(string $projectDir)
    {
        // Chemin du fichier JSON
        $this->filePath = $projectDir . '/public/assets/data/inspirations.json';
    }

    // Fonction pour récupérer l'intégralité des inspirations
    public function getInspirations(): array
    {
        if (!file_exists($this->filePath)) {
            throw new FileNotFoundException("Fichier des inspirations non trouvé.");
        }

        $jsonContent = file_get_contents($this->filePath);
        return json_decode($jsonContent, true); // Retourne les données décodées sous forme de tableau
    }

    // Fonction pour récupérer une citation aléatoire
    public function getRandomInspiration(): ?array
    {
        $inspirations = $this->getInspirations();

        // Sélection aléatoire d'un jeu
        $games = array_keys($inspirations);
        $randomGame = $games[array_rand($games)];

        // Sélection aléatoire d'un personnage dans ce jeu
        $characters = array_keys($inspirations[$randomGame]);
        $randomCharacter = $characters[array_rand($characters)];

        // Sélection aléatoire d'une citation pour ce personnage
        $quotes = $inspirations[$randomGame][$randomCharacter]['quotes'];
        $randomQuote = $quotes[array_rand($quotes)];

        // Retourne le jeu, le personnage, l'avatar et la citation aléatoire
        return [
            'game' => $randomGame,
            'character' => $randomCharacter,
            'avatar' => $inspirations[$randomGame][$randomCharacter]['avatar'],
            'quote' => $randomQuote
        ];
    }
}

<?php

namespace App\Service;

use Symfony\Component\Yaml\Yaml;

class InspirationService
{
    private array $inspirations;

    public function __construct(string $filePath)
    {
        $this->inspirations = Yaml::parseFile($filePath);
    }

    public function getRandomInspiration(): array
    {
        $categories = $this->inspirations['inspirations'];
        $category = array_rand($categories);
        $person = array_rand($categories[$category]);
        $quotes = $categories[$category][$person];

        // Sélectionner une citation au hasard
        $randomQuote = $quotes[array_rand($quotes)];

        // Retourner la citation et les informations sur le personnage
        return [
            'quote' => $randomQuote,
            'character' => ucfirst(str_replace('_', ' ', $person)), // Formater le nom du personnage
            'series' => ucfirst(str_replace('_', ' ', $category)) // Formater le nom de la série
        ];
    }
}

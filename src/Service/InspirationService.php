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

    public function getRandomInspiration(): string
    {
        $categories = $this->inspirations['inspirations'];
        $category = array_rand($categories);
        $person = array_rand($categories[$category]);
        $quotes = $categories[$category][$person];

        return $quotes[array_rand($quotes)];
    }
}

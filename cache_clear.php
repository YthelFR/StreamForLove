<?php

// Protection par mot de passe basique
// $username = 'Yoan';
// $password = '@Yoyoyo21041993';

// if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] !== $username || $_SERVER['PHP_AUTH_PW'] !== $password) {
//     header('WWW-Authenticate: Basic realm="Clear Cache"');
//     header('HTTP/1.0 401 Unauthorized');
//     echo 'Unauthorized';
//     exit;
// }

// Utilisez le chemin vers la version correcte de PHP 8.3
$php_path = '/usr/local/bin/php8.3';
$console_path = __DIR__ . '/bin/console'; // Chemin vers la console Symfony
$command = 'cache:clear';

// Vérification de l'environnement
$environment = getenv('APP_ENV') ?: 'prod'; // Utiliser une variable d'environnement ou 'prod' par défaut
if ($environment !== 'prod') {
    die("This script can only be run in the production environment.\n");
}

try {
    // Construire et exécuter la commande
    $output = [];
    $return_var = 0;
    exec("$php_path $console_path $command", $output, $return_var);

    // Afficher la sortie de la commande
    if ($return_var === 0) {
        echo "Cache cleared successfully:\n";
        echo implode("\n", $output);
    } else {
        echo "Error clearing cache. Return code: $return_var\n";
        echo implode("\n", $output);
        // Enregistrer l'erreur dans un fichier log
        file_put_contents('cache_clear_errors.log', implode("\n", $output), FILE_APPEND);
    }
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage();
    // Enregistrer l'exception dans un fichier log
    file_put_contents('cache_clear_errors.log', "Exception: " . $e->getMessage() . "\n", FILE_APPEND);
}

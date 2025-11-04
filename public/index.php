<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../lib/Router.php';

use App\Library\Route;

// Récupère l'URL demandée
$requestUri = strtok($_SERVER['REQUEST_URI'], '?');
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Route pour la page d'accueil
if ($requestUri === '/' || $requestUri === '/home') {
    include __DIR__ . '/../src/Views/home.php';
    exit;
}

// Route pour le CSS (fix chemin)
if (preg_match('#^/css/(.+)$#', $requestUri, $matches)) {
    $cssFile = __DIR__ . '/css/' . $matches[1];
    if (file_exists($cssFile)) {
        header('Content-Type: text/css');
        readfile($cssFile);
        exit;
    } else {
        http_response_code(404);
        echo 'CSS not found';
        exit;
    }
}

// Ajoute ici d'autres routes dynamiques avec Route si besoin

// 404 par défaut
http_response_code(404);
echo 'Page non trouvée';

<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../lib/Router.php';
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->usePutenv();
$dotenv->loadEnv(__DIR__ . '/../.env', true, true);

use App\Library\Route;
use PhpParser\Node\Stmt\ElseIf_;

// Récupère l'URL demandée
$requestUri = strtok($_SERVER['REQUEST_URI'], '?');
// Normalise le chemin pour ne garder que la partie après /public
$basePath = str_replace('/public', '', dirname($_SERVER['SCRIPT_NAME']));
$requestUri = str_replace($basePath . '/public', '', $requestUri);
if ($requestUri === '') $requestUri = '/';
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Route pour la page d'accueil
if ($requestUri === '/' || $requestUri === '/home') {
    // Appelle le contrôleur pour injecter les données
    $controller = new \App\Controllers\HomeController();
    $controller->index();
    exit;
} elseif($requestUri === '/books.php') {
    // Appelle le contrôleur pour injecter les données
    $controller = new \App\Controllers\BookController();
    $controller->index();
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

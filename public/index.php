<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../lib/Router.php';

use App\Library\Router;
use App\Controllers\BookController;
use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Controllers\RegisterController;

$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->usePutenv();
$dotenv->loadEnv(__DIR__ . '/../.env.tests', true, true);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Liste des routes (pattern, contrôleur, méthode)
$routes = [
    ['pattern' => '/', 'controller' => HomeController::class, 'action' => 'index', 'method' => 'GET'],
    ['pattern' => '/books', 'controller' => BookController::class, 'action' => 'showBooks', 'method' => 'GET'],
    ['pattern' => '/book/{id}', 'controller' => BookController::class, 'action' => 'showBookDetail', 'method' => 'GET'],
    ['pattern' => '/user/{id}', 'controller' => UserController::class, 'action' => 'showPublicUser', 'method' => 'GET'],
    ['pattern' => '/register', 'controller' => RegisterController::class, 'action' => 'showRegisterForm', 'method' => 'GET'],
    ['pattern' => '/login', 'controller' => RegisterController::class, 'action' => 'showLoginForm', 'method' => 'GET'],
    ['pattern' => '/logout', 'controller' => RegisterController::class, 'action' => 'logout', 'method' => 'GET'],
    ['pattern' => '/user/account/{id}', 'controller' => UserController::class, 'action' => 'showUserAccount', 'method' => 'GET'],
    ['pattern' => '/book/edit/{id}', 'controller' => BookController::class, 'action' => 'editBook', 'method' => 'GET'],
    ['pattern' => '/my-account', 'controller' => HomeController::class, 'action' => 'myAccount', 'method' => 'GET'],
    ['pattern' => '/login/userLogin', 'controller' => RegisterController::class, 'action' => 'loginUser', 'method' => 'POST'],
    ['pattern' => '/register/userRegister', 'controller' => RegisterController::class, 'action' => 'registerUser', 'method' => 'POST'],
    ['pattern' => '/user/update/{id}', 'controller' => UserController::class, 'action' => 'updateUser', 'method' => 'POST'],
    ['pattern' => '/book/update/{id}', 'controller' => BookController::class, 'action' => 'updateBook', 'method' => 'POST'],
    ['pattern' => '/book/delete/{id}', 'controller' => BookController::class, 'action' => 'deleteBook', 'method' => 'POST'],
    ['pattern' => '/user/picture/update/{id}', 'controller' => UserController::class, 'action' => 'updateUserPicture', 'method' => 'POST'],
    ['pattern' => '/messagerie/conversation/{conversationId}/send', 'controller' => \App\Controllers\MessageController::class, 'action' => 'sendMessage', 'method' => 'POST'],
    ['pattern' => '/messagerie/{userId}/conversation/{conversationId}', 'controller' => \App\Controllers\MessageController::class, 'action' => 'showMessaging', 'method' => 'GET'],
    ['pattern' => '/messagerie/{userId}', 'controller' => \App\Controllers\MessageController::class, 'action' => 'showMessaging', 'method' => 'GET'],
    ['pattern' => '/messagerie/start/{book_id}', 'controller' => \App\Controllers\MessageController::class, 'action' => 'startNewRelation', 'method' => 'POST'],
    ['pattern' => '/book/create/book', 'controller' => BookController::class, 'action' => 'createBook', 'method' => 'POST'],
    ['pattern' => '/book/edit-picture/{id}/{userId}', 'controller' => BookController::class, 'action' => 'updateBookPicture', 'method' => 'POST'],
    ];

// Récupère l'URL demandée
$requestUri = strtok($_SERVER['REQUEST_URI'], '?');
if (strpos($requestUri, '/public') !== false) {
    $requestUri = substr($requestUri, strpos($requestUri, '/public') + 7);
}
if ($requestUri === '' || $requestUri === false) {
    $requestUri = '/';
}
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Dispatch
$routeFound = false;
foreach ($routes as $route) {
    $router = new Router($route['pattern'], $route['method']);
    if ($route['method'] === $requestMethod && $router->matchRoute($route['pattern'], $requestUri) !== false) {
        $params = $router->matchRoute($route['pattern'], $requestUri);
        $controllerInstance = new $route['controller']();
        call_user_func_array([$controllerInstance, $route['action']], $params);
        $routeFound = true;
        break;
    }
}

if (!$routeFound) {
    http_response_code(404);
    echo 'Page non trouvée';
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

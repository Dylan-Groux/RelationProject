<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Models\Database\DBManager;

$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->usePutenv();
$dotenv->loadEnv(__DIR__ . '/../../../.env.tests', true, true);
var_dump(__DIR__ . '/../../../.env.tests');

try {
    $dbManager = DBManager::getInstance();
    $pdo = $dbManager->getPDO();
    $stmt = $pdo->query('SELECT 1');
    echo "Connexion à la base de données réussie.";
} catch (Exception $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}

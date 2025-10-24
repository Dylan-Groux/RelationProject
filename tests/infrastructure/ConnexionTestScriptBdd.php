<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Infrastructure\DBManager;

try {
    $dbManager = DBManager::getInstance();
    $pdo = $dbManager->getPDO();
    $stmt = $pdo->query('SELECT 1');
    echo "Connexion à la base de données réussie.";
} catch (Exception $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}

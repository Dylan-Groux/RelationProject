<?php

namespace App\Models\Repository;

use App\Models\Entity\Register;
use PDO;
use App\Models\Database\DBManager;

class RegisterRepository
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? DBManager::getInstance()->getPdo();
    }
}
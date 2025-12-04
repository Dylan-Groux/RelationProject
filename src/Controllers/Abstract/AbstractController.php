<?php

namespace App\Controllers\Abstract;

use App\Controllers\Exception\LoginException;

abstract class AbstractController
{

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}

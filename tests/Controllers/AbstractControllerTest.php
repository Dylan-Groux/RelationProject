<?php

use App\Controllers\Abstract\AbstractController;
use PHPUnit\Framework\TestCase;
use App\Models\Exceptions\LoginException;

class DummyController extends AbstractController
{
    // Permet d'appeler le constructeur d'AbstractController
}

class AbstractControllerTest extends TestCase
{
    protected function setUp(): void
    {
        if (!session_id()) {
            session_start();
        }
        $_SESSION = []; // Reset session
    }

    public function testCheckUserAuthenticatedWithUserId()
    {
        $_SESSION['user_id'] = 42;
        $controller = new DummyController();
        $checkSession = $controller->checkUserAccess(42);
        $this->assertTrue($checkSession);
    }

    public function testCheckUserAuthenticatedRedirectWhenNoUserId()
    {
        $controller = new DummyController();
        $this->expectException(LoginException::class);
        $controller->checkUserAccess(42);
    }
}

<?php

use App\Interface\Controller\AbstractController;
use PHPUnit\Framework\TestCase;
use App\Interface\Exception\LoginException;

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
        $checkSession = $controller->checkUserAuthenticated();
        $this->assertTrue($checkSession);
    }

    public function testCheckUserAuthenticatedRedirectWhenNoUserId()
    {
        $controller = new DummyController();
        try {
            $controller->checkUserAuthenticated();
        } catch (LoginException $e) {
            $this->matchesRegularExpression('/Location: \/login/');
            $this->assertInstanceOf(LoginException::class, $e);
        }
    }
}

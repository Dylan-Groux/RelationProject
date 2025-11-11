<?php

namespace App\Library;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Router
{
    public string $path;
    public string $method;
    public function __construct(string $path, string $method = 'GET') {
        $this->path = $path;
        $this->method = $method;
    }

    public function matchRoute(string $pattern, string $url): array|false
    {
        $regex = preg_replace('#\{(\w+)\}#', '([^/]+)', $pattern);
        $regex = '#^' . $regex . '$#';
        if (preg_match($regex, $url, $matches)) {
            array_shift($matches); // Retire le match complet
            return $matches; // Les paramètres dynamiques
        }
        return false;
    }
}

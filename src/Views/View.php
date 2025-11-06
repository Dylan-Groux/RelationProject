<?php
namespace App\Views;

class View
{
    private string $template;

    public function __construct(string $template)
    {
        $this->template = $template;
    }

    public function render(array $params = [])
    {
        extract($params);
        include __DIR__ . '/' . $this->template . '.php';
    }
}

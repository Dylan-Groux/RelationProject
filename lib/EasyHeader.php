<?php

namespace App\Library;

class EasyHeader
{
    public static function addHeader(string $title, array $css = [], array $js = []): void
    {
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?= htmlspecialchars($title ?? 'TomTroc') ?></title>
              <?php if (!empty($css)): foreach ($css as $href): ?>
                  <link rel="stylesheet" href="<?= strpos($href, 'http') === 0 ? $href : '/' . ltrim($href, '/') ?>">
              <?php endforeach; endif; ?>
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
            <?php if (!empty($js)): foreach ($js as $src): ?>
                 <script src="<?= strpos($src, 'http') === 0 ? $src : '/' . ltrim($src, '/') ?>" defer></script>
            <?php endforeach; endif; ?>
        </head>
        <?php
    }
}

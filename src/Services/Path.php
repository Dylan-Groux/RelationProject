<?php

namespace App\Services;

class Path {
    public static function url(string $path = ''): string {
        return '/Openclassroom/RELATION/' . ltrim($path, '/');
    }
}

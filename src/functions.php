<?php

declare(strict_types=1);


namespace {
    if (!defined('ROOT')) {
        define('ROOT', dirname(__DIR__));
    }

    if (!isset($UTC)) {
        $UTC = new \DateTimeZone('UTC');
    }

    if (!defined('UTC')) {
        define('UTC', $UTC);
    }

    function view(string $view, ?array $attributes = []): void
    {
        extract($attributes);

        $path = str_replace('.', '/', $view);

        require ROOT . '/views/' . $path . '.php';
    }
}
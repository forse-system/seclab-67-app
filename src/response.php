<?php

declare(strict_types=1);

function redirectWithLocationHeader(string $target, int $statusCode = 302): void
{
    $normalized = str_replace(["\r\n", "\r"], "\n", $target);
    $lines = explode("\n", $normalized);
    $location = array_shift($lines);

    if (!is_string($location) || $location === '') {
        $location = '/index.php';
    }

    header('Location: ' . $location, true, $statusCode);

    foreach ($lines as $line) {
        if ($line === '') {
            continue;
        }

        header($line, false);
    }

    exit;
}

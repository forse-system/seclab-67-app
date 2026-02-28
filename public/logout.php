<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /home.php');
    exit;
}

logoutUser();
header('Location: /index.php');
exit;

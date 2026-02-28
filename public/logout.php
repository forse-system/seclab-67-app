<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /home.php');
    exit;
}

$csrfToken = isset($_POST['csrf_token']) ? (string) $_POST['csrf_token'] : null;
if (!verifyCsrfToken($csrfToken)) {
    http_response_code(400);
    echo 'Bad Request';
    exit;
}

logoutUser();
header('Location: /index.php');
exit;

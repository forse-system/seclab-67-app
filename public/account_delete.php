<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

requireLogin();
$user = currentUser();

if ($user === null) {
    header('Location: /index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /home.php');
    exit;
}

if (!deleteCurrentUserAccount((int) $user['id'])) {
    $_SESSION['account_delete_error'] = 'アカウント削除に失敗しました。時間をおいて再度お試しください。';
    header('Location: /home.php');
    exit;
}

header('Location: /index.php');
exit;

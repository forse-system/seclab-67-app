<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

requireLogin();
$user = currentUser();

if ($user === null) {
    redirectWithLocationHeader('/index.php');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWithLocationHeader('/home.php');
}

if (!deleteCurrentUserAccount((int) $user['id'])) {
    $_SESSION['account_delete_error'] = 'アカウント削除に失敗しました。時間をおいて再度お試しください。';
    redirectWithLocationHeader('/home.php');
}

redirectWithLocationHeader('/index.php');

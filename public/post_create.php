<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

requireLogin();
$user = currentUser();

if ($user === null) {
    redirectWithLocationHeader('/index.php');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWithLocationHeader('/board.php');
}

$body = isset($_POST['body']) ? (string) $_POST['body'] : '';

if (!createPost((int) $user['id'], $body)) {
    $_SESSION['post_error'] = '投稿に失敗しました。本文は1-2000文字で入力してください。';
    redirectWithLocationHeader('/board.php');
}

redirectWithLocationHeader('/board.php');

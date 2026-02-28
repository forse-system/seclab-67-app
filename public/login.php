<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /index.php');
    exit;
}

$username = isset($_POST['username']) ? (string) $_POST['username'] : '';
$password = isset($_POST['password']) ? (string) $_POST['password'] : '';

if (attemptLogin($username, $password)) {
    header('Location: /home.php');
    exit;
}

$_SESSION['login_error'] = 'ユーザー名またはパスワードが正しくありません。';
header('Location: /index.php');
exit;

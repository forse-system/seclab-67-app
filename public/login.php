<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWithLocationHeader('/index.php');
}

$username = isset($_POST['username']) ? (string) $_POST['username'] : '';
$password = isset($_POST['password']) ? (string) $_POST['password'] : '';

if (attemptLogin($username, $password)) {
    redirectWithLocationHeader('/home.php');
}

$_SESSION['login_error'] = 'ユーザー名またはパスワードが正しくありません。';
redirectWithLocationHeader('/index.php');

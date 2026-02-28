<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /contact.php');
    exit;
}

$name = isset($_POST['name']) ? (string) $_POST['name'] : '';
$message = isset($_POST['message']) ? (string) $_POST['message'] : '';

if (!createInquiry($name, $message)) {
    $_SESSION['contact_error'] = '送信に失敗しました。名前は100文字以内、本文は5000文字以内で入力してください。';
    $_SESSION['contact_old_name'] = $name;
    $_SESSION['contact_old_message'] = $message;
    header('Location: /contact.php');
    exit;
}

header('Location: /contact_done.php');
exit;

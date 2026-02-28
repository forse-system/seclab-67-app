<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

$redirect = $_GET['redirect'] ?? null;
if (is_string($redirect) && $redirect !== '') {
    redirectWithLocationHeader($redirect);
}

if (isLoggedIn()) {
    redirectWithLocationHeader('/home.php');
}

$loginError = '';
if (isset($_SESSION['login_error']) && is_string($_SESSION['login_error'])) {
    $loginError = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
</head>
<body>
    <h1>社内アプリ ログイン</h1>
    <p><a href="/contact.php">お問い合わせはこちら</a></p>

    <?php if ($loginError !== ''): ?>
        <p><?php echo htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <form action="/login.php" method="post">
        <p>
            <label for="username">ユーザー名</label>
            <input id="username" name="username" type="text" required>
        </p>

        <p>
            <label for="password">パスワード</label>
            <input id="password" name="password" type="password" required>
        </p>

        <button type="submit">ログイン</button>
    </form>
</body>
</html>

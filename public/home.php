<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

requireLogin();
$user = currentUser();

if ($user === null) {
    redirectWithLocationHeader('/index.php');
}

$accountDeleteError = '';
if (isset($_SESSION['account_delete_error']) && is_string($_SESSION['account_delete_error'])) {
    $accountDeleteError = $_SESSION['account_delete_error'];
    unset($_SESSION['account_delete_error']);
}
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
</head>
<body>
    <h1>社内アプリ</h1>
    <p>ようこそ <?php echo htmlspecialchars((string) $user['display_name'], ENT_QUOTES, 'UTF-8'); ?> さん</p>
    <p>ユーザー名: <?php echo htmlspecialchars((string) $user['username'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p><a href="/board.php">掲示板へ</a></p>
    <p><a href="/inquiries.php">お問い合わせ一覧へ</a></p>

    <?php if ($accountDeleteError !== ''): ?>
        <p><?php echo htmlspecialchars($accountDeleteError, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <form action="/logout.php" method="post">
        <button type="submit">ログアウト</button>
    </form>

    <form action="/account_delete.php" method="post">
        <button type="submit">アカウントを削除する</button>
    </form>
</body>
</html>

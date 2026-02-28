<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

requireLogin();
$user = currentUser();

if ($user === null) {
    header('Location: /index.php');
    exit;
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

    <form action="/logout.php" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
        <button type="submit">ログアウト</button>
    </form>
</body>
</html>

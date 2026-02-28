<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

requireLogin();
$user = currentUser();

if ($user === null) {
    redirectWithLocationHeader('/index.php');
}

$inquiries = listInquiries(100);
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inquiries</title>
</head>
<body>
    <h1>お問い合わせ一覧（社内向け）</h1>
    <p>ログイン中: <?php echo htmlspecialchars((string) $user['display_name'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p><a href="/home.php">ホームに戻る</a></p>

    <hr>

    <?php if ($inquiries === []): ?>
        <p>まだお問い合わせはありません。</p>
    <?php else: ?>
        <?php foreach ($inquiries as $inquiry): ?>
            <p><?php echo (int) $inquiry['id']; ?>: <?php echo (string) $inquiry['name']; ?> (<?php echo (string) $inquiry['created_at']; ?>)</p>
            <p><?php echo nl2br((string) $inquiry['message']); ?></p>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>

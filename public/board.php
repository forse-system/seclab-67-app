<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

requireLogin();
$user = currentUser();

if ($user === null) {
    header('Location: /index.php');
    exit;
}

$postError = '';
if (isset($_SESSION['post_error']) && is_string($_SESSION['post_error'])) {
    $postError = $_SESSION['post_error'];
    unset($_SESSION['post_error']);
}

$posts = listPosts(100);
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Board</title>
</head>
<body>
    <h1>掲示板</h1>
    <p>ログイン中: <?php echo htmlspecialchars((string) $user['display_name'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p><a href="/home.php">ホームに戻る</a></p>

    <?php if ($postError !== ''): ?>
        <p><?php echo htmlspecialchars($postError, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <form action="/post_create.php" method="post">
        <p>
            <label for="body">本文</label><br>
            <textarea id="body" name="body" rows="5" cols="60" required></textarea>
        </p>
        <button type="submit">投稿</button>
    </form>

    <hr>

    <?php if ($posts === []): ?>
        <p>まだ投稿はありません。</p>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <p><?php echo (int) $post['id']; ?>: <?php echo htmlspecialchars((string) $post['display_name'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo htmlspecialchars((string) $post['created_at'], ENT_QUOTES, 'UTF-8'); ?>)</p>
            <p><?php echo nl2br(htmlspecialchars((string) $post['body'], ENT_QUOTES, 'UTF-8')); ?></p>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>

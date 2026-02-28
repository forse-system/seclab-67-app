<?php

declare(strict_types=1);

function createPost(int $userId, string $body): bool
{
    $trimmedBody = trim($body);

    if ($trimmedBody === '') {
        return false;
    }

    $bodyLength = function_exists('mb_strlen') ? mb_strlen($trimmedBody) : strlen($trimmedBody);

    if ($bodyLength > 2000) {
        return false;
    }

    $stmt = db()->prepare(
        'INSERT INTO posts (user_id, body, created_at)
         VALUES (:user_id, :body, :created_at)'
    );

    return $stmt->execute([
        ':user_id' => $userId,
        ':body' => $trimmedBody,
        ':created_at' => date('Y/m/d H:i:s'),
    ]);
}

function listPosts(int $limit = 100): array
{
    $safeLimit = max(1, min($limit, 200));

    $stmt = db()->prepare(
        'SELECT
            posts.id,
            posts.body,
            posts.created_at,
            users.display_name
         FROM posts
         INNER JOIN users ON users.id = posts.user_id
         ORDER BY posts.id DESC
         LIMIT :limit'
    );
    $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}

<?php

declare(strict_types=1);

function createInquiry(string $name, string $message): bool
{
    $trimmedName = trim($name);
    $trimmedMessage = trim($message);

    if ($trimmedName === '' || $trimmedMessage === '') {
        return false;
    }

    $nameLength = function_exists('mb_strlen') ? mb_strlen($trimmedName) : strlen($trimmedName);
    $messageLength = function_exists('mb_strlen') ? mb_strlen($trimmedMessage) : strlen($trimmedMessage);

    if ($nameLength > 100 || $messageLength > 5000) {
        return false;
    }

    $stmt = db()->prepare(
        'INSERT INTO inquiries (name, message, created_at)
         VALUES (:name, :message, :created_at)'
    );

    return $stmt->execute([
        ':name' => $trimmedName,
        ':message' => $trimmedMessage,
        ':created_at' => date('Y/m/d H:i:s'),
    ]);
}

function listInquiries(int $limit = 100): array
{
    $safeLimit = max(1, min($limit, 200));

    $stmt = db()->prepare(
        'SELECT id, name, message, created_at
         FROM inquiries
         ORDER BY id DESC
         LIMIT :limit'
    );
    $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}

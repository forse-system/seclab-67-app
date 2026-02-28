<?php

declare(strict_types=1);

$basePath = __DIR__ . '/../';
$dataDir = $basePath . 'data';
$databasePath = $dataDir . '/app.sqlite';

if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}

$pdo = new PDO('sqlite:' . $databasePath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec(
    'CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password_hash TEXT NOT NULL,
        display_name TEXT NOT NULL,
        created_at TEXT NOT NULL
    )'
);

$pdo->exec(
    'CREATE TABLE IF NOT EXISTS posts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        body TEXT NOT NULL,
        created_at TEXT NOT NULL,
        FOREIGN KEY(user_id) REFERENCES users(id)
    )'
);

$pdo->exec(
    'CREATE TABLE IF NOT EXISTS inquiries (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        message TEXT NOT NULL,
        created_at TEXT NOT NULL
    )'
);

$seedUsername = 'admin';
$seedPassword = 'Admin#1234';
$seedDisplayName = 'Administrator';

$stmt = $pdo->prepare('SELECT id FROM users WHERE username = :username LIMIT 1');
$stmt->execute([':username' => $seedUsername]);
$existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$existingUser) {
    $insert = $pdo->prepare(
        'INSERT INTO users (username, password_hash, display_name, created_at)
         VALUES (:username, :password_hash, :display_name, :created_at)'
    );

    $insert->execute([
        ':username' => $seedUsername,
        ':password_hash' => password_hash($seedPassword, PASSWORD_DEFAULT),
        ':display_name' => $seedDisplayName,
        ':created_at' => date('c'),
    ]);

    echo "Database initialized.\n";
    echo "Seed user created: {$seedUsername} / {$seedPassword}\n";
} else {
    echo "Database initialized.\n";
    echo "Seed user already exists: {$seedUsername}\n";
}

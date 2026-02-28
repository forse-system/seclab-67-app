<?php

declare(strict_types=1);

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']) && is_int($_SESSION['user_id']);
}

function attemptLogin(string $username, string $password): bool
{
    $trimmedUsername = trim($username);

    if ($trimmedUsername === '' || $password === '') {
        return false;
    }

    $stmt = db()->prepare('SELECT id, username, display_name, password_hash FROM users WHERE username = :username LIMIT 1');
    $stmt->execute([':username' => $trimmedUsername]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, (string) $user['password_hash'])) {
        return false;
    }

    session_regenerate_id(true);
    $_SESSION['user_id'] = (int) $user['id'];

    return true;
}

function currentUser(): ?array
{
    if (!isLoggedIn()) {
        return null;
    }

    $stmt = db()->prepare('SELECT id, username, display_name FROM users WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => (int) $_SESSION['user_id']]);
    $user = $stmt->fetch();

    if (!$user) {
        logoutUser();
        return null;
    }

    return $user;
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: /index.php');
        exit;
    }
}

function logoutUser(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            (bool) $params['secure'],
            (bool) $params['httponly']
        );
    }

    session_destroy();
}

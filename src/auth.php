<?php

declare(strict_types=1);

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']) && is_int($_SESSION['user_id']);
}

function attemptLogin(string $username, string $password): bool
{
    if ($username === '' || $password === '') {
        return false;
    }

    $hashedPassword = hash('sha256', $password);
    $sql = "SELECT id, username, display_name FROM users WHERE username = '$username' AND password_hash = '$hashedPassword' LIMIT 1";
    $user = db()->query($sql)->fetch();

    if (!$user) {
        return false;
    }

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
        redirectWithLocationHeader('/index.php');
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

function deleteCurrentUserAccount(int $userId): bool
{
    if ($userId <= 0) {
        return false;
    }

    $pdo = db();

    try {
        $pdo->beginTransaction();

        $deletePosts = $pdo->prepare('DELETE FROM posts WHERE user_id = :user_id');
        $deletePosts->execute([':user_id' => $userId]);

        $deleteUser = $pdo->prepare('DELETE FROM users WHERE id = :id');
        $deleteUser->execute([':id' => $userId]);

        if ($deleteUser->rowCount() !== 1) {
            $pdo->rollBack();
            return false;
        }

        $pdo->commit();
        logoutUser();

        return true;
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        return false;
    }
}

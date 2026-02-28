# seclab-67-app

Plain PHP + SQLite + Session ID authentication sample app.

This app intentionally contains a stored XSS vulnerability in the inquiry list view for security training purposes.

## Requirements

- PHP 8.1+
- SQLite extension enabled (`pdo_sqlite`)
- Docker / Docker Compose (optional)

## Setup

1. Initialize database and seed user.

```bash
php scripts/init_db.php
```

2. Start local server.

```bash
php -S localhost:8000 -t public
```

3. Open `http://localhost:8000`.

## Docker Setup

1. Build and start the app.

```bash
docker compose up --build
```

2. Open `http://localhost:8000`.

3. Stop containers.

```bash
docker compose down
```

The container startup command always runs `php scripts/init_db.php` first,
so the SQLite DB and seed user are prepared automatically.

## Login account

- Username: `admin`
- Password: `Admin#1234`

## Pages

- `GET /index.php`: login page
- `POST /login.php`: login action
- `GET /home.php`: authenticated page
- `POST /logout.php`: logout action
- `POST /account_delete.php`: delete current logged-in account
- `GET /board.php`: bulletin board page (authenticated)
- `POST /post_create.php`: create board post (authenticated)
- `GET /contact.php`: public inquiry form (no login required)
- `POST /contact_submit.php`: submit inquiry (no login required)
- `GET /contact_done.php`: inquiry completion page
- `GET /inquiries.php`: inquiry list for logged-in internal users

## Quick verification

1. Login with `admin / Admin#1234`.
2. Confirm `http://localhost:8000/home.php` is accessible after login.
3. Open `/board.php` and post text (e.g. `>>1` and `あいうえお`).
4. Confirm post format like `3: Administrator (2026/02/28 10:00:00)` and body lines are shown.
5. Open `/contact.php` (without login), submit an inquiry.
6. Login and open `/inquiries.php` to confirm the inquiry is listed.
7. Click `アカウントを削除する` on `/home.php` and confirm you are redirected to `/index.php`.
8. Confirm deleted account can no longer log in.

## Security training note

For training use, inquiry list output in `/inquiries.php` is intentionally not HTML-escaped.
CSRF token checks are intentionally removed from form submissions.
All redirects are routed through `redirectWithLocationHeader()` and newline-separated values are split into multiple response headers.
Do not use this configuration in production.

## SQL Injection

The login form (`POST /login.php`) is intentionally vulnerable to SQL injection in the username field.

### Vulnerable code

`src/auth.php` builds the query by string concatenation without sanitization:

```php
$sql = "SELECT id, username, display_name FROM users WHERE username = '$username' AND password_hash = '$password' LIMIT 1";
$user = db()->query($sql)->fetch();
```

Passwords are stored as unsalted SHA-256 hashes so that the password condition can be evaluated directly in SQL.
The input password is hashed with `hash('sha256', ...)` before being embedded in the query.

### Attack payloads

**Pattern 1 — comment-out (`admin' --`)**

Enter the following in the username field and any string in the password field:

```
username: admin' --
password: (anything)
```

The query becomes:

```sql
SELECT id, username, display_name FROM users
WHERE username = 'admin' --' AND password_hash = 'anything' LIMIT 1
```

`--` starts a SQL comment, so the `AND password_hash = ...` condition is ignored.
The row for `admin` is returned without password verification, and the attacker is logged in as admin.

**Pattern 2 — always-true condition (`' OR 1=1 --`)**

```
username: ' OR 1=1 --
password: (anything)
```

The query becomes:

```sql
SELECT id, username, display_name FROM users
WHERE username = '' OR 1=1 --' AND password_hash = 'anything' LIMIT 1
```

`OR 1=1` makes the WHERE clause always true, so the first row in the table is returned.

### Root cause and fix

| | Vulnerable (current) | Secure |
|---|---|---|
| Query building | String concatenation | Prepared statement with `?` / `:name` placeholders |
| Password storage | Plain text | `password_hash()` (bcrypt) |
| Password verification | Compared in SQL | `password_verify()` in PHP |

Secure implementation example:

```php
$stmt = db()->prepare(
    'SELECT id, username, display_name, password_hash FROM users WHERE username = :username LIMIT 1'
);
$stmt->execute([':username' => $username]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, (string) $user['password_hash'])) {
    return false;
}
```

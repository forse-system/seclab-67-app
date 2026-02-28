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
Do not use this configuration in production.

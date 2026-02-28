# seclab-67-app

Plain PHP + SQLite + Session ID authentication sample app.

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
- `GET /board.php`: bulletin board page (authenticated)
- `POST /post_create.php`: create board post (authenticated)

## Quick verification

1. Login with `admin / Admin#1234`.
2. Confirm `http://localhost:8000/home.php` is accessible after login.
3. Open `/board.php` and post text (e.g. `>>1` and `あいうえお`).
4. Confirm post format like `3: Administrator (2026/02/28 10:00:00)` and body lines are shown.
5. Logout and confirm `/home.php` redirects to `/index.php`.

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

## Quick verification

1. Login with `admin / Admin#1234`.
2. Confirm `http://localhost:8000/home.php` is accessible after login.
3. Logout and confirm `/home.php` redirects to `/index.php`.

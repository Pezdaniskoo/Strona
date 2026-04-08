# ToDo List – Backend + Admin Panel (Laravel 11)

## One-command start (bez `docker exec`)

Po prostu odpal:

```bash
docker compose up -d --build
```

I to wszystko ✅

Co dzieje się automatycznie:
- kopiowanie `.env` (jeśli nie istnieje),
- `composer install`,
- `npm install`,
- `npm run build`,
- `php artisan key:generate`,
- `php artisan migrate --seed`,
- start PHP-FPM,
- start workera kolejki,
- start schedulera.

## Adresy
- Aplikacja: http://localhost:8000
- Mailpit (podgląd maili): http://localhost:8025

## Konto admina (seed)
- email: `admin@todo-list.local`
- hasło: `Admin123!`

## Zatrzymanie
```bash
docker compose down
```

## Reset danych (wraz z bazą)
```bash
docker compose down -v
docker compose up -d --build
```

## Podgląd logów
```bash
docker compose logs -f app
docker compose logs -f queue
docker compose logs -f scheduler
```

# ToDo List – Backend + Admin Panel (Laravel 11)

## Start (jedna komenda, zero ręcznej roboty)

Uruchom tylko to:

```bash
docker compose up -d --build
```

I koniec — nic więcej nie musisz robić.

Po starcie aplikacja będzie gotowa na:
- GUI / panel: **http://localhost:8080**
- podgląd maili: **http://localhost:8025**

## Co dzieje się automatycznie

Przy starcie kontenerów system sam wykona:
- utworzenie `.env` z `.env.example` (jeśli brak),
- `composer install`,
- `npm install`,
- `npm run build`,
- `php artisan key:generate`,
- `php artisan migrate --seed`,
- uruchomienie `php-fpm`, Nginx, MySQL, Redis, Mailpit,
- uruchomienie `queue:work` i `schedule:work`.

## Konto administratora
- email: `admin@todo-list.local`
- hasło: `Admin123!`

## Dodatkowe komendy (opcjonalnie)

```bash
# stop
docker compose down

# twardy reset danych
docker compose down -v
docker compose up -d --build

# logi
docker compose logs -f app
docker compose logs -f queue
docker compose logs -f scheduler
docker compose logs -f nginx
```

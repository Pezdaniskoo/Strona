# ToDo List – Laravel 11 Backend + Admin

## Uruchomienie (jedna komenda)

```bash
docker compose up -d --build
```

Po starcie:
- Aplikacja: `http://localhost:8080`
- Mailpit: `http://localhost:8025`

## Ważne przy aktualizacji po błędach typu `vendor/autoload.php`

Jeżeli wcześniej były uruchomienia z niepełnym volume/cache, zrób twardy reset:

```bash
docker compose down -v --remove-orphans
docker compose build --no-cache
docker compose up -d
```

Ta konfiguracja **nie montuje kodu jako bind-volume** do kontenera app/queue/scheduler, dzięki czemu nie gubi `vendor/` i jest stabilna na Windows/macOS/Linux.

## Konto administratora

- email: `admin@todo-list.local`
- hasło: `Admin123!`

## Logi diagnostyczne

```bash
docker compose logs -f app
docker compose logs -f mysql
```


## Compose warning fix

Usunąłem przestarzałe pole `version` z `docker-compose.yml`, więc warning Compose v2 o `version is obsolete` nie powinien już występować.

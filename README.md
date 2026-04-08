# ToDo List – Backend + Admin Panel (Laravel 11)

## Start (jedna komenda, bez `docker exec`)

```bash
docker compose up -d --build
```

Po uruchomieniu otwórz:
- GUI / aplikacja: **http://localhost:8080**
- Mailpit: **http://localhost:8025**

## Ważne
Przy **pierwszym uruchomieniu** kontener sam:
- dociąga brakujący szkielet Laravel 11 (jeśli repo jeszcze go nie ma),
- instaluje composer/npm dependencies,
- buduje assety,
- generuje APP_KEY,
- uruchamia migracje i seedy.

To może potrwać kilka minut.

## Konto administratora
- email: `admin@todo-list.local`
- hasło: `Admin123!`

## Jeśli strona nie odpowiada od razu
Sprawdź logi i poczekaj aż bootstrap się skończy:

```bash
docker compose logs -f app
docker compose logs -f queue
docker compose logs -f scheduler
```

## Komendy pomocnicze

```bash
# stop
docker compose down

# pełny reset danych
docker compose down -v
docker compose up -d --build
```

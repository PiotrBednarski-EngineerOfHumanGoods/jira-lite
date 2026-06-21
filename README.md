# Jira-lite

Mini-system zarządzania projektami i zadaniami. Każdy projekt ma członków, każde zadanie ma status, priorytet, opcjonalne tagi i załączniki. Wszystkie zmiany są logowane w historii (audit log).

Projekt zaliczeniowy z PHP. Docelowa ocena: **5.0**.

## Stack

- PHP 8.3 + Laravel 13
- SQLite jako baza
- Blade (silnik szablonów z dziedziczeniem layoutu)
- Laravel Breeze (logowanie/rejestracja, bcrypt)
- Tailwind CSS z CDN + customowa paleta (Space Grotesk, JetBrains Mono)
- Alpine.js do interakcji w UI

## Uruchomienie

Wymagane: PHP 8.2+ z rozszerzeniami `pdo_sqlite, mbstring, openssl, curl, fileinfo, zip, gd` oraz Composer 2.x.

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```

Aplikacja: `http://127.0.0.1:8000`

## Konta testowe (hasło: `password`)

| Email | Rola |
|-------|------|
| admin@jira.test | admin |
| manager@jira.test | manager |
| jan@jira.test, ewa@jira.test, piotr@jira.test, karolina@jira.test | developer |

Role mają różne uprawnienia: tworzenie projektów i tagów wymaga managera, usuwanie projektu wymaga admina lub jego twórcy.

## Spełnione kryteria

### 3.0
- Pełny CRUD na zasobach Projekty, Zadania, Tagi (kontrolery w `app/Http/Controllers/`)
- Walidacja po stronie serwera w klasach `FormRequest` (`app/Http/Requests/`)
- Po błędzie walidacji formularz zachowuje wartości (`old()`)
- Tabele z rekordami (np. `resources/views/projects/index.blade.php`)
- Pełna separacja MVC (kontroler, model, widok)

### 3.5
- Dane w bazie SQLite (`database/database.sqlite`), schemat w `database/schema.sql`
- 8 powiązanych tabel z foreign keys + cascade delete
- Upload plików (max 5 plików x 5 MB) jako załączniki do zadań
- Usunięcie zadania kasuje pliki z dysku (observer `Attachment::deleting` w `app/Models/Attachment.php`)
- Dashboard z metrykami i progress barami (`resources/views/dashboard.blade.php`)

### 4.0
- Logowanie i rejestracja przez Breeze, hasła hashowane bcrypt
- Cała aplikacja chroniona middlewarem `auth`
- Preferencje użytkownika (sortowanie domyślne projektów) zapisywane w `users.preferences` JSON, ustawiane w `/profile`
- Komunikaty flash w `<x-flash>`, auto-znikają po 5s
- Wszystkie POST kończą się `redirect()` (Post/Redirect/Get)

### 4.5
- 8 tabel w bazie, w tym 2 relacje many-to-many:
  - `users` <-> `projects` przez `project_user` (z dodatkowym polem `project_role`)
  - `tasks` <-> `tags` przez `task_tag`
- 3 role: admin, manager, developer (middleware `EnsureRole` + `ProjectPolicy`, `TaskPolicy`)
- Wyszukiwanie + filtrowanie po wielu kryteriach na listach projektów i zadań
- Paginacja (`paginate(10)`, `paginate(15)`)
- Sortowanie po kolumnach (komponent `<x-sortable-th>`)
- Eksport CSV projektów z aktywnymi filtrami (`/projects-export/csv`)
- Czytelne strony błędów 403/404/419/500 w `resources/views/errors/`

### 5.0
- Composer jako menedżer zależności (`composer.json`)
- Blade z dziedziczeniem layoutu (`<x-app-layout>` + slots)
- Architektura MVC + dodatkowo FormRequest, Policy, Middleware, Trait
- REST API zwracające JSON:
  - `GET /api/projects?status=active`
  - `GET /api/projects/{id}`
  - `GET /api/tasks?priority=high`
  - `GET /api/tasks/{id}`
  - `GET /api/health`
- Historia zmian: tabela `audit_logs` + trait `Auditable` (`app/Traits/Auditable.php`) rejestruje kto/co/kiedy/jakie pola zmienił przy każdym create/update/delete
- Konfiguracja wrażliwa w `.env` (plik w `.gitignore`), wzorzec w `.env.example`
- Repozytorium Git z czytelnym podziałem na commity

## Struktura bazy

Tabele: `users`, `projects`, `project_user` (pivot m:n), `tasks`, `tags`, `task_tag` (pivot m:n), `attachments`, `audit_logs`.

Pełny dump DDL: `database/schema.sql`.

## Architektura

```
app/
  Http/Controllers/        CRUD: Project, Task, Tag, Attachment, Dashboard, UserPreference
    Api/                   ProjectApiController, TaskApiController (JSON)
  Http/Requests/           FormRequest z walidacją
  Http/Middleware/         EnsureRole
  Models/                  User, Project, Task, Tag, Attachment, AuditLog
  Policies/                ProjectPolicy, TaskPolicy
  Traits/                  Auditable

resources/views/
  layouts/                 app, guest, navigation
  components/              flash, sortable-th, status-badge, page-header + Breeze defaults
  projects/, tasks/, tags/
  auth/, profile/          Breeze + edytowane
  errors/                  strony 403, 404, 419, 500
  dashboard.blade.php

routes/
  web.php                  CRUD + dashboard
  api.php                  JSON endpoints
  auth.php                 Breeze
```

## Uwagi techniczne

- Tailwind ładowany z CDN (`cdn.tailwindcss.com`). Konfiguracja palety i fontów (Space Grotesk, JetBrains Mono) inline w `<script>` w `resources/views/layouts/app.blade.php`. Aby budować lokalnie: `npm install && npm run build`, potem zamień `<script src="cdn.tailwindcss.com">` na `@vite([...])`.
- Załączniki trafiają do `storage/app/public/attachments/` i są dostępne przez symlink `public/storage`.
- Każda operacja CRUD na modelach `Project` lub `Task` automatycznie zapisuje wpis w `audit_logs` (user_id, action, zmienione pola w JSON, timestamp).

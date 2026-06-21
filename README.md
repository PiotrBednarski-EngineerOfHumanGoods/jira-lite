# Jira-lite

Mini-system zarządzania projektami i zadaniami. Każdy projekt ma członków, każde zadanie ma status, priorytet, opcjonalne tagi i załączniki. Wszystkie zmiany są logowane w historii.

Projekt zaliczeniowy z PHP — celuje w **ocenę 5.0**.

## Stack

- **PHP 8.3** + **Laravel 13**
- **SQLite** jako baza
- **Blade** — silnik szablonów z dziedziczeniem layoutu
- **Laravel Breeze** — gotowy moduł logowania/rejestracji (hashowanie bcrypt)
- **Tailwind CSS** ładowany z CDN + customowa paleta (neobrutalism)
- **Alpine.js** do interakcji w UI

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

Role mają różne uprawnienia: tworzenie projektów / tagów wymaga managera, usuwanie projektu — admina lub twórcy.

## Spełnione kryteria — co i gdzie

### 3.0 — CRUD, OOP, walidacja, prezentacja
- CRUD na zasobach **Projekty**, **Zadania**, **Tagi** — kontrolery w `app/Http/Controllers/`
- Walidacja po stronie serwera — klasy `FormRequest` w `app/Http/Requests/` (np. `ProjectStoreRequest`)
- Wartości w formularzu po błędzie zachowuje `old()` — patrz np. `resources/views/projects/_form.blade.php`
- Tabele z rekordami — `resources/views/projects/index.blade.php`
- Pełna separacja MVC (kontroler → model → widok)

### 3.5 — baza, pliki, dashboard
- Dane w bazie **SQLite** (`database/database.sqlite`), schemat w `database/schema.sql`
- 8 powiązanych tabel z FK + cascade delete
- Upload plików (max 5 × 5MB) jako załączniki do zadań — `Task → Attachment`
- Usunięcie zadania kasuje pliki z dysku (observer `Attachment::deleting` w `app/Models/Attachment.php`)
- Dashboard z metrykami i progress barami — `resources/views/dashboard.blade.php`

### 4.0 — auth, preferencje, flash, PRG
- Logowanie/rejestracja przez Breeze, hasła hashowane bcrypt (`User::$casts` → `'password' => 'hashed'`)
- Cała aplikacja za `auth` middleware
- Preferencje użytkownika (sortowanie domyślne projektów) zapisywane w `users.preferences` JSON — `/profile`
- Komunikaty flash w `<x-flash>` — auto-znikają po 5s
- Wszystkie POST kończą się `redirect()` (Post/Redirect/Get)

### 4.5 — relacje, role, filtry, eksport
- **8 tabel** w bazie, w tym **2 relacje many-to-many**:
  - `users ↔ projects` przez `project_user` (z dodatkowym polem `project_role`)
  - `tasks ↔ tags` przez `task_tag`
- 3 role: admin / manager / developer — middleware `EnsureRole` + `ProjectPolicy`, `TaskPolicy`
- Wyszukiwanie + filtrowanie po wielu kryteriach na listach projektów i zadań
- Paginacja (`paginate(10)` / `paginate(15)`)
- Sortowanie po kolumnach (komponent `<x-sortable-th>`)
- **Eksport CSV** projektów z aktywnymi filtrami — `/projects-export/csv`

### 5.0 — framework, MVC, API, audit log
- **Composer** jako menedżer zależności (`composer.json`)
- Silnik szablonów **Blade** z dziedziczeniem — `<x-app-layout>` + slots
- Architektura **MVC** (Models / Controllers / Views) + dodatkowo FormRequest, Policy, Middleware, Trait
- **REST API zwracające JSON**:
  - `GET /api/projects?status=active`
  - `GET /api/projects/{id}`
  - `GET /api/tasks?priority=high`
  - `GET /api/tasks/{id}`
  - `GET /api/health`
- **Historia zmian** — tabela `audit_logs` + trait `Auditable` (`app/Traits/Auditable.php`) rejestruje kto/co/kiedy/jakie pola zmienił przy każdym create/update/delete
- Konfiguracja wrażliwa w `.env` (w `.gitignore`), wzorzec w `.env.example`
- Repozytorium Git z czytelnym podziałem na commity

## Struktura bazy

Tabele: `users`, `projects`, `project_user` (pivot m:n), `tasks`, `tags`, `task_tag` (pivot m:n), `attachments`, `audit_logs`.

Pełny dump DDL: **`database/schema.sql`**.

## Architektura

```
app/
├── Http/Controllers/        # CRUD: Project, Task, Tag, Attachment, Dashboard, UserPreference
│   └── Api/                 # ProjectApiController, TaskApiController (JSON)
├── Http/Requests/           # FormRequest z walidacją
├── Http/Middleware/         # EnsureRole
├── Models/                  # User, Project, Task, Tag, Attachment, AuditLog
├── Policies/                # ProjectPolicy, TaskPolicy
└── Traits/                  # Auditable

resources/views/
├── layouts/                 # app, guest, navigation
├── components/              # flash, sortable-th, status-badge, page-header + Breeze defaults
├── projects/, tasks/, tags/
├── auth/, profile/          # Breeze + własne edycje
└── dashboard.blade.php

routes/
├── web.php                  # CRUD + dashboard
├── api.php                  # JSON endpoints
└── auth.php                 # Breeze
```

## Uwagi

- **Tailwind ładowany z CDN** (`cdn.tailwindcss.com`) — wymaga internetu przy ładowaniu. Configuracja palety i fontów (Space Grotesk, JetBrains Mono) w `<script>` w `resources/views/layouts/app.blade.php`. Aby budować lokalnie: `npm install && npm run build` i podmienić `<script src="cdn.tailwindcss.com">` na `@vite([...])`.
- Załączniki trafiają do `storage/app/public/attachments/` i są dostępne przez symlink `public/storage`.
- Każda operacja CRUD na `Project` lub `Task` automatycznie tworzy wpis w `audit_logs` (user_id, action, zmienione pola w JSON, timestamp).

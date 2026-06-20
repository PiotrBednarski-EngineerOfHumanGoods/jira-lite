# Jira-lite — mini-system zarządzania projektami

Aplikacja webowa do zarządzania projektami i zadaniami zespołu. Każdy projekt ma członków, każde zadanie ma status, priorytet, opcjonalne tagi i załączniki. System rejestruje pełną historię zmian.

**Docelowa ocena: 5.0**

---

## Stack

- **PHP 8.3** + **Laravel 13** (framework MVC)
- **SQLite** (baza danych — zero konfiguracji)
- **Blade** (silnik szablonów z dziedziczeniem layoutu)
- **Laravel Breeze** (system logowania/rejestracji z bcrypt)
- **Tailwind CSS 3** (style, ładowane z CDN dla szybkiego uruchomienia)
- **Alpine.js** (interaktywność po stronie klienta)
- **Composer** (menedżer pakietów PHP)

## Spełnione kryteria (5.0)

### 3.0
- Pełny CRUD na zasobach: **Projekty**, **Zadania**, **Tagi**
- Kod obiektowy (Eloquent ORM, modele, kontrolery, FormRequest, Policy, Trait)
- Formularze z wieloma typami pól (text, textarea, date, select, checkbox, file, color) i walidacją po stronie serwera (`FormRequest`)
- Po błędzie walidacji formularz zachowuje wartości (`old()`)
- Wyświetlanie rekordów w tabelach
- Logika oddzielona od prezentacji (kontrolery / widoki / modele)
- Estetyczny wygląd (Tailwind, własne komponenty Blade)

### 3.5
- Dane w **bazie danych SQLite** (nie w plikach)
- Powiązane tabele (foreign keys + cascade delete)
- Przesyłanie plików powiązanych z zadaniem (załączniki, max 5 plików × 5 MB)
- Usunięcie zadania usuwa pliki z dysku (`Attachment::deleting` observer)
- Automatyczny zapis daty utworzenia (`timestamps()`)
- Panel podsumowujący dane — Dashboard z metrykami i wykresami progress bar

### 4.0
- Logowanie / rejestracja (Laravel Breeze, hasła w bcrypt)
- Dostęp tylko po zalogowaniu (`auth` middleware na wszystkich route)
- Zapamiętywanie preferencji użytkownika (kolumna `users.preferences` JSON: domyślne sortowanie projektów, motyw)
- Komunikaty zwrotne (flash session) wyświetlane jednokrotnie, auto-znikające
- Redirect po POST (Post/Redirect/Get) — odświeżenie nie wysyła ponownie formularza

### 4.5
- **8 tabel** w bazie + dwie relacje **many-to-many**:
  - `users` ↔ `projects` przez `project_user` (z dodatkowym polem `project_role`)
  - `tasks` ↔ `tags` przez `task_tag`
- **Role użytkowników**: `admin`, `manager`, `developer` — różne uprawnienia (middleware `role:`, Policies)
- Wyszukiwanie i filtrowanie po wielu kryteriach (status, priorytet, projekt, członek, tag, fraza tekstowa)
- Paginacja (`paginate(10)` / `paginate(15)`)
- Sortowanie po klikalnych kolumnach tabeli (komponent `<x-sortable-th>`)
- Eksport projektów do CSV (`/projects-export/csv`) z aktywnymi filtrami
- Czytelne komunikaty błędów (FormRequest messages po polsku)

### 5.0
- Composer jako menedżer pakietów
- Silnik szablonów Blade z dziedziczeniem (`<x-app-layout>`, slots, komponenty)
- Komponenty z popularnego frameworka — **cały Laravel** (Eloquent, Blade, Validation, Auth, Routing)
- Architektura **MVC**: Models / Controllers / Views, dodatkowo FormRequest, Policy, Middleware, Traits
- **API JSON**: `GET /api/projects`, `GET /api/projects/{id}`, `GET /api/tasks`, `GET /api/tasks/{id}`, `GET /api/health`
- **Historia zmian** — tabela `audit_logs` + trait `Auditable` rejestrujący każde create/update/delete (kto, co, kiedy, jakie pola)
- Konfiguracja wrażliwa poza kodem — `.env` (w `.gitignore`), wzorzec w `.env.example`
- Repozytorium Git z instrukcją uruchomienia (ten plik)

---

## Wymagania

- PHP **8.2+** z rozszerzeniami: `pdo_sqlite`, `mbstring`, `openssl`, `curl`, `fileinfo`, `zip`, `gd`
- Composer 2.x
- (opcjonalnie) Node.js 20+ — tylko jeśli chcesz lokalnie budować Tailwind zamiast używać CDN

## Uruchomienie

```bash
# 1. Pobierz / sklonuj projekt
cd jira-lite

# 2. Instalacja zależności PHP
composer install

# 3. Konfiguracja środowiska
cp .env.example .env
php artisan key:generate

# 4. Stworzenie bazy i wypełnienie danymi testowymi
php artisan migrate:fresh --seed

# 5. Linkowanie storage (dla załączników)
php artisan storage:link

# 6. Uruchomienie serwera deweloperskiego
php artisan serve
```

Aplikacja: **http://127.0.0.1:8000**

## Konta testowe (po `--seed`)

Hasło dla wszystkich: **`password`**

| Email | Rola | Uprawnienia |
|-------|------|-------------|
| admin@jira.test | admin | wszystko, w tym usuwanie cudzych projektów |
| manager@jira.test | manager | tworzenie projektów, zarządzanie tagami |
| jan@jira.test | developer | edycja swoich zadań, dodawanie załączników |
| ewa@jira.test | developer | jw. |
| piotr@jira.test | developer | jw. |
| karolina@jira.test | developer | jw. |

## Struktura bazy danych

Patrz: **`database/schema.sql`** — pełny eksport schematu (DDL).

Tabele:
- `users` — użytkownicy + rola + preferencje (JSON)
- `projects` — projekty
- `project_user` — m:n użytkownicy↔projekty (z `project_role`)
- `tasks` — zadania powiązane z projektami
- `tags` — tagi z kolorem
- `task_tag` — m:n zadania↔tagi
- `attachments` — pliki przypięte do zadań
- `audit_logs` — historia zmian (polimorficzna)

## Endpointy API (JSON)

Wymagana autoryzacja przez sesję (najpierw zaloguj się w aplikacji):

```
GET  /api/health                 # status + timestamp
GET  /api/projects?status=active # lista projektów (paginowana)
GET  /api/projects/{id}          # szczegóły + zadania
GET  /api/tasks?priority=high    # lista zadań (filtry: status, priority, project_id, assigned_to, q)
GET  /api/tasks/{id}             # szczegóły + załączniki
```

## Architektura

```
app/
├── Http/
│   ├── Controllers/        # ProjectController, TaskController, DashboardController,
│   │   │                   # AttachmentController, TagController, UserPreferenceController
│   │   └── Api/            # ProjectApiController, TaskApiController
│   ├── Requests/           # FormRequest z walidacją serwerową
│   └── Middleware/         # EnsureRole
├── Models/                 # User, Project, Task, Tag, Attachment, AuditLog
├── Policies/               # ProjectPolicy, TaskPolicy
└── Traits/                 # Auditable (historia zmian)

resources/views/
├── layouts/                # app.blade.php, guest.blade.php, navigation.blade.php
├── components/             # flash, sortable-th, status-badge, page-header
├── projects/               # index, create, edit, show, _form
├── tasks/                  # index, create, edit, show, _form
├── tags/                   # index, create, edit
└── dashboard.blade.php

routes/
├── web.php                 # interfejs webowy
├── api.php                 # endpointy JSON
└── auth.php                # Breeze (login, register, password reset)
```

## Notatki

- **Tailwind ładowany z CDN** (`cdn.tailwindcss.com`) — wymaga połączenia z internetem przy pierwszym ładowaniu. Aby pre-buildować lokalnie: `npm install && npm run build`, a następnie podmień `<script src="https://cdn.tailwindcss.com...">` z powrotem na `@vite([...])` w `resources/views/layouts/app.blade.php` i `guest.blade.php`.
- Załączniki trafiają do `storage/app/public/attachments/` i są dostępne przez symlink `public/storage`.
- Każda operacja CRUD na Project/Task wpisuje się do `audit_logs` (kto + co + kiedy + zmienione pola w JSON).

# Jira-lite

Mini-system zarządzania projektami i zadaniami w Laravel: projekty z członkami i rolami, zadania ze statusem, priorytetem, tagami i załącznikami, pełna historia zmian (audit log) oraz JSON API. Projekt zaliczeniowy z PHP (PJATK, 2026).

`PHP 8.3` `Laravel 13` `Blade` `Breeze` `SQLite` `Tailwind CSS` `Alpine.js`

## Co robi

- **Projekty i zadania** — CRUD z walidacją w `FormRequest`, filtrowanie po wielu kryteriach, sortowanie po kolumnach, paginacja, eksport CSV z aktywnymi filtrami.
- **Role** — admin, manager, developer; uprawnienia przez middleware `EnsureRole` oraz `ProjectPolicy` / `TaskPolicy` (np. usunięcie projektu wymaga admina lub jego twórcy).
- **Relacje m:n** — użytkownicy ↔ projekty (z rolą w projekcie) i zadania ↔ tagi; 8 tabel z kluczami obcymi i kaskadowym usuwaniem.
- **Załączniki** — do 5 plików × 5 MB na zadanie; usunięcie zadania kasuje pliki z dysku (observer `Attachment::deleting`).
- **Audit log** — trait `Auditable` zapisuje kto, co, kiedy i jakie pola zmienił przy każdym create/update/delete.
- **JSON API** — `GET /api/projects?status=active`, `GET /api/projects/{id}`, `GET /api/tasks?priority=high`, `GET /api/tasks/{id}`, `GET /api/health`.
- **Dashboard** z metrykami i paskami postępu, preferencje użytkownika w JSON (`users.preferences`), własne strony błędów 403/404/419/500.

## Uruchomienie

Wymagane: PHP 8.2+ z rozszerzeniami `pdo_sqlite, mbstring, openssl, curl, fileinfo, zip, gd` oraz Composer 2.

```bash
composer install && cp .env.example .env && php artisan key:generate
php artisan migrate:fresh --seed && php artisan storage:link
php artisan serve        # http://127.0.0.1:8000
```

Konta testowe (hasło `password`): `admin@jira.test` (admin), `manager@jira.test` (manager), `jan@jira.test` (developer).

## Architektura

```
app/Http/Controllers/    CRUD: Project, Task, Tag, Attachment, Dashboard, UserPreference
  Api/                   ProjectApiController, TaskApiController (JSON)
app/Http/Requests/       walidacja FormRequest
app/Http/Middleware/     EnsureRole
app/Models/              User, Project, Task, Tag, Attachment, AuditLog
app/Policies/            ProjectPolicy, TaskPolicy
app/Traits/              Auditable
resources/views/         layouts, components (flash, sortable-th, status-badge), projects/, tasks/, tags/, errors/
routes/                  web.php (CRUD + dashboard), api.php (JSON), auth.php (Breeze)
database/schema.sql      pełny DDL 8 tabel
```

## Co zrobiłbym inaczej

- **API za Sanctum** — dziś endpointy JSON są otwarte; w produkcie dostałyby tokeny i rate limiting.
- **Testy Feature dla polityk** — uprawnienia to najłatwiejsze miejsce na regresję, a `phpunit.xml` wciąż czeka na testy.
- **Tailwind przez Vite zamiast CDN** — jeden build, bez zewnętrznego skryptu w produkcji.
- **Kolejka do sprzątania plików** — usuwanie załączników w observerze blokuje request; job w kolejce byłby czystszy.

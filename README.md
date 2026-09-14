# Client Project Tracker

A small full-stack technical assessment for a digital agency. The application provides a REST API and Vue interface for creating, viewing, editing, filtering, sorting, and deleting client projects.

## Stack

- Laravel 12 / PHP 8.3+
- PostgreSQL
- Vue 3 + TypeScript + Vite
- Pinia + Vue Router + Axios
- Docker Compose
- PHPUnit/Laravel feature tests

## Implemented Requirements

- GET `/projects`
- GET `/projects/{id}`
- POST `/projects`
- PUT `/projects/{id}`
- DELETE `/projects/{id}`
- Required client/project names
- Enum-backed status and priority validation
- Due date cannot be earlier than start date
- Meaningful validation errors (HTTP 422)
- Search by client/project name
- Filtering by status and priority
- Sorting
- Seeded assessment data (12 projects)
- Automated API tests
- Docker development setup

## Local Setup

### Backend

```bash
cd backend
cp .env.example .env
# install Composer dependencies
docker compose exec backend composer install
docker compose exec backend php artisan key:generate
docker compose exec backend php artisan migrate --seed
docker compose exec backend php artisan serve
```

### Frontend

```bash
cd frontend
npm install
npm run dev
```

The Vite dev server proxies `/projects` to `http://localhost:8000`.

## Docker

```bash
docker compose up --build
```

In another terminal:

```bash
docker compose exec backend php artisan key:generate
docker compose exec backend php artisan migrate --seed
```

Frontend: http://localhost:5173
API: http://localhost:8000/projects

## API Notes

The API returns a deliberately stable camelCase contract even though Laravel uses snake_case database columns. This keeps the frontend decoupled from the database schema.

### Example create request

```json
{
  "clientName": "Acme Corporation",
  "projectName": "New Website",
  "description": "A new marketing site",
  "status": "Planning",
  "priority": "High",
  "startDate": "2026-09-15",
  "dueDate": "2026-10-15"
}
```

### Query parameters

`GET /projects?search=acme&status=Planning&priority=High&sort=due_date&direction=asc`

## Architecture Decisions

- **Form Requests** keep validation out of controllers.
- **PHP enums** make status and priority finite, explicit domain values.
- **API Resource** controls the public JSON representation.
- **Controller-level query composition** keeps the endpoint simple while supporting useful search/filter/sort behavior.
- **Reusable Vue form** serves both create and edit flows.
- **Server-side validation remains authoritative**; client validation exists primarily for fast feedback.

## Testing

```bash
cd backend
php artisan test
```

Tests cover listing, creation, validation, updating, deleting, and missing-resource behavior.

## AI Disclosure

ChatGPT and Claude Code may be used as development assistants for implementation ideas, boilerplate, edge-case review, and documentation. All generated code is reviewed and tested before submission.

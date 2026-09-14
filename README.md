# Client Project Tracker

A small full-stack technical assessment for a digital agency.

The application allows project managers to create, view, update, delete, search, filter, and sort client projects while enforcing the required project validation rules.

## Features Implemented

### Required Features

- Get all projects
- Get a single project
- Create a project
- Update a project
- Delete a project
- Client Name is required
- Project Name is required
- Status must be one of:
  - Planning
  - In Progress
  - On Hold
  - Completed
- Priority must be one of:
  - Low
  - Medium
  - High
- Due Date cannot be earlier than Start Date
- Meaningful validation errors with HTTP `422` responses
- RESTful API implementation

### Bonus Features

- Search by client name and project name
- Filter by status
- Filter by priority
- Sorting
- Automated API feature tests
- Docker development environment

## Tech Stack

### Backend

- Laravel 13
- PHP 8.4+
- PostgreSQL 17

### Frontend

- Vue 3
- TypeScript
- Vite
- Pinia
- Vue Router
- Axios

### Development

- Docker Compose
- PHPUnit / Laravel testing tools

## Architecture

```text
┌─────────────────────────┐
│       Browser           │
│                         │
│   Vue 3 + TypeScript    │
│        :5173            │
└────────────┬────────────┘
             │
             │ HTTP / Axios
             ▼
┌─────────────────────────┐
│      Laravel API        │
│                         │
│       PHP 8.4           │
│        :8000            │
└────────────┬────────────┘
             │
             │ Eloquent
             ▼
┌─────────────────────────┐
│      PostgreSQL 17      │
│        :5432            │
└─────────────────────────┘
```

The frontend and backend are maintained as separate applications.

The Vue application is responsible for presentation and user interaction, while Laravel handles validation, business rules, persistence, and API responses.

## Setup Instructions

### Prerequisites

- Docker Desktop
- Docker Compose
- Git

### Run with Docker

Clone the repository:

```bash
git clone <repository-url>
cd client-project-tracker
```

Start the application:

```bash
docker compose up -d --build
```

Generate the Laravel application key:

```bash
docker compose exec backend php artisan key:generate
```

Run database migrations and seed the assessment data:

```bash
docker compose exec backend php artisan migrate --seed
```

Clear cached configuration when needed:

```bash
docker compose exec backend php artisan optimize:clear
```

### Application URLs

Frontend:

```text
http://localhost:5173
```

Backend API:

```text
http://localhost:8000/projects
```

## API Endpoints

| Method | Endpoint | Description |
|---|---|---|
| GET | `/projects` | Get all projects |
| GET | `/projects/{id}` | Get a single project |
| POST | `/projects` | Create a project |
| PUT | `/projects/{id}` | Update a project |
| DELETE | `/projects/{id}` | Delete a project |

## API Query Parameters

The `GET /projects` endpoint supports optional query parameters for the bonus search, filtering, and sorting functionality.

Available parameters:

- `search`
- `status`
- `priority`
- `sort`
- `direction`

Example:

```text
GET /projects?search=acme&status=Planning&priority=High&sort=due_date&direction=asc
```

## API Response Format

The API uses a stable camelCase JSON response format for the frontend, while the database uses snake_case column names.

Example response:

```json
{
  "data": {
    "id": 1,
    "clientName": "Acme Corporation",
    "projectName": "Corporate Website Redesign",
    "description": "Redesign and modernize the company's corporate website.",
    "status": "In Progress",
    "priority": "High",
    "startDate": "2026-06-01",
    "dueDate": "2026-07-15"
  }
}
```

This keeps the frontend API contract independent from the database schema.

## Example Create Request

```http
POST /projects
Content-Type: application/json
```

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

## Validation Rules

The backend validates all incoming project data.

| Field | Rules |
|---|---|
| Client Name | Required, string |
| Project Name | Required, string |
| Description | Optional, string |
| Status | Required, valid project status |
| Priority | Required, valid project priority |
| Start Date | Optional, valid date |
| Due Date | Optional, valid date and not earlier than Start Date |

Invalid requests return HTTP `422 Unprocessable Entity` with structured validation errors.

Example:

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "dueDate": [
      "The due date must be on or after the start date."
    ]
  }
}
```

## Database and Seed Data

The application uses PostgreSQL 17.

The assessment provides 12 sample projects in `test_data.json`. These projects are included as Laravel seed data.

To recreate the database from scratch:

```bash
docker compose exec backend php artisan migrate:fresh --seed
```

## Testing

Run the automated test suite with:

```bash
docker compose exec backend php artisan test
```

The tests cover:

- Project listing
- Project creation
- Validation failures
- Project updates
- Project deletion
- Missing project handling

## Architecture Decisions

### Form Requests

Laravel Form Requests are used to keep validation rules separate from controllers and make the validation logic easier to maintain and test.

### PHP Enums

PHP enums are used for project status and priority to make the allowed domain values explicit and prevent arbitrary values from being used throughout the application.

### API Resources

Laravel API Resources control the API response structure and provide a stable contract between the backend and frontend.

### Backend Search, Filtering, and Sorting

Search, filtering, and sorting are implemented on the backend rather than only in the browser. This keeps data-related operations close to the database and provides a foundation that can scale as the number of projects grows.

### Reusable Vue Form

The same Vue form component is used for both creating and editing projects to avoid duplicating form logic.

### Server-Side Validation

Client-side validation is used for immediate user feedback, but the backend remains the authoritative source of validation and business rules.

## Assumptions Made

- Authentication is not required because it is listed as an optional bonus feature in the assessment.
- A project description is optional.
- Start Date and Due Date are optional.
- When both dates are provided, Due Date must be on or after Start Date.
- Status and Priority are restricted to the values explicitly defined in the assessment.
- Search applies to both Client Name and Project Name.
- Search, filtering, and sorting are implemented through optional query parameters on `GET /projects`.
- The supplied 12-project dataset is treated as the initial seed data.
- PostgreSQL is used as the primary application database.
- The frontend and backend are deployed as separate applications during development and communicate through HTTP.
- The API response uses camelCase while the database uses snake_case.

## AI Disclosure

ChatGPT were used as development assistants during the assessment.

They were used for:

- Reviewing implementation approaches
- Generating and refining boilerplate
- Identifying potential edge cases
- Reviewing validation and error-handling approaches
- Improving documentation

All generated code was reviewed, adapted, and tested before inclusion in the project.

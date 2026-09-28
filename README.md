# Library Management

A small Laravel 13 app for running a lending library. Admins manage the catalogue, the
members and the issue/return desk; members only see their own loans.

## Pages

| Page | Who can open it | Routes |
| --- | --- | --- |
| Books: list, search, add, edit, delete | admin | `/books` (`books.*`) |
| Issue & return, list of books on loan | admin | `/circulation` (`circulation.*`) |
| Members: list, add, delete | admin | `/users` (`users.*`) |
| Own loans, due dates and fines | member | `/my` (`my`) |

`/` redirects an admin to the books page and a member to `/my`.

## The rules

- A loan lasts **14 days** (`IssueRecord::LOAN_DAYS`) and a late day costs **रू 5**
  (`IssueRecord::FINE_PER_DAY`); a book due today is not late yet.
- `books.stock` is the number of copies **on the shelf**: issuing decrements it,
  returning increments it.
- `users.is_admin = 1` marks an admin. The check is the `admin` middleware, registered
  as an alias in `bootstrap/app.php`.
- Login allows 5 attempts per minute per IP (`routes/auth.php`).

## Getting started

```sh
composer setup     # install, copy .env, generate key, migrate, npm install, npm run build
php artisan db:seed   # optional demo data (books, members)
composer dev       # run the app, queue listener and Vite together
```

The connection details live under `DB_*` in `.env` (MySQL, database `library`).

### Seeded logins

| Role | Email | Password |
| --- | --- | --- |
| Admin | admin@library.test | password |
| Member | user@library.test | password |

## What is where

```
app/Http/Controllers/BookController.php          books CRUD + search
app/Http/Controllers/CirculationController.php   issue and return
app/Http/Controllers/UserController.php          member management
app/Http/Controllers/MyController.php            the member's own page
app/Models/IssueRecord.php                       loan + fine calculations
resources/views/                                 one folder per page
```

## Tests

```sh
php artisan test
```

Covers the circulation rules (stock changes, double issue/return, fines), login and that
every page renders for the right role.


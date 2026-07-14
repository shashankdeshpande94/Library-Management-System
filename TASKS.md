# Library Management System — Yii2 (MVC) Practice Project

A basic **Library Management System** built with the Yii2 Framework (basic app template),
used as both backend and frontend, backed by **MySQL**. Created as a quick, hands-on
refresher of Yii2 fundamentals and the MVC pattern for interview preparation.

---

## 1. Setup / Installation

### Prerequisites
- PHP 8.1+ (this project was built against PHP 8.2, via XAMPP)
- Composer
- MySQL server (XAMPP's bundled MySQL works fine)

### Steps already done in this project
1. Scaffolded via Composer:
   ```
   composer create-project --prefer-dist yiisoft/yii2-app-basic library-management-system
   ```
2. Created the MySQL database:
   ```sql
   CREATE DATABASE library_management_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
3. Configured [config/db.php](config/db.php) with the DB name/credentials.
4. Wrote migrations in [migrations/](migrations) for `author`, `member`, `book`, `book_loan`.
5. Applied them:
   ```
   php yii migrate
   ```
6. Ran the app locally with PHP's built-in server:
   ```
   php yii serve
   ```
   or point an Apache/Nginx vhost's document root at the `web/` folder.
7. Visit `http://localhost:8080` (or your configured host). Gii (code generator) and the
   Debug toolbar are enabled automatically in dev mode at `/gii` and via the bottom-right badge.

### If you need to set this up again
- `composer install` — installs vendor dependencies from `composer.lock`.
- Make sure MySQL is running (`xampp-control.exe` → start MySQL, or `mysql_start.bat`).
- Update `config/db.php` if your MySQL user/password differ from `root` / *(empty)*.
- `php yii migrate` — (re)builds all tables.
- `php yii serve` — starts a dev server on `http://localhost:8080`.

---

## 2. MVC Architecture — quick refresher

Yii2 (like most PHP frameworks) follows **Model-View-Controller**, which separates an app
into three responsibilities so each can change independently:

| Layer | Responsibility | Where in this project |
|---|---|---|
| **Model** | Data, business rules, validation. Talks to the database. | [models/Book.php](models/Book.php), [models/Author.php](models/Author.php), [models/Member.php](models/Member.php), [models/BookLoan.php](models/BookLoan.php) |
| **View** | Presentation only — renders HTML from data handed to it. No business logic. | [views/book/*.php](views/book), [views/author/*.php](views/author), etc. |
| **Controller** | Receives the HTTP request, asks the Model to do work, picks a View to render, returns the response. | [controllers/BookController.php](controllers/BookController.php), [controllers/BookLoanController.php](controllers/BookLoanController.php) |

**Request flow in Yii2:**

```
Browser request
   → web/index.php (front controller / entry script)
   → Application (bootstraps config, DI container)
   → UrlManager resolves route, e.g. "book/create" → BookController::actionCreate()
   → Controller action loads/validates a Model, calls save()/find()
   → Controller renders a View, passing the Model to it
   → View (+ Layout) produces HTML
   → Response sent back to browser
```

### Models (`ActiveRecord`)
- Each model maps to one DB table (`Book` → `book` table) via Yii2's **ActiveRecord** ORM.
- `rules()` defines validation (required fields, string length, foreign-key existence, etc.).
- `attributeLabels()` gives human-friendly field names for forms/grids.
- Relations are declared as methods, e.g. `Book::getAuthor()` (`hasOne`) and
  `Author::getBooks()` (`hasMany`) — see [models/Book.php](models/Book.php) and
  [models/Author.php](models/Author.php).
- Business logic can live on the model too — see `BookLoan::markReturned()` in
  [models/BookLoan.php](models/BookLoan.php), which wraps the "return a book" operation
  (update loan + restock copy count) in a DB transaction.
- `*Search` models (e.g. [models/BookSearch.php](models/BookSearch.php)) are a Yii2
  convention: a model dedicated to building filtered/sorted `ActiveDataProvider`s for
  grid views, kept separate from the "real" model's validation rules.

### Views
- Plain PHP templates (`.php` files) rendered by `Controller::render()`.
- Never contain DB queries or business logic — only formatting/echoing of data the
  controller already prepared.
- Reusable pieces: `_form.php` (shared between create/update), `layouts/main.php`
  (overall page shell), `layouts/_header.php` (nav bar).
- Built with Yii2's widgets: `ActiveForm` (forms + validation display), `GridView`
  (sortable/filterable tables), `DetailView` (single-record display).

### Controllers
- One controller per resource (`BookController`, `AuthorController`, `MemberController`,
  `BookLoanController`), each with the standard CRUD actions: `actionIndex`,
  `actionView`, `actionCreate`, `actionUpdate`, `actionDelete`.
- `behaviors()` attaches cross-cutting concerns declaratively — e.g. `VerbFilter` restricts
  `delete`/`return` to POST requests only.
- `findModel($id)` is the standard Yii2 pattern for loading a record or throwing a 404.
- `BookLoanController` also has custom actions beyond plain CRUD: `actionCreate` ("issue a
  book", which decrements `available_copies`) and `actionReturn` ("return a book", which
  calls `BookLoan::markReturned()`).

### Why this separation matters (interview talking point)
- **Testability**: business rules in Models can be unit-tested without HTTP/views.
- **Reuse**: the same Model/Controller can serve multiple Views (HTML page, JSON API, etc.).
- **Maintainability**: designers/front-end devs can edit Views without touching business logic.

---

## 3. Project domain model

```
Author (1) ───< (many) Book (1) ───< (many) BookLoan (many) >─── (1) Member
```

- **Author**: `id, name, bio`
- **Book**: `id, title, author_id (FK), isbn, category, published_year, total_copies, available_copies`
- **Member**: `id, full_name, email (unique), phone, membership_date, status`
- **BookLoan**: `id, book_id (FK), member_id (FK), borrow_date, due_date, return_date, status`
  - `status`: `borrowed` | `returned` | `overdue`
  - Issuing a loan decrements `Book.available_copies`; returning it increments it back
    (see [models/BookLoan.php](models/BookLoan.php)).

---

## 4. Tasks completed

- [x] Scaffold Yii2 basic app via Composer (used as both backend + frontend, server-rendered PHP views)
- [x] Configure MySQL connection (`config/db.php`)
- [x] Design schema + write migrations for `author`, `member`, `book`, `book_loan` (with FKs/indexes)
- [x] Write ActiveRecord models with validation rules and relations
- [x] Write `*Search` models for filterable grids
- [x] Write CRUD controllers for Author, Member, Book
- [x] Write `BookLoan` controller with custom **Issue Book** / **Return Book** actions and
      transactional copy-count updates
- [x] Build CRUD views (index/grid, create, update, view/detail) for all four resources
- [x] Add navigation links and a simple dashboard (`site/index`) showing live counts
- [x] Manually verify the full flow in-browser: create author → create book → create member
      → issue loan → confirm available copies decrement → return loan → confirm copies restock

## 5. Possible next steps (good interview extension topics)

- [ ] Add authentication-gated actions (only logged-in "librarian" can create/delete)
- [ ] Add an `overdue` cron/console command that flips `status` to `overdue` past `due_date`
- [ ] Add pagination/sorting tests, or unit tests for `BookLoan::markReturned()`
- [ ] Add a REST API module (`yii\rest\ActiveController`) alongside the HTML frontend
- [ ] Add RBAC (Role-Based Access Control) via `yii\rbac` for Librarian vs Member roles
- [ ] Add search/autocomplete for Book/Member pickers on the Issue Book form
- [ ] Dockerize (a `docker-compose.yml` already ships with the Yii2 basic template — extend it)

---

## 6. Key Yii2 concepts worth reviewing before an interview

- **ActiveRecord** vs plain **Model** (`yii\base\Model`) — ORM-backed vs. form-only models
- **Migrations** (`php yii migrate/create`, `safeUp()`/`safeDown()`) — version-controlled schema changes
- **DAO vs ActiveRecord vs Query Builder** — three ways to talk to the DB in Yii2
- **Behaviors** (e.g. `TimestampBehavior`, `VerbFilter`, `AccessControl`) — reusable cross-cutting logic
- **Widgets** (`GridView`, `ActiveForm`, `DetailView`, `Pjax`) — reusable UI components
- **DI container** — Yii2 auto-wires typed constructor params (see `SiteController`'s
  constructor-injected `MailerInterface`)
- **Gii** — code generator for models/CRUD (enabled at `/gii` in this project's dev config)
- **Events** — `EVENT_BEFORE_SAVE`, `EVENT_AFTER_FIND`, etc. on ActiveRecord
- **Console commands** (`commands/` + `yii <command>`) — for migrations, cron jobs, cache flushing

# NSTU Medical Center — Hospital Management System

A full hospital management system built with **Laravel 11 (PHP)** and **MySQL**, using
server-rendered Blade views (no separate frontend build step — just Bootstrap 5 via CDN).
Built as a university project.

## Modules

|#|Module|What it covers|
|-|-|-|
|1|Authentication|Login, patient self-registration, logout, role-based access|
|2|User Management|Admin creates/edits/deactivates admin, doctor \& receptionist accounts|
|3|Patient Management|Register, edit, search, view full patient history|
|4|Doctor Management|Doctor profiles, specialization, department, browsing|
|5|Departments|CRUD for hospital departments|
|6|Appointments|Book, confirm, complete, cancel — with live slot picker|
|7|Doctor Schedules|Weekly recurring availability per doctor, auto slot generation|
|8|Medical Records|Visit notes, vitals, linked to appointments|
|9|Diagnoses|Attached to a medical record, with ICD code \& severity|
|10|Prescriptions|Multi-medicine prescriptions with dosage/frequency/duration, printable|
|11|Billing \& Payments|Invoices with line items, partial/full payments, balance tracking|

## Roles

* **Admin** — full access, manages users \& departments
* **Receptionist** — manages patients, books appointments, handles billing
* **Doctor** — manages own schedule, appointments, medical records, diagnoses, prescriptions
* **Patient** — books own appointments, views own records/prescriptions/invoices

## Tech stack

* PHP 8.2+ / Laravel 11
* MySQL 8 (SQLite also works for quick local testing — see below)
* Blade + Bootstrap 5 (CDN) — no Node/npm build step required
* Plain session-based auth (no external auth package)

\---

## 1\. Local setup

**Requirements:** PHP 8.2+, Composer, MySQL (or just SQLite for a zero-install start).

```bash
# 1. Install PHP dependencies
composer install

# 2. Copy the environment file
cp .env.example .env

# 3. Generate the app key
php artisan key:generate
```

### Option A — fastest start, no MySQL install needed (SQLite)

```bash
touch database/database.sqlite
```

Then edit `.env` and change:

```
DB\\\_CONNECTION=sqlite
# comment out or remove the DB\\\_HOST / DB\\\_DATABASE / DB\\\_USERNAME / DB\\\_PASSWORD lines
```

### Option B — MySQL (matches production)

Create a database, then edit `.env`:

```
DB\\\_CONNECTION=mysql
DB\\\_HOST=127.0.0.1
DB\\\_PORT=3306
DB\\\_DATABASE=hospital\\\_management
DB\\\_USERNAME=root
DB\\\_PASSWORD=your\\\_password
```

```bash
mysql -u root -p -e "CREATE DATABASE hospital\\\_management CHARACTER SET utf8mb4;"
```

### Then, either way:

```bash
# 4. Run migrations and load demo data
php artisan migrate --seed

# 5. Serve the app
php artisan serve
```

Visit **http://localhost:8000**.

### Demo accounts (created by the seeder)

|Role|Email|Password|
|-|-|-|
|Admin|admin@hms.test|password|
|Doctor|doctor@hms.test|password|
|Receptionist|reception@hms.test|password|
|Patient|patient@hms.test|password|

The seeder also creates 4 extra doctors (one per department), a handful of walk-in
patients, weekly schedules, past completed visits with diagnoses/prescriptions, and
invoices in various payment states — enough to demo every module immediately.

\---

## 2\. Project structure notes

* `app/Models` — one model per table, with relationships and small helper methods
(e.g. `Invoice::refreshTotals()`, `DoctorSchedule::generateSlots()`).
* `app/Http/Controllers` — one controller per module.
* `app/Http/Middleware/EnsureUserHasRole.php` — the `role:` route middleware used to
restrict pages by role (see `routes/web.php`).
* `routes/web.php` — every route, grouped by module, with role middleware applied.
* `resources/views` — Blade templates, one folder per module, sharing
`layouts/app.blade.php` (authenticated shell) and `layouts/guest.blade.php` (login/register).
* `database/seeders/DatabaseSeeder.php` — all demo data in one file.

No JavaScript build step is used — Bootstrap 5, Bootstrap Icons, and a Google Font are
loaded via CDN `<link>` tags, and the small amount of interactive JS (appointment slot
picker, dynamic invoice line items, dynamic prescription items) is inline `<script>` in
the relevant Blade views. This keeps deployment to "PHP + MySQL", nothing else.

\---

## 3\. Deploying for free

Two solid, genuinely-free options as of 2026. **Free-tier terms change often — check
each provider's current pricing page before you commit**, but both of these were
confirmed to have real no-credit-card free tiers at the time this was written.

### Option A — Render.com (recommended: closest to "just works")

Render's free web service tier runs a Docker container for you (the free instance
spins down after inactivity and takes \~1 minute to wake back up on the next request —
fine for a class demo). This repo already includes a `Dockerfile` that installs
Composer dependencies and runs migrations automatically on deploy.

1. Push this project to a GitHub repo.
2. Render's own free database is PostgreSQL, not MySQL — so for a real free MySQL
instance, sign up for a free MySQL database separately (Aiven's free tier and
Railway's trial credit both support MySQL; search "free MySQL hosting" for current
options, since these come and go).
3. On render.com, create a **New Web Service**, connect your repo,
and choose **Docker** as the environment (it will detect the `Dockerfile`).
4. Add environment variables in Render's dashboard (Settings -> Environment):

```
   APP\\\_NAME=City Care Hospital
   APP\\\_ENV=production
   APP\\\_KEY=            # generate locally with `php artisan key:generate --show`, paste the output
   APP\\\_DEBUG=false
   APP\\\_URL=https://your-app.onrender.com
   DB\\\_CONNECTION=mysql
   DB\\\_HOST=<your MySQL host>
   DB\\\_PORT=3306
   DB\\\_DATABASE=<your MySQL database name>
   DB\\\_USERNAME=<your MySQL username>
   DB\\\_PASSWORD=<your MySQL password>
   SESSION\\\_DRIVER=database
   CACHE\\\_STORE=database
   QUEUE\\\_CONNECTION=database
   ```

5. Deploy. Render builds the Docker image, and the container's startup command
(`php artisan migrate --force; apache2-foreground`) creates your tables automatically.
6. To load demo data once, use Render's **Shell** tab (or a one-off job) and run:

```bash
   php artisan db:seed
   ```

### Option B — A free PHP + MySQL panel host (InfinityFree, GoogieHost, HelioHost, etc.)

These bundle free PHP and free MySQL together under one cPanel account, which matches
your MySQL requirement exactly, but most don't give SSH/Composer access, so:

1. Run `composer install --no-dev --optimize-autoloader` **on your own machine**.
2. Upload the whole project (including the now-present `vendor/` folder) via the
host's File Manager or FTP.
3. Point the domain's document root at the project's `public/` folder (cPanel usually
has an "Application Root" / "Document Root" setting for this).
4. Create a MySQL database + user in cPanel, then create `.env` on the server (copy
`.env.example`, fill in the DB credentials cPanel gave you, and set `APP\\\_KEY` —
generate it locally with `php artisan key:generate --show` and paste the value in).
5. Since there's usually no terminal, import the schema by running
`php artisan migrate --seed` locally against a temporary SQLite file to sanity check it,
then use phpMyAdmin's SQL tab on the host to run the equivalent `CREATE TABLE`
statements, **or** check if your host offers a "Cron Job" / "Run PHP script" feature
you can point at a small one-off script that calls `Artisan::call('migrate:fresh --seed')`.

Option A is significantly less fiddly if you can find any free MySQL host to pair with
Render — Option B is the fallback if you'd rather avoid juggling two providers.

\---

## 4\. Common commands

```bash
php artisan migrate:fresh --seed   # wipe \\\& reseed the database
php artisan route:list              # see every route + middleware
php artisan tinker                  # interactive shell to poke at models
```

## 5\. Known simplifications (intentional, for scope)

* No email verification / password-reset flow (out of scope for a class project;
easy to add later with Laravel's built-in `Illuminate\\\\Auth\\\\Notifications\\\\ResetPassword`).
* Payments are recorded manually (cash/card/mobile banking/insurance) rather than
integrated with a real payment gateway — appropriate for a demo billing module.
* No automated tests included, to keep the deliverable focused; the structure
(one controller/model per module) makes adding PHPUnit/Pest tests straightforward
if your course requires them.


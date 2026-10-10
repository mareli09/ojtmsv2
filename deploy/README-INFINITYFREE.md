# Deploying OJTMS to InfinityFree

InfinityFree is free Apache + PHP + MySQL shared hosting with **no SSH and no
Composer**. Everything must be built on your laptop and uploaded; the database
goes in through phpMyAdmin.

---

## Read this before you start

| Feature | On InfinityFree |
|---|---|
| Pages, login, dashboards, reports, CSV/ZIP downloads | Work normally |
| MySQL database | Works — free, created in the control panel |
| **Contact form email** | **Blocked** — see "Email" below |
| **Password reset email** | **Blocked** — same reason |
| **AI chatbot (OpenAI)** | Likely blocked — outbound API calls are restricted |
| Student file uploads | Work, but count against the file limit |
| Scheduled tasks / queue workers | Not available (the app doesn't need them) |

InfinityFree blocks outbound SMTP **and** disables PHP's `mail()`. The Gmail
setup we configured will not send from there. That affects two features you
already have working locally, so decide up front whether that matters for your
demo.

---

## Step 1 — Create the hosting account

1. Sign up at [infinityfree.com](https://infinityfree.com) and create an account.
2. Note the free subdomain you pick, e.g. `ojtms.infinityfreeapp.com`.
3. In the control panel, set **PHP version to 8.2** (Laravel 11 needs 8.2+).
4. Open **MySQL Databases** and create one. Write down all four values:
   - host, e.g. `sql123.infinityfree.com`
   - database name, e.g. `if0_12345678_ojtms`
   - username, e.g. `if0_12345678`
   - password

Accounts take a few minutes to activate; the domain 404s until then.

---

## Step 2 — Import the database

`deploy/ojtms_database.sql` is a full dump of your local `ojtmsv2` database —
all 16 tables and the seeded demo data.

1. Control panel → **phpMyAdmin** → select your database.
2. **Import** tab → choose `ojtms_database.sql` → **Go**.

If the file is rejected for size, gzip it first (phpMyAdmin accepts `.sql.gz`),
or import it in two passes: structure first, then data.

> Because the data is already in the dump, you never need to run
> `php artisan migrate` or `db:seed` on the server — which is good, since
> you can't.

---

## Step 3 — Build the upload package

On your laptop, in the project folder:

```bash
# 1. Install dependencies WITHOUT dev packages, optimised for production
composer install --no-dev --optimize-autoloader

# 2. Clear any cached config that points at localhost
php artisan optimize:clear
```

Then assemble two things:

**A. `ojtms_app.zip`** — the whole project **except** `public/`, `node_modules/`,
`.git/` and `deploy/`. It must contain:

```
app/  bootstrap/  config/  database/  resources/  routes/  storage/  vendor/
artisan  composer.json  composer.lock
```

**B. the web-root files** — from this `deploy/` kit and your `public/` folder:

| Upload to | From |
|---|---|
| `htdocs/index.php` | `deploy/htdocs/index.php` (already path-adjusted) |
| `htdocs/.htaccess` | `deploy/htdocs/.htaccess` |
| `htdocs/favicon.ico`, `robots.txt` | your `public/` folder |
| `htdocs/extract.php` | `deploy/extract.php` — temporary |

---

## Step 4 — Upload

Use FileZilla with the FTP details from the control panel.

1. Upload `ojtms_app.zip` and `extract.php` into `htdocs/`.
2. Edit `extract.php` first and change `SECRET` to something only you know.
3. Visit `https://<your-site>/extract.php?key=YOUR_SECRET`
4. When it reports success, **delete `extract.php` and `ojtms_app.zip`** over FTP.
5. Rename the extracted folder to `ojtms_app` if your ZIP had a different root.
6. Upload `deploy/ojtms_app/.htaccess` into `htdocs/ojtms_app/` — this is what
   stops the world from reading your `.env`.
7. Upload the `htdocs/` files from Step 3B.

Uploading ~6,000 files individually over FTP takes hours and often stalls —
that is why the ZIP route exists. Only `vendor/` is large (36 MB).

Final layout:

```
htdocs/
├── index.php          ← front controller
├── .htaccess          ← pretty-URL rewrites
├── favicon.ico
├── robots.txt
└── ojtms_app/
    ├── .htaccess      ← Require all denied
    ├── .env           ← next step
    ├── app/ bootstrap/ config/ database/ resources/ routes/ storage/ vendor/
    └── artisan  composer.json  composer.lock
```

---

## Step 5 — Configure

1. Take `deploy/env-production.txt`, fill in your four database values and your
   real domain in `APP_URL`.
2. Upload it as `htdocs/ojtms_app/.env` — leading dot, **no `.txt`**.
   (FileZilla: upload it, then rename on the server.)
3. Set permissions to **755** on these folders, recursively:
   - `htdocs/ojtms_app/storage`
   - `htdocs/ojtms_app/bootstrap/cache`

   If you get a 500 error later, try 777 on `storage/framework` and
   `storage/logs` — shared hosts vary.

A fresh `APP_KEY` is already in that template. Don't reuse your local one.

---

## Step 6 — Check it

Visit your domain. You should see the OJTMS landing page.

Log in with any seeded account — `admin` / `password`. **Change that password
immediately**: the dump ships with the demo passwords, and the login page lists
test accounts for anyone to see.

---

## Troubleshooting

**500 error, blank page**
Set `APP_DEBUG=true` in `.env` temporarily, reload, read the message, then set
it back to `false`. Nine times out of ten it's `storage/` permissions or a wrong
`DB_HOST`.

**"No application encryption key has been specified"**
`.env` isn't being read — it's probably named `.env.txt`, or sitting in the
wrong folder.

**Blank page with no error at all**
Check `htdocs/ojtms_app/storage/logs/laravel.log` over FTP.

**Pages work but every link 404s**
`.htaccess` didn't upload (FileZilla hides dotfiles by default — enable
*Server → Force showing hidden files*).

**"SQLSTATE[HY000] [1045] Access denied"**
The DB host is not `localhost` on InfinityFree. Use the `sqlXXX.infinityfree.com`
value from the control panel.

**Account suspended**
Free hosting enforces daily CPU/hit limits. Heavy page reloads during a demo
can trip it; it lifts after 24 hours.

---

## Email

The contact form and password reset are built and working — they just can't
send from InfinityFree, which blocks SMTP. With `MAIL_MAILER=log` they record
the message and show the user a success notice instead of crashing.

Two ways to get real email if you need it:

1. **An HTTP email API** (Brevo, SendGrid, Mailgun). These send over HTTPS
   instead of SMTP, so they may get through where port 587 does not. This needs
   a code change — swapping Laravel's SMTP transport for the provider's API —
   and you should test it on the live host before relying on it.
2. **Host somewhere that allows SMTP.** Paid tiers on Render, Railway and
   Fly.io permit outbound SMTP, and your current Gmail configuration would
   work unchanged. Free tiers often restrict SMTP ports to curb spam, so
   verify on the plan you actually pick before depending on it.

---

## A note on fit

InfinityFree suits a Laravel app that is mostly pages and database work — which
describes most of OJTMS. Where it strains is anything reaching outward: email,
the OpenAI chatbot, and any scheduled work. If those need to be live for your
defense, the same app deploys to Render or Railway with the mail setup intact;
ask me and I'll prepare that kit instead.

# Deploying OJTMS to Hostinger shared hosting

Hostinger shared hosting gives you SSH, Composer, Git, MySQL and real email, so
the app can be installed the normal Laravel way: clone, `composer install`,
`migrate`. (The InfinityFree kit in this folder is no longer needed.)

Everything works on Hostinger, including the contact form, password-reset email
and the OpenAI chatbot.

Domain: **ojtms.online**. Email: **no-reply@ojtms.online** (mailbox already
created, and already tested from the local project).

`deploy/env-hostinger.txt` already holds the domain and the mailbox login. Only
the three database values are left to fill in. That file is git-ignored, so
the password never goes to GitHub.

**Two ways to deploy:** **Method A (SSH, recommended)** is Steps 0–5 below.
If your plan has no SSH, or you'd rather click than type commands, use
**Method B (File Manager)** further down.

---

# Method A: SSH (recommended)

## Step 0: Push your latest code to GitHub

The server pulls the code from `github.com/mareli09/ojtmsv2`, so commit and
push everything first:

```bash
git add -A
git commit -m "Prepare for Hostinger deployment"
git push origin main
```

`.env`, `deploy/env-*.txt` and `deploy/*.sql` are git-ignored, so no passwords
are pushed.

---

## Step 1: Prepare hPanel

1. **Domain**: hPanel → **Websites**, and make sure your domain is added and
   pointing to Hostinger.
2. **PHP version**: Websites → your site → **Advanced → PHP Configuration**,
   then pick **PHP 8.2** or **8.3**. Laravel 11 needs 8.2 or newer.
3. **SSL**: **Security → SSL**, then install the free SSL and turn on
   **Force HTTPS**.
4. **Database**: **Databases → MySQL Databases**, then create one. Write down
   the database name, username and password. Both name and user look like
   `u123456789_ojtms`, and the host is `localhost`.
5. **SSH**: **Advanced → SSH Access**, then **Enable**. Note the IP, port
   (usually `65002`) and username.

---

## Step 2: Connect over SSH and get the code

From your laptop (PowerShell, Git Bash, or PuTTY):

```bash
ssh -p 65002 u123456789@YOUR.SERVER.IP
```

Your repo is private, so give the server a key that GitHub accepts:

```bash
ssh-keygen -t ed25519 -C "hostinger-ojtms"     # press Enter at every prompt
cat ~/.ssh/id_ed25519.pub
```

Copy the printed line, then on GitHub open **ojtmsv2 → Settings → Deploy keys
→ Add deploy key**, paste it, and save (read-only is fine).

Now clone next to the web root:

```bash
cd ~/domains/ojtms.online
git clone git@github.com:mareli09/ojtmsv2.git ojtms
cd ojtms
php -v                    # must say 8.2 or 8.3
composer install --no-dev --optimize-autoloader
```

If `composer` reports version 1.x, use `composer2` instead.

---

## Step 3: Configure `.env`

```bash
cp .env.example .env
nano .env
```

Delete everything in the editor (hold **Ctrl+K**), then paste the contents of
`deploy/env-hostinger.txt` with your real values filled in. Save with
**Ctrl+O**, Enter, then exit with **Ctrl+X**. Then:

```bash
php artisan key:generate
chmod -R 775 storage bootstrap/cache
```

---

## Step 4: Create the tables

Pick **one** of these:

**A. Fresh start with demo data** (good for a defense or demo):

```bash
php artisan migrate --force
php artisan db:seed --class=FullDemoSeeder --force
```

**B. Fresh start, real use**: run migrations only, then create your admin
account:

```bash
php artisan migrate --force
php artisan db:seed --class=CMSSeeder --force
php artisan tinker
>>> App\Models\User::create(['role'=>'admin','first_name'=>'System','last_name'=>'Admin','username'=>'admin','email'=>'you@ojtms.online','password'=>bcrypt('A-Strong-Password-Here'),'status'=>'active']);
>>> exit
```

**C. Copy your local data exactly**: export a fresh dump from your laptop
(phpMyAdmin → Export, or `mysqldump`), import it in hPanel → **phpMyAdmin**,
then upload your local `storage/app/` folder (the student uploads) to
`~/domains/ojtms.online/ojtms/storage/app/` with the hPanel **File Manager**.
Finish with `php artisan migrate --force` to add any newer tables.

Do not reuse the old `deploy/ojtms_database.sql`. It's from before the chatbot
history and company fields were added.

---

## Step 5: Point the domain at Laravel's `public/` folder

The domain serves `public_html`. Replace it with a link to `ojtms/public`:

```bash
cd ~/domains/ojtms.online
mv public_html public_html_old
ln -s ojtms/public public_html
```

Then speed it up:

```bash
cd ojtms
php artisan optimize
```

Open `https://ojtms.online`. You should see the OJTMS landing page.

> If you used option A, every demo account's password is `password`. Change
> the admin password right away in Profile Settings. The login page no longer
> lists test accounts when `APP_ENV=production`.

---

## Step 6: Hostinger email

### Mailbox: done ✅

| Mailbox | Used for |
|---|---|
| `no-reply@ojtms.online` | **Sends** every OJTMS email (password resets, contact form) |
| `admin@ojtms.online` | **Receives** landing-page contact messages. It's the default **Contact email** in Admin → CMS. |

Both were tested from the local project. One thing remains:

1. Under **Emails → Email Deliverability / DNS**, check that **SPF, DKIM and
   DMARC** show as active. Without them, Gmail sends your messages to spam.

### Settings OJTMS uses

| Setting | Value |
|---|---|
| SMTP host | `smtp.hostinger.com` |
| Port / encryption | `465` / `ssl` (or `587` / `tls`) |
| Username | the full address, `no-reply@ojtms.online` |
| Password | that mailbox's password |
| From address | **the same mailbox** (Hostinger rejects anything else) |

These are already in `deploy/env-hostinger.txt`. After editing `.env`, always
run `php artisan optimize` so the cached config is refreshed.

### Test it

```bash
php artisan ojtms:test-mail you@gmail.com
```

It prints the mailer settings and either "Sent." or the exact SMTP error.

### Where mail goes in OJTMS

* **Password reset** goes to the user's own email. Users need a real email in
  their profile.
* **Contact form** on the landing page goes to the **Contact email** set in
  **Admin → CMS**. Set it to the inbox you want, e.g. `ojt@ojtms.online`.
  Visitors' addresses are set as *Reply-To*, so you can answer them directly.

### Reading mail

Use webmail at **https://mail.hostinger.com**, or add the account to
Gmail/Outlook (IMAP `imap.hostinger.com`, port 993, SSL).

---

## Step 7: AI chatbot

Outbound HTTPS is allowed, so OpenAI works. Log in as admin → **CMS**, then
enter the OpenAI API key and enable AI. The key is stored in the database, not
in `.env`.

---

# Method B: File Manager (no SSH)

Use this if SSH isn't available. Everything is prepared on your laptop and
uploaded as one ZIP.

### B1. Build on your laptop

In the project folder:

```bash
composer install --no-dev --optimize-autoloader
php artisan optimize:clear
```

Zip the project **without** `node_modules/`, `.git/`, `deploy/`, `tests/`
and `.env`. The ZIP must contain:

```
app/ bootstrap/ config/ database/ public/ resources/ routes/ storage/ vendor/
artisan  composer.json  composer.lock
```

Afterwards run `composer install` again so your laptop has the dev tools back.

### B2. Database

1. hPanel → **Databases → MySQL Databases**, and create the database (as in
   Step 1).
2. On your laptop, export your local database: phpMyAdmin → `ojtmsv2` →
   **Export** → **Go**. A fresh export already includes the chatbot history
   and company columns.
3. hPanel → **Databases → phpMyAdmin** → open the new database → **Import**
   → choose that `.sql` file → **Go**.

### B3. Upload and extract

1. hPanel → **Files → File Manager** → open `domains/ojtms.online/`.
2. Create a folder `ojtms`, open it, **Upload** the ZIP, then right-click the
   ZIP and choose **Extract**. Delete the ZIP afterwards.
3. Upload `deploy/env-hostinger.txt` into `ojtms/` and rename it to `.env`
   (leading dot, no `.txt`). Fill in the three DB values with the File
   Manager editor.
4. In `.env`, set `APP_KEY=` to your **local** `.env`'s `APP_KEY` value (you
   can't run `key:generate` without SSH).
5. Right-click `ojtms/storage` and `ojtms/bootstrap/cache` → **Permissions**
   → `775`, applied recursively.

### B4. Point the domain at `public/`

Without SSH you can't make a symlink, so redirect instead. Open
`domains/ojtms.online/public_html/`, delete the default `index.php` /
`default.php`, and create a file named `.htaccess` containing:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ ../ojtms/public/$1 [L]
</IfModule>
```

If that shows a 500 or 403 error (some servers forbid `../`), instead move the
**contents** of `ojtms/public/` into `public_html/`. Then edit
`public_html/index.php` and change the two `__DIR__.'/../` paths to
`__DIR__.'/../ojtms/`.

Open `https://ojtms.online`. To update later, upload the changed files with
the File Manager, and run any new migrations by importing a fresh SQL export.

---

## Updating the live site later (Method A)

On your laptop, `git push`. Then over SSH:

```bash
cd ~/domains/ojtms.online/ojtms
php artisan down
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize
php artisan up
```

---

## Troubleshooting

| Symptom | Fix |
|---|---|
| **500 error** | Read `storage/logs/laravel-YYYY-MM-DD.log`. Most often it's `storage` permissions (`chmod -R 775 storage bootstrap/cache`) or wrong DB values. |
| **Changed `.env` but nothing changed** | Run `php artisan optimize:clear && php artisan optimize`. |
| **403 / directory listing** | `public_html` isn't the symlink. Redo Step 5. |
| **`composer: command not found` or wrong PHP** | Use `composer2`, and check `php -v` matches the hPanel PHP version. |
| **Mail: "Authentication failed"** | Username must be the full email address; re-type the mailbox password. |
| **Mail: "Sender address rejected"** | `MAIL_FROM_ADDRESS` must equal `MAIL_USERNAME`. |
| **Mail sends but lands in spam** | Enable DKIM/SPF/DMARC in hPanel (Step 6), and give DNS a few hours. |
| **Student uploads missing after option C** | Upload `storage/app/` again; the database only stores the file paths. |

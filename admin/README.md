# Admin panel

A small, dependency-free CMS for editing the site's content, at `/admin`.

## Logging in

Go to `/admin` (redirects to `/admin/login.php`). Credentials are in
`content/admin-user.json` — a username and a bcrypt password hash, never the
plaintext password. Change your password any time from **Account** once
logged in.

That file is deliberately kept out of git (see `content/.gitignore`) so a
password hash never ends up in the repo's history, even though bcrypt makes
it very hard to reverse. It still needs to exist on the server; upload it
there directly rather than via a deploy.

## What you can edit

- **Site & hero** (`site.php`) — name, tagline, contact details, logo,
  social links, menu labels, and the hero's video playlist (upload,
  reorder, remove).
- Everything else (`edit.php?type=...`) is a generic list + add/edit/delete/
  reorder screen, driven by the field definitions in
  `includes/store.php::content_schemas()`. Services, work, team, stats,
  values, client logos, testimonials, events and process steps all use it.
- **Messages** (`messages.php`) — a read-only view of `storage/messages.log`,
  parsed into a list. The contact form still emails every enquiry too; this
  is the backup copy.

Every write goes straight to the matching file in `content/*.json`. There's
no build step and no cache to clear — a save is live the moment the page
reloads.

## How it's built

- **No database.** Content is flat JSON files in `content/`, one per
  collection. `includes/store.php` (front-end side: `includes/data.php`)
  reads/writes them.
- **Auth**: a single admin account, session-based, in
  `admin/includes/auth.php`. Sessions use a distinct cookie name
  (`dunamis_admin`), `httponly`, `SameSite=Lax`, and `secure` when served
  over HTTPS. Failed logins are throttled per IP with a growing lockout.
- **CSRF**: one synchronizer token per session (`admin/includes/csrf.php`),
  required on every POST.
- **Uploads** (`admin/includes/upload.php`): every image is verified by
  real MIME sniffing and `getimagesize()`, then **decoded and re-encoded
  through GD** before it's saved — this strips out anything hidden inside a
  file that merely *looks* like an image, and normalises the format. Files
  are capped at 8MB, downscaled if huge, and saved under a random filename
  in the matching `assets/img/<collection>/` folder — never the name the
  browser sent. Hero videos are checked for MIME `video/mp4` and capped at
  40MB.
- **Access control**: `content/`, `storage/` and `admin/includes/` all ship
  an `.htaccess` denying direct web access (belt-and-suspenders — the PHP
  itself doesn't expose secrets either). `/admin/` and `/content/` are also
  disallowed in `robots.txt`.

## Adding a new field to an existing collection

Add it to that collection's entry in `content_schemas()`
(`admin/includes/store.php`) and to the matching template in `sections/`
that reads it — the generic edit screen picks up new text/textarea/select/
number/image fields automatically.

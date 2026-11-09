# Claude WordPress Template

A hybrid (block + PHP) WordPress theme built to be extended by AI coding agents.
It has config-driven post types, taxonomies and native custom fields; a scaffolder; a verification gate; and step-by-step recipes.

- **Agents:** read [AGENTS.md](AGENTS.md). Claude Code loads it through [CLAUDE.md](CLAUDE.md).
- **How templates resolve:** [docs/architecture.md](docs/architecture.md)
- **Task recipes:** [docs/recipes/](docs/recipes/)

## Requirements

- PHP 8.1+ with `mysqli`, `curl`, `gd`, `mbstring`, `openssl`, `zip`
- MySQL 8 / MariaDB running locally
- Node/npm (optional; only used for the `npm run` shortcuts)
- No XAMPP/htdocs and no Docker needed. WordPress runs from `.wp/` inside this repo, served by PHP's built-in server.

## Setup

**1. Create the database user** (once). Open a MySQL root shell (`mysql -u root -p`, or MySQL Workbench) and run:

```sql
CREATE DATABASE wp_agent CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'wp_agent'@'localhost' IDENTIFIED BY 'choose-a-password';
GRANT ALL PRIVILEGES ON wp_agent.* TO 'wp_agent'@'localhost';
FLUSH PRIVILEGES;
```

**2. Configure:**

```bash
cp .env.example .env      # then set DB_PASSWORD (and anything else)
```

**3. Install.** This downloads WP-CLI (sha512-verified) and WordPress core, writes wp-config, installs the site, links the theme and seeds demo content:

```bash
npm run setup             # = php bin/setup.php --seed
```

**4. Run** (host/port from `WP_URL` in `.env`):

```bash
npm start                 # http://localhost:8080   admin: http://localhost:8080/wp-admin
```

Re-running setup is safe. It skips steps that are already done.

## Start a new site from this template

This repo is the **template**. Every website is its own copy, with its own database and `.env`. No `npm install` is needed: the project has no npm dependencies, and npm only runs the shortcuts.

```bash
git clone <this-repo> acme-site && cd acme-site        # or GitHub "Use this template"
rm -rf .git && git init                                # fresh history for the site
cp .env.example .env                                   # new DB_NAME, password, WP_URL port (8081 if 8080 is taken)
php bin/rename.php "Acme Studio" acme                  # rebrand theme: name, slug, text domain, PHP prefix
#   add --strip-examples to drop the Event demo, --dry-run to preview
npm run setup                                          # install WordPress into .wp/ and activate the theme
npm start                                              # serves on the WP_URL port
```

Create the site's database first (same SQL as above, with a new DB name). Set `WP_URL` **before** setup. Changing it later requires `wp search-replace`, and `npm start` prints the exact command.

Generic improvements made while building a site (scaffolder, recipes, checks) should be ported back to the template.

## Daily commands

| Command | What it does |
|---|---|
| `npm start` | dev server on the `WP_URL` host/port from `.env` |
| `npm run rename -- "Name" slug` | rebrand a fresh copy (once per site) |
| `npm run make` | scaffolder help (`php bin/make.php <cmd>`) |
| `npm run check` | static checks: PHP lint, JSON, block markup, config shape |
| `npm run check:http` | static + runtime + fetch every key URL (server must be running) |
| `npm run wp -- <args>` | WP-CLI, e.g. `npm run wp -- post list` |
| `npm run seed` | (re)create sample content |
| `composer install && composer lint` | WordPress Coding Standards (PHPCS) |

## What's included

| | Example |
|---|---|
| Custom post type (config) | `theme/config/post-types/event.php` |
| Taxonomy (config) | `theme/config/taxonomies/event-type.php` |
| Native custom fields + admin meta box | `theme/config/meta/event.php` |
| Block templates | `theme/templates/*.html` (index, single, page, archive, search, 404, single-event, archive-event) |
| Meta shown in block templates (Block Bindings) | `theme/templates/single-event.html` |
| User-selectable block templates | `page-no-title`, `page-wide` (theme.json `customTemplates`) |
| User-selectable PHP template | `theme/page-templates/template-landing.php` |
| PHP template-hierarchy override | `theme/taxonomy-event_type.php` |
| Template parts shared by block + PHP | `theme/parts/header.html`, `footer.html` |
| Patterns | `theme/patterns/hero.php`, `call-to-action.php` |
| Custom dynamic block (no build) | `theme/blocks/event-list/` |
| Design tokens | `theme/theme.json` |

## Alternatives to the local PHP server

- **wp-env (Docker):** `npm run env:start`. It uses `.wp-env.json` and its own DB container, not your MySQL.
- **Any existing WordPress** (XAMPP, LocalWP…): copy or symlink `theme/` into `wp-content/themes/agent-theme` and activate it.

## Moving to production

Deploy the `theme/` folder as `wp-content/themes/agent-theme`. Nothing else in the repo is needed at runtime.

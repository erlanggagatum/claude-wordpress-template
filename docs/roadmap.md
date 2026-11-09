# Roadmap & status

Read this at the start of a session to see what's done, what's unverified, and what's next.
Update it when you finish or start something significant. No personal info here (see AGENTS.md guardrails).

## Status

| Area | State |
|---|---|
| Theme engine (config-driven CPT / taxonomy / meta, blocks, patterns) | Built, passes static checks |
| Hybrid templates (block + PHP, shared header/footer) | Built, passes static checks |
| Scaffolder `bin/make.php` | Built, tested on scratch copies |
| Verification `bin/check.php` + privacy scan | Built, tested on planted errors and leaks |
| Rebrand `bin/rename.php` (+ `--strip-examples`) | Built, tested on scratch copies |
| Dev server `bin/serve.php` (port from `.env`) | Works |
| Local install `bin/setup.php` + `bin/seed.php` | Works (fixed: wp-config DB sync from `.env`, Windows path-with-spaces rewrite bug, admin password printed before later steps) |
| Front end (`check.php --http`) | All 11 key URLs 200; meta bindings, Event List block, PHP landing + term-archive override verified in HTML |

## Next

1. [x] First real run: setup, serve, `check:http`.
2. [x] Editor confirmed working (meta box, Event List block, template dropdown, Site Editor).
3. [ ] `git init` plus a pre-commit hook (`.githooks/pre-commit` running `php bin/privacy-check.php`, enabled with `git config core.hooksPath .githooks`).
4. [ ] `composer install && composer lint`, then fix WPCS findings.

## Later / ideas

- ACF or Meta Box support as an optional alternative to native fields (keep native as the default).
- Optional `@wordpress/scripts` build path for blocks that need JSX or InnerBlocks.
- Editor sidebar panel for meta (instead of the classic meta box).
- Deploy recipe (theme zip, DB migration).
- Translations (`languages/*.pot` via `wp i18n make-pot`).

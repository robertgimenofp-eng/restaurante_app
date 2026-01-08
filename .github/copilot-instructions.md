<!--
Do not expand this file with generic policy. Keep short, actionable
guidance for AI coding agents who will edit this PHP MVC project.
-->
# Copilot Instructions — restaurante_app

Purpose: Give AI coding agents the minimal, actionable knowledge to be
productive editing this PHP app (simple MVC scaffold served from
XAMPP).

- Project type: PHP app (lightweight MVC). Root files: `test_db.php`,
  `config/db.php`. Key folders: `controllers/`, `models/`, `views/`,
  `public/` (assets).

- Database: `config/db.php` defines `Database::connect()` using PDO.
  - Typical usage: `require_once __DIR__ . '/config/db.php'; $db = Database::connect();`
  - Default credentials are local (MySQL root, no password) and DB name
    is `restaurante_app`. Don't hard-change credentials in PRs — prefer
    using environment overrides or asking maintainers.
  - Use the `test_db.php` script to validate connectivity when in doubt.

- Conventions and structure (observed):
  - Views are organized under `views/` with subfolders `pedido/`,
    `producto/`, `usuario/` and a `views/layout/` for shared templates.
  - Public static files live in `public/` (CSS/JS/images). Keep web
    accessible assets here and do not place PHP controllers in `public`.
  - `controllers/` and `models/` are present as folders — follow an
    MVC naming style (e.g. `PedidoController.php`, `ProductoModel.php`).
    If adding controllers, require them from a central index/router.

- Coding patterns to follow (based on existing code):
  - Use `PDO` and `Database::connect()` for DB access. Use prepared
    statements and the default fetch mode `PDO::FETCH_ASSOC`.
  - Error handling: `config/db.php` enables `PDO::ERRMODE_EXCEPTION` —
    propagate or catch exceptions consistently (do not suppress them).

- Developer workflows (how to run/debug):
  - The app is intended to run under XAMPP/localhost. Place the repo
    root under your `htdocs` (example path: `c:\xampp\htdocs\restaurante_app`).
  - Start Apache + MySQL in XAMPP, then open `http://localhost /`.
  - Use `test_db.php` to test DB access: `http://localhost /test_db.php`.

- Repo and PR guidance for AI agents:
  - Do not change `config/db.php` credentials silently — add an
    environment-backed option or ask if you need different credentials.
  - Add new PHP classes/files under the matching folder (`controllers/`,
    `models/`, `views/`). Update any central router or index if present.
  - Keep JS/CSS assets in `public/`. If adding uploads, follow `.gitignore`
    (uploads are ignored by default: `/public/uploads/`).

- Tests & migrations: None detected. If you add DB schema changes,
  include a SQL migration file and document how to run it in the PR.

- Files/directories to inspect when making changes:
  - `config/db.php` — DB connection pattern
  - `test_db.php` — quick connectivity check
  - `views/` — where templates for resources live
  - `.gitignore` — files intentionally excluded from VCS (`logs/`, uploads)

If anything here is unclear or you want me to follow a different
controller/view naming pattern, tell me which convention to use and I
will update this guidance and adjust new code accordingly.

# Cular Restructure

Rebuild of the [Cular Creative](https://cularcreative.com) agency site off Elementor onto a lean **WordPress block theme + custom ACF Blocks**, bundled with **Vite**.

## Architecture

- **`theme/`** — the custom block theme (`cular`). This folder is symlinked into the Local site's `wp-content/themes/cular`, so editing here updates the running site live.
- **`theme/blocks/<name>/`** — one folder per reusable component (Gutenberg block). Each has its own `block.json`, `render.php` (PHP markup), `fields.php` (ACF fields), and `<name>.scss`. Edit these to adjust a component.
- **`theme/src/`** — global JS/SCSS entry points, compiled by Vite.
- **`theme/dist/`** — Vite build output (generated, gitignored).

## Requirements

- WordPress 6.5+
- **Advanced Custom Fields PRO** (ACF Blocks require Pro) — install & activate in WP admin.
- Node 20+ / npm

## Dev workflow

```bash
npm install
npm run dev     # Vite dev server with HMR at http://localhost:5173
npm run build   # Production build into theme/dist/
```

## Adding a component (block)

1. Copy `theme/blocks/hero/` to `theme/blocks/<your-block>/`.
2. Update `block.json` (name/title), `fields.php` (ACF fields), `render.php` (markup), and `<your-block>.scss`.
3. It auto-registers — no other wiring needed. Add it to a page from the Gutenberg inserter.

## The Local site

- Path: `C:\Users\Nego\Local Sites\cularcreative\app\public`
- URL: http://cularcreative.local

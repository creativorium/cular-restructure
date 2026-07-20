# Components (blocks)

One folder per component. Nothing else needs wiring — `inc/blocks.php` scans this
directory and auto-registers anything with a `block.json`.

## Anatomy of a component

```
blocks/<name>/
  block.json      Registers the block (name, title, icon, supports).
  fields.php      Editable fields shown in the sidebar (ACF).
  render.php      The HTML. Plain PHP — edit this to change markup.
  <name>.scss     Styles for this component only.
  view.js         Optional front-end behaviour for this component.
```

`.scss` and `view.js` are picked up automatically by Vite (`src/main.js` globs
`blocks/**`), so you never register assets by hand.

## Adding a component

1. Copy an existing folder, e.g. `cp -r blocks/cta blocks/my-block`.
2. Rename the `.scss` file and update `name`/`title` in `block.json`.
3. Point the ACF field group's `location` at your new block name in `fields.php`.
4. `npm run build` — it appears in the editor under the **Cular** category.

## Where content comes from

| Component | Content source |
| --- | --- |
| `site-header` | **Appearance → Menus** (locations: *Primary Menu*, *Social Links*). Falls back to ACF fields, then to hardcoded defaults. |
| `site-footer` | ACF fields on the block, with defaults in `render.php`. |
| `portfolio` | Latest `portfolio_item` posts (featured image + title). |
| `field-notes` | Latest blog posts. |
| `hero` | ACF fields; background video defaults to the site showreel (landscape, with a portrait cut swapped in under 900px). |
| `services`, `cta`, `testimonials` | ACF fields, with real copy as defaults in `render.php`. |

## Current components

- `site-header` — logo, pill Menu button, expanding side panel
- `site-footer` — headline, link columns, newsletter, social
- `hero` — full-bleed background video + waterfall scroll indicator
- `services` — 100vh animated mesh-gradient band with two cards
- `portfolio` — grid of recent projects
- `testimonials` — client quotes
- `field-notes` — latest blog posts
- `cta` — green card with gold-highlighted heading

## Conventions

- Class names are BEM-ish and prefixed `cular-` to avoid collisions.
- Brand tokens live in `theme.json` (colours, fonts) and are used via
  `var(--wp--preset--color--*)` / `var(--cular-green)` etc. Don't hardcode hexes.
- Global chrome (WhatsApp button) lives in `inc/site-chrome.php`.
- Elementor's frontend assets are dequeued on block pages by
  `inc/elementor-offload.php`.

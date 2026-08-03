# Cular Creative — Rebuild Project Guide

The single source of truth for how this project is built, why, and what's left.
Written so any developer — or another AI agent — can pick it up and continue
without re-deriving context.

> **One-line summary:** rebuilding [cularcreative.com](https://cularcreative.com)
> off a heavy Elementor build onto a lean **WordPress block theme + custom ACF
> Blocks**, bundled with **Vite**, matching the live design 1:1 while being fast,
> SEO-clean, and easy to maintain.

---

## 1. Goals & principles

- **Match the live site** visually and in content, section by section.
- **Drop Elementor.** Every component is native WordPress + our own SCSS/JS. No
  Elementor dependency remains in what we ship, so Elementor and its add-ons can
  be removed at the end.
- **Clean, not 1:1 cruft.** The old site was built by many hands and is messy /
  heavy. We port only what's actually used (CSS, JS, images) — see
  `reference/` extraction and the media audit.
- **Editable by a non-dev.** Content comes from ACF fields, WP menus, and CPTs —
  not hardcoded — so the owner can adjust it.
- **Fast + SEO-optimised.** Target all-green Core Web Vitals and clean semantic
  markup with structured data. See §9 and §10.
- **Component-per-folder.** Each block lives in its own folder for easy editing
  and rebuilding.

## 2. Tech stack

| Layer | Choice |
| --- | --- |
| CMS | WordPress (block/FSE theme) |
| Theme | Custom **`cular`** block theme (`theme/`) |
| Components | **ACF PRO Blocks** — one folder per block under `theme/blocks/` |
| Build | **Vite 6** — bundles all block SCSS + JS into `theme/dist/` |
| Motion | **GSAP** (+ ScrollTrigger) and **Lenis** smooth scroll, npm-bundled |
| Fonts | Self-hosted **Luxia Display** (headings) + **Montserrat** (body) |
| Local dev | **Local by Flywheel** (MySQL 8.4, PHP 8.2, nginx) |
| Hosting (target) | Hostinger or DigitalOcean |

## 3. Repo & theme structure

```
Redesign/                     git root -> github.com/creativorium/cular-restructure
  vite.config.js              Vite config (root = theme/, outputs theme/dist)
  package.json                gsap, lenis, sass, vite
  theme/                      the block theme (symlinked into the Local site)
    style.css theme.json      theme header + design tokens (colours, fonts)
    functions.php             bootstraps inc/*
    inc/
      enqueue.php             loads Vite manifest assets (dev + prod)
      blocks.php              auto-registers every block under blocks/
      block-category.php      "Cular" inserter category
      nav.php                 reads Appearance > Menus
      media.php               shared gallery/image helpers
      site-chrome.php         global WhatsApp button
      elementor-offload.php   dequeues Elementor CSS/JS on non-Elementor pages
      single-post.php         single-post SEO (h1, footnotes, Article JSON-LD)
    templates/                FSE templates: index, page, home (blog), single, front-page
    parts/                    header + footer template parts (render our blocks)
    blocks/<name>/            one folder per component (see §5)
    src/                      global JS/SCSS entry (main.js, styles/, slider.js)
    assets/                   self-hosted fonts + logos + spotlight image
    dist/                     Vite build output (gitignored)
  reference/                  LOCAL-ONLY (gitignored): extracted proprietary code,
                              rendered HTML, screenshots, Playwright check scripts
  docs/PROJECT.md             this file
```

**`reference/` is gitignored** — it holds the extracted Elementor code, rendered
HTML captures, media audit, and the Playwright verification scripts. It never
goes to GitHub (owner's request).

## 4. Local dev & build

- **Site:** `http://cularcreative.local` (Local by Flywheel). Path:
  `C:\Users\Nego\Local Sites\cularcreative\app\public`.
- The theme is **symlinked**: `wp-content/themes/cular` → this repo's `theme/`.
  So the checked-out git branch is what renders live.
- **Build:** `npm install` then `npm run build` (or `npm run dev` for HMR).
- **DB:** local/root/root on port 10029 (Local's MySQL). Boot WP from CLI with
  Local's PHP + `PHPRC` for scripts (see `reference/` scratch scripts).
- **Visual QA:** Playwright (installed with `--no-save`) drives the real site and
  screenshots; check scripts live in `reference/*.mjs`.

## 5. Component (block) system

Convention — nothing else needs wiring, `inc/blocks.php` scans `blocks/`:

```
blocks/<name>/
  block.json     registers the block (name cular/<name>, "acf" render template)
  fields.php     ACF field group (acf_add_local_field_group), located to the block
  render.php     the markup (plain PHP) — edit this
  <name>.scss    styles for this component only (auto-bundled by Vite glob)
  view.js        optional front-end behaviour (auto-bundled)
```

- Block names are `cular/<name>`; class names are BEM-ish, prefixed `cular-`.
- Brand tokens live in `theme.json` and are used via
  `var(--wp--preset--color--*)` / `var(--cular-green|gold|sage)` — **never
  hardcode hex** (see §11 tokens).
- Add a component: copy a folder, rename, update `block.json` + `fields.php`
  location + SCSS, `npm run build`.

### Current blocks

`site-header` (pill menu + animated side panel), `site-footer`, `hero`
(bg video + waterfall scroll), `services` (100vh mesh gradient), `team-intro`,
`portfolio` (curated `portfolio_item`s), `why-us` (logo marquee + badges),
`testimonials` (video + written sliders), `field-notes` (homepage teaser),
`field-notes-archive` (blog index), `cta` (spotlight heading), `about-intro`,
`team-grid`, `post-share`, `related-posts`.

Shared: `src/slider.js` (scroll-snap slider used by testimonials + field notes),
`src/styles/main.scss` (tokens, fonts, `.cular-gradient-mesh` [+`--green`],
WhatsApp button, reveal helpers), `pages.scss` (FAQ/Privacy), `single-post.scss`.

## 6. Pages — status & URLs

Converted **in place** at their real URLs. Each converted page's previous
Elementor state is backed up to post meta (`_cular_prev_edit_mode`,
`_cular_prev_template`) — fully restorable, no `-old` duplicates.

| Page | URL | Status |
| --- | --- | --- |
| Home | `/` | ✅ full rebuild (hero → services → team → portfolio → why-us → testimonials → field notes → CTA) |
| About | `/about/` | ✅ about-intro + team grid |
| FAQ | `/faqs/` | ✅ native accordion, green page |
| Privacy & Terms | `/privacy-terms/` | ✅ native sections, green page |
| Field Notes (blog) | `/blog/` | ✅ archive (set as WP posts page) |
| Single post | `/<slug>/` | ✅ single.html + share + related + SEO |
| Marketing Services | `/activate/` | ⬜ still Elementor |
| Consultancy | `/elevate/` | ⬜ still Elementor |
| Service detail pages | `/social-media/`, `/seo/`, `/web-development/`, `/content-creation/`, `/graphic-design/`, `/digital-advertising/`, `/copywriting/` | ⬜ still Elementor |
| Case Study | `/case-study/` | ⬜ still Elementor |
| Portfolio listing | `/portfolio-cular/` | ⬜ still Elementor |
| Single portfolio | `/portfolio-cular/<slug>/` | ⬜ still Elementor |
| Contact | `/contact/` | ⬜ still Elementor (has forms — see §12) |
| Rate card / ads landing pages | various | ⬜ low priority |

Not-yet-rebuilt pages keep working on Elementor at their real URL until we
rebuild them; the nav/footer links then resolve to the new page automatically.

## 7. Data sources

- **Menus:** Appearance → Menus, locations **Primary Menu** / **Social Links**
  (`inc/nav.php`); header falls back to ACF then hardcoded defaults.
- **Portfolio:** `portfolio_item` CPT + `portfolio_tag` taxonomy; card art in
  meta `card_title`, `video_url`, `overlay_logo_id`, `external_link`.
- **Team:** photos in uploads; roster is a default array in `team-grid` (editable
  via ACF repeater).
- **Brand tokens:** `theme.json` (green `#457F55`, gold `#F8CE4A`, sage
  `#A0B89D`, orange `#E9765B`; fonts Luxia Display + Montserrat).

## 8. Git workflow

**Every change on its own branch**, `type/short-name`:
`feat/…` (new section/component), `fix/…` (hotfix), `content/…`, `chore/…`.
Commit → push branch → **merge into `main` with `--no-ff`** (clear merge commit)
→ push main → **end on `main`** (the Local site renders the checked-out branch).
Branches are **kept** on GitHub for history/rollback.

## 9. Performance plan (page speed — target all green)

Targets: **LCP < 2.5s, CLS < 0.1, INP < 200ms**, Lighthouse 90+ on mobile.

Done:
- Elementor CSS/JS **dequeued** on block pages (homepage went 190KB → ~52KB).
- One Vite bundle (CSS ~a few KB gzip; JS ~52KB gzip incl. GSAP + Lenis).
- Images `loading="lazy"`; `svh` on hero to avoid mobile CLS; reveal/animation
  respect `prefers-reduced-motion`.

To do (ordered by impact):
1. **Images → WebP/AVIF + right sizes.** Uploads are 5.7 GB, mostly oversized
   JP/PNG. Convert survivors, add `srcset`/`sizes`, strip metadata. Biggest LCP
   win. (See §12 media cleanup.)
2. **Fonts:** subset + convert TTF → **WOFF2** (Montserrat is a 688KB variable
   TTF — subset to Latin + needed weights; `font-display: swap` already set).
3. **Hero video:** serve a poster image, `preload="none"` on mobile, and a
   smaller/compressed showreel; consider AV1/WebM at lower bitrate.
4. **Defer/async JS**, keep the single bundle; split rarely-used block JS if it
   grows. Consider `@wordpress/scripts`-style code-splitting only if needed.
5. **Critical CSS** inline for above-the-fold; the rest async (optional; Vite can
   emit a critical chunk).
6. **Caching + CDN** in production (page cache + Cloudflare/host CDN). Do **not**
   reuse the old NitroPack/WP Rocket configs — start clean.
7. **DB:** search-replace to the production domain on deploy; remove leftover
   Newfold/Elementor options; keep autoloaded options small.
8. Lighthouse/PSI pass per page before launch; fix any CLS from late-loading
   images (set width/height or aspect-ratio — mostly done).

## 10. SEO plan

Done:
- Semantic headings (one `h1` per page; single posts promote the first content
  `h2` → `h1`).
- **Structured data:** `BlogPosting` microdata on archive cards; `BlogPosting`
  JSON-LD in `<head>` on single posts (`inc/single-post.php`).
- **Internal linking:** related posts on singles; archive cards link to posts.
- **External links** get `rel="noopener noreferrer"` + a generated **Sources**
  footnote list.
- Clean URLs preserved (in-place conversion keeps slugs; no redirects needed).

To do:
1. **Meta titles/descriptions + OG/Twitter.** Yoast is installed but currently
   deactivated on local. Decide: keep Yoast (re-enable, it handles titles, OG,
   sitemaps, breadcrumbs, schema) **or** replace with a lighter plugin
   (SEOPress / Rank Math / The SEO Framework). Recommendation: **keep Yoast** for
   sitemaps + OG + titles; our JSON-LD complements it (avoid duplicate Article
   schema — if Yoast emits Article, drop ours or vice-versa).
2. **XML sitemap + robots.txt** (Yoast provides; verify posts/pages/CPTs
   included, noindex thin/utility pages).
3. **Image alt text** — ensure every content image has meaningful alt (audit).
4. **Canonical + hreflang** (single-language now; canonical via Yoast).
5. **Breadcrumbs** with `BreadcrumbList` schema (Yoast or custom).
6. **Organization / WebSite schema** sitewide (logo, sameAs socials) — Yoast or a
   small `inc/schema.php`.
7. **Redirects:** since we convert in place, slugs are unchanged — but audit any
   Elementor pages we later delete/rename and 301 them (Redirection plugin still
   installed).
8. **Performance is SEO** — see §9 (CWV are ranking signals).
9. Add `Article`/`FAQPage` schema to the FAQ page (native accordions → `FAQPage`
   JSON-LD is an easy win).

## 11. Design tokens (theme.json)

Colours: `primary/green #457F55`, `sage #A0B89D`, `gold #F8CE4A`,
`orange #E9765B`, `cream #eef1ea`, `contrast #111`, `base #fff`.
Fonts: `display` = Luxia Display (headings), `body` = Montserrat.
Signature effects: animated mesh/blob gradient (`.cular-gradient-mesh`, warm; and
`--green` for the blog), CTA "spotlight" sweep, header side-panel, waterfall
scroll indicator, GSAP reveals.

## 12. Media cleanup (the ~3 GB)

Audit (`reference/media-audit/`): **5.7 GB / 10,019 files**, ~**3 GB
reclaimable** — 976 unused attachments + 561 orphans (giant stock JPGs,
duplicate video exports, Elementor form-submission PDFs).

⚠️ **Re-audit against the NEW site first** — hero, team, testimonials and
portfolio all depend on videos the original scan flagged as "unused". Plan:
1. Re-scan referenced media on the block site.
2. Compress survivors to WebP/AVIF + correct sizes.
3. Delete orphans + confirmed-unused (keep originals until verified; owner
   authorised deletion "at the end").

### 12a. Media manifest — track what we actually use ⬅ **do this as we build**

We now know exactly which images/videos each rebuilt component references, and
that knowledge is worth capturing **while we build** rather than reconstructing
it at the end. At launch we want two lists: *keep + compress* and *delete*.

**Where media references come from** (all four must be scanned — a file used by
only one of these is still in use):

| Source | How to find it |
| --- | --- |
| Block defaults hardcoded in PHP | `grep -rn "uploads/" theme/blocks/` |
| Theme's own assets | `theme/assets/img/`, `theme/assets/fonts/` |
| ACF fields + post meta | attachment IDs in `wp_postmeta` (portfolio `video_url`, `overlay_logo_id`, team `photo`, block JSON in `post_content`) |
| Post/page content | `<img>`, `<video>`, and background-image URLs inside `post_content` |
| Featured images | `_thumbnail_id` on every post/page/CPT |

**Known-referenced media so far** (keep + compress; update as pages land):

- **Theme assets:** `logo-full.png`, `logo-green.png`, `spotlight.png`,
  `team-card-bg.jpg`, `team-soon-mark.png`, the three font TTFs.
- **Hero:** showreel video (landscape + portrait cuts).
- **Team (About):** 11 member cut-outs — see the roster in
  `blocks/team-grid/render.php`.
- **Testimonials:** video testimonials + brand logos.
- **Portfolio:** `portfolio_item` card art + videos.
- **Field Notes:** the featured image of every published post.
- **Why-us:** client logo marquee.

**Method when the rebuild is done:**
1. Write `reference/media-manifest.mjs` — crawl every rebuilt URL with
   Playwright and collect `img[src]`, `img[srcset]`, `video/source[src]`, and
   computed `background-image` for every element. That catches CSS-only
   references the DB scan misses.
2. Union that with the DB scan (the four sources above) → **used set**.
3. `used set` vs `wp_posts` attachments → **orphan set**.
4. Compress the used set (WebP/AVIF, correct dimensions, strip EXIF); delete
   the orphan set only after the owner signs off, keeping a full uploads
   backup until the production site is verified.

> Do **not** rely on the original `reference/media-audit/` numbers for the
> delete list — that scan predates the rebuild and marks in-use videos as
> unused.

## 13. Migration & deploy notes

- **Elementor removal (final step):** once all pages are rebuilt, deactivate
  Elementor + Elementor Pro + `dynamic-content-for-elementor` +
  `extensions-for-elementor-form` + `pro-elements`. Verify no page 500s
  (all rebuilt pages have `_cular_prev_*` backups if rollback needed).
- **Contact forms:** the Contact page uses Elementor Pro forms + WPForms. Before
  dropping Elementor, rebuild the contact form (WPForms is kept active, or a
  lightweight custom form + a mailer). This is a **blocker** for removing
  Elementor — do it with the Contact page rebuild.
- **Deactivated on local (for a lighter dev site):** Wordfence, WP Rocket,
  WP-Optimize, Site Kit, PixelYourSite, UpdraftPlus, WP Mail SMTP,
  wpforms-geolocation, wpforms-user-journey, simple-history, Yoast. Original
  active-plugins list backed up in option `cular_active_plugins_backup`.
  Re-enable the ones needed in production (Yoast, caching, SMTP, analytics).
- **Newfold/Bluehost cruft removed** (was the "self-reinstalling plugin"):
  files quarantined to `wp-content/_quarantine-bluehost/`, options + `mm_cron`
  removed. Keep off Bluehost.
- **Production move:** export DB, `wp search-replace cularcreative.local
  cularcreative.com` (serialized-safe), sync uploads (post-cleanup),
  deploy the theme (run `npm run build`, commit `dist/` or build on server).

## 14. Roadmap (suggested order)

1. **Contact page** + working form (unblocks Elementor removal).
2. **Marketing Services `/activate/`** and **Consultancy `/elevate/`** (hub
   pages), then the 7 service detail pages (likely one reusable `cular/service`
   block/template).
3. **Portfolio listing** + **single `portfolio_item`** (case-study layout).
4. **Case Study `/case-study/`**.
5. **Media cleanup** (§12) + **performance pass** (§9).
6. **SEO pass** (§10): re-enable Yoast, sitemaps, OG, alt-text audit, FAQ schema.
7. **Remove Elementor** + dependencies; final QA + Lighthouse per page.
8. **Deploy** to Hostinger/DO.

## 15. Suggestions & improvements

- **Reusable `cular/section` primitives** (heading + intro + CTA) to speed up the
  service pages instead of a block per page.
- **Editor UX:** register block patterns (pre-composed sections) so the owner can
  drop full sections in Gutenberg.
- **ACF JSON sync:** enable `acf-json/` in the theme so field groups are
  version-controlled and portable (currently fields are PHP-registered per
  block, which is fine and already in git — optional).
- **Testing:** keep the Playwright check scripts; consider a tiny visual-regression
  baseline per page to catch layout drift.
- **Accessibility pass:** focus states, skip-link, `aria-current` on nav, colour
  contrast on gold-on-white, video captions for testimonials.
- **Analytics/consent:** re-add GTM/Hotjar via a consent-aware loader (they were
  Elementor custom-code snippets; port to a small `inc/analytics.php` gated by a
  cookie-consent choice).
- **i18n:** wrap user-facing strings in `__()` if a second language is ever
  needed (Bahasa Indonesia audiences are mentioned in the FAQ).
- **Newsletter:** the footer form is currently visual only — wire it to the real
  provider (Mailchimp/WPForms) when known.

## 16. Known content notes (not bugs)

- Several recent posts are **duplicates sharing one featured image**
  (`Artboard-4-1-1.png`); the archive de-dupes images across cards, but assign
  distinct featured images for best results.
- Some posts author the **title inside the content** as an `h2`; the single-post
  filter promotes the first `h2` → `h1` to avoid a duplicate heading.
- **No logo file exists** for a couple of testimonial brands (Vifa, Luna & Sol) —
  they fall back to a serif wordmark; add logos to replace.

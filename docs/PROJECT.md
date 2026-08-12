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
| Motion | **Lenis** smooth scroll (npm) + IntersectionObserver/CSS reveals. GSAP was removed — see §9 |
| Fonts | Self-hosted **Luxia Display** (headings) + **Montserrat** (body), subset WOFF2 |
| Local dev | **Local by Flywheel** (MySQL 8.4, PHP 8.2, nginx) |
| Hosting (target) | Hostinger or DigitalOcean |

## 3. Repo & theme structure

```
Redesign/                     git root -> github.com/creativorium/cular-restructure
  vite.config.js              Vite config (root = theme/, outputs theme/dist)
  package.json                lenis, sass, vite (+ the scripts in §4)
  tools/                      build-fonts.mjs, build-images.mjs (asset pipelines)
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
    src/                      global JS/SCSS entry (main.js, styles/, slider.js, reveal.js)
    assets/                   fonts + img, each with a src/ holding the originals
    dist/                     Vite build output (gitignored)
  reference/                  LOCAL-ONLY (gitignored): extracted proprietary code,
                              rendered HTML, screenshots, Playwright check scripts,
                              media-crawl.mjs + media-scan.php + media-manifest/
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
- **Other scripts** (all optional — their outputs are committed, so you only run
  them when the inputs change):

  | Command | Does |
  | --- | --- |
  | `npm run fonts` | Re-subset the brand fonts to WOFF2. Needs `pip install fonttools brotli`. |
  | `npm run images` | Re-optimise `theme/assets/img/` from `img/src/`. Needs `pip install pillow`. |
  | `npm run media:manifest` | Rebuild the used/unused uploads lists (§12a). Needs the Local site running. |

- `reference/run-php.mjs` runs any WP-booting PHP script with Local's own PHP +
  `php.ini` (the system PHP has no `mysqli`, so `wp-load.php` dies). Use it
  instead of hand-rolling PHPRC paths.
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
`team-grid`, `post-share`, `related-posts`, `page-hero` (shared inner-page
hero), `service-list` (hub page card grid), `service-detail` (child service
page), `cta-panel`, `faq`, `contact` (intake form).

Shared: `src/slider.js` (scroll-snap slider used by testimonials + field notes),
`src/reveal.js` (IntersectionObserver reveals + hero parallax),
`src/styles/main.scss` (tokens, fonts, `.cular-gradient-mesh` [+`--green`],
WhatsApp button, reveal CSS), `pages.scss` (FAQ/Privacy), `single-post.scss`.

## 6. Pages — status & URLs

Converted **in place** at their real URLs. Each converted page's previous
Elementor state is backed up to post meta (`_cular_prev_edit_mode`,
`_cular_prev_template`) — fully restorable, no `-old` duplicates.

Status below is generated from the database (`_elementor_edit_mode` vs. our
`_cular_prev_edit_mode` backup marker), not from memory — re-derive it rather
than trusting this table if it looks stale.

### Rebuilt on our blocks ✅

| Page | URL |
| --- | --- |
| Home | `/` (page `home-cular`, set as the front page) |
| About | `/about/` — about-intro + team grid |
| FAQ | `/faqs/` — native accordion, grouped into category cards |
| Privacy & Terms | `/privacy-terms/` — native sections, green page |
| Field Notes (blog) | `/blog/` — archive, set as the WP posts page |
| Single post | `/<slug>/` — single.html + share + related + SEO |
| Contact | `/contact/` — green hero + working intake form |
| Marketing Services (hub) | `/activate/` |
| ↳ 8 service detail pages | `/activate/` + `social-media`, `seo`, `web-development`, `content-creation`, `graphic-design`, `advertising`, `copywriting`, `branding-identity` |
| Consultancy (hub) | `/elevate/` |
| ↳ 3 detail pages | `/elevate/` + `consultacy`, `marketing-audit`, `blueprint-strategy` |

Note the service pages live **under their hub** (`/activate/seo/`), not at the
top level — the old `/seo/` URLs in an earlier draft of this doc never existed.

### Still on Elementor ⬜

85 pages. Grouped by what they need, not by count:

| Group | Count | URL(s) | Notes |
| --- | --- | --- | --- |
| **Portfolio single pages** | 47 | `/portfolio-cular/<slug>/` | The content already exists twice: these Elementor pages *and* the 44 `portfolio_item` CPT entries we built the homepage carousel from. Needs one `single-portfolio_item.html` template, then these are redundant → 301 to the CPT. |
| **Portfolio listing** | 2 | `/portfolio-cular/`, `/cular-portfolio/` | Two competing listings; `/portfolio-cular/` is the one in the nav. |
| **Paid landing pages** | 10 | `/ads-pages/*` | Live ad traffic — check with the owner before touching. Several are form pages. |
| **Standalone form pages** | 10 | `/form/*` | All Elementor Pro forms. Blocker for removing Elementor (§13); the `cular/contact` intake form is the pattern to reuse. |
| **Case study** | 5 | `/case-study/`, `/case-study-test/`, `/case-study-insurance-platform/`, `/case-study-pilates-combo/`, `/case-study-draft/` | Only `/case-study/` is in the nav; the other four look like drafts/experiments — confirm before rebuilding all five. |
| **Rate card** | 4 | `/book-ratecard/`, `/book-ratecard-2/`, + 2 drafts | Low priority. |
| **Old homepage** | 1 | `/cular-creative/` | ⚠️ **The "Home" item in the Primary Menu still points here**, not at `/`. Anyone using the menu lands on the old Elementor homepage. Fix in Appearance → Menus. |
| **Odds and ends** | 6 | `/hiring/`, `/let-us-know-how-we-did/`, `/mau-omset-shopee-anda-naik-hingga-250/`, `/cular-business-enquiry-landing-page/`, `/field-note-preview/`, 1 untitled draft | Mostly disposable — audit for traffic, then delete or 301. |

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
- Images `loading="lazy"`; `svh` on hero to avoid mobile CLS; reveal/animation
  respect `prefers-reduced-motion`.
- **Fonts → subset WOFF2.** `npm run fonts` (`tools/build-fonts.mjs`) subsets to
  Latin + the punctuation we actually set. Montserrat **688KB TTF → 42KB WOFF2**
  with its 100–900 variable axis intact; Luxia 19KB → 7KB. TTF originals moved to
  `theme/assets/fonts/src/`. Unused `LuxiaRegular.ttf` deleted. Both fonts are
  now `rel="preload"`ed, with the hashed href read from the Vite manifest so the
  preload primes the same URL the CSS asks for instead of fetching a second copy.
- **GSAP + ScrollTrigger removed.** They were ~117KB minified serving exactly two
  effects — a fade-and-rise on scroll and one parallax transform. Now
  IntersectionObserver + a CSS transition, and six lines of rAF, in
  `src/reveal.js`. Easing matched to GSAP's `power3.out`, so the motion is
  unchanged. Nothing in the theme or in any page's content used `window.gsap`
  (checked against `post_content` before removing). Lenis stays (~15KB) — it is
  the smooth-scroll feel.
  **JS bundle 141KB → 26.6KB raw, 52.9KB → 7.8KB gzip.**
- **Theme's own images optimised.** `npm run images` (`tools/build-images.mjs`):
  logos resized to 2× their largest render size, CSS backgrounds converted to
  WebP. **427KB → 46KB**; `spotlight.png` alone went 227KB → 6KB. Originals in
  `theme/assets/img/src/`. Header and footer logos got explicit `width`/`height`
  to stop them reflowing the fixed header.
- Scroll chrome (scroll indicator + header glass plate) coalesced from two
  per-event listeners into one rAF-throttled pass that only touches the DOM on a
  threshold crossing. The header's `backdrop-filter` is now `visibility: hidden`
  at the top of the page rather than merely transparent, so the compositor drops
  the blur readback entirely until it is needed.

To do (ordered by impact):
1. **Uploads → WebP/AVIF + right sizes.** The 906-file keep set is 534 MB and
   still mostly oversized JPG/PNG plus uncompressed video. Convert, add
   `srcset`/`sizes`, strip metadata. Biggest remaining LCP win. (See §12a.)
2. **Hero video:** the showreel is a **120 MB** `.webm`. Serve a poster image,
   `preload="none"` on mobile, and a properly compressed cut (AV1/WebM at a sane
   bitrate). This is now by far the heaviest single asset on the site.
3. **Third-party tags.** GTM / Google Ads / Analytics currently load on every
   page and are the largest remaining third-party cost. Port them to a
   consent-aware loader (§15) so they load after interaction, not on paint.
4. **Critical CSS** inline for above-the-fold; the rest async (optional; Vite can
   emit a critical chunk). CSS is 72KB raw / 17KB gzip — worth doing only after
   the image work.
5. **Caching + CDN** in production (page cache + Cloudflare/host CDN). Do **not**
   reuse the old NitroPack/WP Rocket configs — start clean.
6. **DB:** search-replace to the production domain on deploy; remove leftover
   Newfold/Elementor options; keep autoloaded options small.
7. Lighthouse/PSI pass per page before launch; fix any CLS from late-loading
   images (set width/height or aspect-ratio — mostly done).

**Regression check:** `node reference/verify-light.mjs` drives 7 representative
pages and asserts that every `[data-cular-reveal]` element ends up visible, that
the WOFF2 fonts load and no `.ttf` is requested, that the menu opens and closes,
that rapid double-toggling doesn't strand it, and that no first-party request
fails. Run it after touching motion, fonts, or assets.

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

### 12a. Media manifest — what we actually use ✅ **built, re-run it as you build**

The manifest is now a script, not a memory exercise. **Re-run it after every
page you convert** — a page that moves off Elementor changes which uploads count
as in use, in both directions.

```bash
npm run media:manifest     # crawl + scan (crawl needs the Local site running)
npm run media:crawl        # browser pass only
npm run media:scan         # DB/code pass only (reuses the last crawl)
```

Two passes, because neither is complete on its own:

| Pass | File | Catches |
| --- | --- | --- |
| Browser crawl | `reference/media-crawl.mjs` | What a real browser actually downloads on every rebuilt URL, desktop **and** mobile, scrolled to the bottom: `img[src\|srcset]`, `video`/`source`/`poster`, media links, and computed `background-image` on every element. This is the only pass that sees CSS-only references. |
| DB + code scan | `reference/media-scan.php` | Hardcoded `uploads/` URLs in theme code; attachment IDs and URLs in `wp_postmeta` (ACF fields, `video_url`, `overlay_logo_id`, `_thumbnail_id`); `post_content` of every post we are **keeping**; `wp_options`. Expands each attachment to all its generated sizes, so a thumbnail reference never strands the full-size original. |

Output lands in `reference/media-manifest/` (gitignored):
`used.txt` (keep + compress, with *why* each file is kept), `unused.txt`
(attachments nothing references), `orphans.txt` (files on disk with no
attachment row), `summary.txt`.

**Current numbers** (all 19 rebuilt URLs crawled):

```
Uploads on disk:      10,011 files, 5.7 GB
Referenced (keep):       906 files, 534 MB
Unused attachments:    8,717 files, 5.0 GB
Orphan files:            388 files, 120 MB   (mostly Elementor form-submission PDFs)
Reclaimable:                       5.1 GB
```

Two things to know before trusting a delete list:

- **`post_content` of Elementor pages we intend to delete is deliberately
  excluded** from the used set — otherwise every image on every page we are
  about to throw away keeps itself alive and the reclaimable figure collapses to
  nothing. The flip side: convert a page, re-run the scan, or you may delete
  something that page still needs. Featured images are still counted for *all*
  posts, including Elementor ones, as a deliberate safety margin.
- **Numeric post meta is only read as an attachment ID when the meta key does
  not start with `_`.** Without that guard `_wp_attachment_metadata` — a
  serialized blob full of pixel widths and heights — matches a width of 1024
  against attachment #1024 and marks essentially the entire library as used.
  That bug made the first run report 1,852 files as in use instead of 906.

Sanity checks that the current run passes: the 120MB hero showreel is kept
while its five duplicate exports are flagged unused; all 24 testimonial and
portfolio videos are kept; only 4 referenced files are missing from disk.

**When the rebuild is done:** compress the used set (WebP/AVIF, correct
dimensions, strip EXIF), then delete the orphan + unused sets — only after the
owner signs off, and keeping a full uploads backup until production is verified.

> Do **not** rely on the original `reference/media-audit/` numbers for the
> delete list — that scan predates the rebuild and marks in-use videos as
> unused. Use `reference/media-manifest/` instead.

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

1. ~~Contact page + working form~~ ✅
2. ~~Marketing Services `/activate/` + Consultancy `/elevate/` hubs and their 11
   child service pages~~ ✅
3. **Fix the "Home" menu item** — it still points at the old Elementor
   `/cular-creative/` instead of `/`. One-minute fix, currently sending every
   menu user to the wrong homepage.
4. **Single `portfolio_item` template** + **portfolio listing** (`/portfolio-cular/`).
   Rebuilding the listing on the CPT makes 47 Elementor pages redundant in one
   move — by far the best ratio of work to Elementor removed.
5. **Case Study `/case-study/`** (confirm which of the five are real first).
6. **`/form/*` pages** — 10 Elementor Pro forms. Reuse the `cular/contact`
   intake pattern. This is the last hard blocker for removing Elementor.
7. **`/ads-pages/*`** — check with the owner for live ad spend before touching.
8. **Media cleanup** (§12a) + the remaining **performance work** (§9): uploads to
   WebP/AVIF, hero video compression, third-party tag deferral.
9. **SEO pass** (§10): re-enable Yoast, sitemaps, OG, alt-text audit, FAQ schema.
10. **Remove Elementor** + dependencies; final QA + Lighthouse per page.
11. **Deploy** to Hostinger/DO.

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

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
  plugins/cular-intake-form/  the intake-forms plugin (junctioned into WP, §7a)
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
      elementor-offload.php   dequeues Elementor CSS/JS by asset source path
      single-post.php         single-post SEO (h1, footnotes, Article JSON-LD)
      portfolio.php           links portfolio_item <-> its case-study page
      seo.php                 schema graph, redirects, noindex, h1 hygiene
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
page), `cta-panel`, `faq`, `contact` (intake form), `case-study` (client case
study + dynamic related-work strip), `portfolio-archive` (full grid + service
filters).

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
| **Portfolio listing** | `/portfolio-cular/` and `/cular-portfolio/` — `cular/portfolio-archive` |
| **47 case studies** | `/portfolio-cular/<slug>/` — `cular/case-study` |
| **Case study pages** | `/case-study/` + `-test`, `-insurance-platform`, `-pilates-combo`, `-draft` |
| **10 paid landing pages** | `/ads-pages/*` — WPForms embeds preserved |
| **Rate card** | `/book-ratecard/`, `/book-ratecard-2/`, `/cular-creative-rate-card/`, `-preview` |
| **One-offs** | `/hiring/`, `/let-us-know-how-we-did/`, `/mau-omset-.../`, `/cular-business-enquiry-landing-page/`, `/field-note-preview/` |

Note the service pages live **under their hub** (`/activate/seo/`), not at the
top level — the old `/seo/` URLs in an earlier draft of this doc never existed.

**94 pages are off Elementor.** Run `node reference/verify-all.mjs` to re-check
them all (see §6a).

| **10 form pages** | `/form/*` — all on our intake forms (§7a) |

### Still on Elementor ⬜

**2 pages, and neither is blocking.** Elementor can now be deactivated — see §13.

| Group | Count | URL(s) | Notes |
| --- | --- | --- | --- |
| **Old homepage** | 1 | `/cular-creative/` | Deliberately left. It **301s to `/`** (`cular_redirects()` in `inc/seo.php`) rather than being rebuilt — rebuilding it would have created a second page serving the homepage's content. |
| **Untitled draft** | 1 | — | Empty; delete it. |

Not-yet-rebuilt pages keep working on Elementor at their real URL until we
rebuild them; the nav/footer links then resolve to the new page automatically.

### 6a. How pages get converted

Conversion is scripted, not hand-built. Three pieces:

| File | Job |
| --- | --- |
| `reference/elementor-extract.php` | Walks `_elementor_data` and emits presentation-free blocks (`heading`, `text`, `list`, `image`, `gallery`, `video`, `shortcode`, `form`). `--audit [--uri=<fragment>]` reports **unrecognised widget types instead of dropping them**, which is the whole point — run it before converting anything new. |
| `reference/build-portfolio.php` | The 47 case studies → `page-hero` + `case-study`. |
| `reference/build-pages.php` | `--group=listing\|case\|ads\|misc\|all` for everything else. |

Both builders are **dry-run by default**; pass `--apply` to write and `--revert`
to restore from the `_cular_prev_*` backups.

Three traps worth knowing before writing any converted page:

1. **`wp_slash()` the content.** `wp_update_post()` unslashes its input, which
   eats the `\uXXXX` escapes inside block-attribute JSON and breaks the block
   delimiter — the page then renders its own block comment as visible text.
2. **`kses_remove_filters()` first.** From CLI there is no logged-in user, so WP
   treats the write as untrusted and escapes `<!-- wp:… -->` into plain text.
3. **The delimiter is `wp:cular/<name>`**, not `wp:acf/cular-<name>`.

And two content traps the audit caught, both of which would have shipped
silently-broken pages:

- **`shortcode` widgets must not be ignored.** The paid landing pages embed
  their forms with `[wpforms]` and the rate-card pages are nothing but
  `[cular_rate_card]`. Ignoring the widget emptied five pages, three of them
  taking live ad traffic. The case-study block runs `do_shortcode()` *after*
  kses so these still work.
- **Elementor Pro `form` widgets are never converted to markup.** A dead HTML
  copy of a lead-capture form looks right and quietly drops enquiries. The
  extractor emits a `form` marker and the builder attaches the real
  `cular/contact` intake form instead.

## 7a. Forms & enquiry tracking

Every form on the site is a **Cular Intake Form**. One pipeline, one admin
screen, one table — so "how many enquiries did we get, and for what?" has a
single answer instead of three plugins' worth.

### Where the plugin lives ⚠️

`plugins/cular-intake-form/` **in this repo**, junctioned into the Local site at
`wp-content/plugins/cular-intake-form` — the same arrangement as the theme.

It used to live only inside WordPress, untracked, which meant every change to a
form was one `wp-content` wipe away from being gone and invisible in code
review. If you clone this repo onto a new machine, recreate the link:

```bash
cmd //c mklink //J "C:\…\wp-content\plugins\cular-intake-form" "C:\…\Redesign\plugins\cular-intake-form"
```

(Windows junctions, not symlinks — junctions don't need an elevated shell. The
original folder is preserved as `cular-intake-form.pre-vendor-backup`; delete it
once you're happy.)

### Form types

Registered in `$form_types` in `cular-intake-form.php`. `[cular_intake_form
type="…"]`, or set **Intake form type** on a `cular/contact` block.

| Type | Form | Origin |
| --- | --- | --- |
| `contact` | Contact / General Enquiry | hand-written |
| `web`, `web-design`, `web-development` | Web services | hand-written |
| `ads`, `seo` | Advertising, SEO | hand-written |
| `social-media` | Social Media Marketing (5 steps) | ported from a 36-question Elementor Pro form |
| `content-social` | Content Creation — Social Media | ported from Elementor Pro |
| `content-shoot` | Content Creation — Photo & Video Shoot | ported from Elementor Pro |
| `brand-identity` | Brand Identity | reshaped from WPForms #11073 |
| `discovery` | New Client Discovery | reshaped from WPForms #11067 |

### Adding a form

The five ported forms are **spec-driven** — a field list, not markup. Copy
`templates/intake-form-brand-identity.php`, change the questions, register the
type. Two pieces do the rest:

- `templates/partials/render-spec.php` renders a spec (steps → sections →
  fields; supports text/email/tel/textarea/select/radio/checkbox) into the same
  markup the hand-written templates use.
- `assets/js/intake-form-generic.js` drives any form marked
  `data-generic-driver`: stepping, validation, review, submit. It reads step
  names from `data-step-name`, so the template stays the source of truth and no
  new form needs its own JS. The four older forms still have bespoke scripts;
  they work, and there was no reason to rewrite them.

`cular_intake_contact_step()` is **not optional**. `business_name` and
`contact_email` are indexed columns on the submissions table and are what the
admin list shows — a form that skips them files enquiries nobody can identify.

### Tracking

**wp-admin → Intake Forms** (All Submissions / Form Types / Settings), reading
`wp_cular_intake_submissions`. Each row stores the full answer set as JSON plus
form type, business name, email and timestamp. Submissions also fire an email to
the recipients in Settings, and a webhook if one is configured.

`reference/check-submissions.php` reports what is in the table from the CLI, and
`--purge=<needle>` removes rows by business name (used to clear QA rows after a
test run — be careful, it deletes).

### Verifying

`node reference/verify-forms.mjs` opens every `/form/*` page in a real browser,
fills the required fields, walks every step and submits — then
`check-submissions.php` confirms the rows landed. Rendering a form is not the
same as it working; only this proves the pipeline end to end.

Two things that check caught, neither visible from the markup:

- The legacy `web` template had **no `form_type` input at all** — it relied
  solely on its JS setting `data.form_type` at submit time, so nothing
  inspecting the DOM could tell what the form was. Now declared in the markup
  like every other template.
- `/form/ads-form/` is a **draft**, so it 404s for anonymous visitors. It is
  converted and will work when published; the verify script skips it by design.

### Look

Form containers carry a **neon green edge** (`--neon`, a brightened brand
green) that intensifies on `:focus-within`. It is deliberately *not* built from
`--accent`: these forms sit on the site's green pages, and a mid-green glow on a
green background is invisible — the first attempt read as no border at all.

Dedicated `/form/*` pages set **Form only** on the `cular/contact` block, which
drops the contact-details column and the "Book a Call with Us" heading. A
37-question intake does not belong in a 40%-wide column under a heading that
duplicates its own title.

## 7. Data sources

- **Menus:** Appearance → Menus, locations **Primary Menu** / **Social Links**
  (`inc/nav.php`); header falls back to ACF then hardcoded defaults.
- **Portfolio:** `portfolio_item` CPT + `portfolio_tag` taxonomy; card art in
  meta `card_title`, `video_url`, `overlay_logo_id`, `portfolio_image_id`,
  `external_link`.

  There are **two records per project and they are not redundant**: the CPT holds
  the *card* (art, video, tags, link) and drives the homepage carousel, the
  archive and every related-work strip; the page at `/portfolio-cular/<slug>/`
  holds the *long-form case study*. `external_link` on the CPT is what ties them
  together, and `cular_case_study_item()` / `cular_item_permalink()` in
  `inc/portfolio.php` walk that link in both directions. Don't "deduplicate"
  them — deleting the pages would throw away the case-study content and its
  search history.
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
- **Elementor offload rewritten to match on asset source path.** It used to be a
  hardcoded list of ~20 handle names, and it had quietly rotted: Elementor 3.x
  registers CSS *per widget type* (`widget-image`, `widget-icon-list`,
  `widget-spacer`, …) for whatever a page uses, so the portfolio conversions
  pulled in five stylesheets the list had never heard of, plus
  `dynamic-content-for-elementor` and `extensions-for-elementor-form` assets.
  Matching on where a file comes from (`cular_elementor_asset_paths()`) covers
  new widget handles and add-ons automatically. **Verified: zero Elementor
  stylesheets on all 87 rebuilt URLs.** The dead `elementor-default` /
  `elementor-kit-N` body classes are stripped too.
- **Responsive images everywhere via `cular_img()`.** Blocks were hand-writing
  single-`src` `<img>` tags, so a phone downloaded a 2000px original to paint it
  at 380px. The helper routes through `wp_get_attachment_image()` for
  `srcset`/`sizes`/intrinsic dimensions, and falls back to a plain tag for
  content ported out of Elementor whose attachment row is gone.
- **Video posters and `preload` are decided per element.** With a poster,
  `preload="none"` (a case study can carry 40 clips, and the archive 45 cards).
  Without one, `preload="none"` paints a dead black box — those fall back to
  `preload="metadata"` so the first frame shows. Archive card videos only fetch
  on hover/focus intent.
- **Portfolio archive filters in the browser, not over the network.** All ~45
  cards ship once and non-matching ones are hidden, so filtering costs no round
  trip, the URL stays clean, and every project is in the initial HTML for
  crawlers.

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

**Regression checks** — run these after touching motion, fonts, assets or any
conversion:

| Script | Covers |
| --- | --- |
| `node reference/verify-all.mjs` | Every rebuilt URL (87): HTTP 200, one `h1`, zero Elementor stylesheets, no raw block markup leaking as text, every reveal fired, valid JSON-LD, no failing first-party request. Regenerate its path list with the snippet in §6a. |
| `node reference/verify-light.mjs` | 7 representative pages: fonts are WOFF2 and no `.ttf` is requested, the menu opens/closes, rapid double-toggling doesn't strand it. |
| `node reference/verify-portfolio.mjs` | The 45 published case studies specifically. |

One gotcha when writing these: **assert on the `is-revealed` class, not on
computed `opacity`.** The first version of `verify-portfolio.mjs` sampled opacity
right after scrolling and reported all 45 pages as broken — it was catching
elements mid-transition.

## 10. SEO plan

Done:
- Semantic headings, **verified** — exactly one `h1` on all 87 rebuilt URLs
  (`reference/verify-all.mjs` asserts it). Two real defects were found and fixed
  doing this: the **homepage had none at all** (its hero is the showreel with no
  visible headline, so `cular/hero` now emits a visually-hidden `h1` when no
  heading is set), and three service pages had **two**, because the intake-forms
  plugin renders its brand line as an `h1` — `inc/seo.php` wraps that shortcode
  and demotes it, so a plugin update cannot re-break it.
- **Structured data** (`inc/seo.php`, complements Yoast rather than duplicating
  it — every node is suppressed when `WPSEO_VERSION` is defined and Yoast would
  emit its own):
  - `CreativeWork` on each of the 47 case studies, with the project's
    `portfolio_tag` terms as `keywords`/`about`. `CreativeWork` is the honest
    type for a portfolio piece — it is not an `Article` and not a `Product`.
  - `ItemList` on the two portfolio listings, naming every project, which is
    what lets a listing surface as a carousel rather than one blue link.
  - `BreadcrumbList` sitewide.
  - `BlogPosting` microdata on archive cards + JSON-LD on single posts
    (`inc/single-post.php`).
- **Redirects:** `/cular-creative/` (the old homepage) 301s to `/` instead of
  being rebuilt, so the homepage's content lives at exactly one URL.
- **noindex** on the previews, tests and internal one-offs, so converting them
  didn't add thin pages competing in search.
- **Internal linking:** related posts on singles; archive cards link to posts;
  every case study links to four *tag-matched* projects (generated from the CPT,
  so the links are topical and never go stale).
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

**Current numbers** (all 87 rebuilt URLs crawled):

```
Uploads on disk:      10,013 files, 5.7 GB
Referenced (keep):     2,851 files, 2.1 GB
Unused attachments:    6,811 files, 3.4 GB
Orphan files:            351 files, 120 MB   (mostly Elementor form-submission PDFs)
Reclaimable:                       3.5 GB
```

> **Why the keep set tripled.** The first run, against 19 rebuilt URLs, reported
> 906 files / 534 MB kept and 5.1 GB reclaimable. Converting 94 pages moved their
> media into the used set, and the figure went to 2,851 files / 2.1 GB. This is
> the re-run rule in action, not a regression — **convert a page, re-run the
> manifest**, or the delete list will happily include something a page you just
> converted now needs.

Three things to know before trusting a delete list:

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
- **URL extraction anchors on a known media extension.** Serialized meta stores
  URLs back to back with no separator, so a greedy "match non-delimiter
  characters" pattern ran off the end of one filename into the next and produced
  one joined path (`…/spb.webmhttp:/…/spb-portrait_1.mp4`). A joined key matches
  nothing on disk, so **both** real files fell out of the used set and onto the
  delete list — the most dangerous possible failure for this script. Matching
  "any 2–5 letters" as the extension is not enough either: Instagram rips are
  named `Snapinsta.app_1234.jpg` and got truncated at `.app`. Hence the explicit
  extension list. The fix took referenced-but-missing from 53 down to 9.

Sanity checks that the current run passes: the 120MB hero showreel is kept while
its five duplicate exports are flagged unused; testimonial and portfolio videos
are kept; and of 2,851 referenced files only 9 are missing from disk, all of them
old Elementor kit references to files that really were deleted.

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
- **Contact forms:** ✅ done. Every form is a Cular Intake Form (§7a), the paid
  landing pages keep their `[wpforms]` embeds (WPForms stays active), and no
  Elementor Pro form remains. **There is no blocker left** — 104 pages are off
  Elementor and the only two still on it are an empty draft and the old homepage
  that 301s to `/`.

  Before deactivating, in this order: run `node reference/verify-forms.mjs` (all
  9 published forms must submit), `node reference/verify-all.mjs`, then
  deactivate and re-run both. Keep WPForms active — the ads landing pages depend
  on it.
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
3. ~~Fix the "Home" menu item~~ ✅ (now points at the front page; the old
   `/cular-creative/` 301s to `/`)
4. ~~Portfolio listing + 47 case studies~~ ✅
5. ~~Case study, ads landing, rate card and one-off pages~~ ✅
6. ~~`/form/*` pages~~ ✅ all 10 on Cular Intake Forms, tracked in wp-admin (§7a)
7. **Media cleanup** (§12a) — re-run `npm run media:manifest` first, since 94
   pages moved off Elementor and that changes the used set substantially.
8. **Remaining performance work** (§9): uploads to WebP/AVIF, the 120 MB hero
   video, third-party tag deferral.
9. **SEO pass** (§10): re-enable Yoast (note `inc/seo.php` already stands down
   when it is active), sitemaps, OG, alt-text audit, FAQ schema.
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

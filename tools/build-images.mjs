/**
 * Optimise the theme's own images (theme/assets/img/).
 *
 * These are the handful of files the theme ships itself — brand logos, the CTA
 * spotlight sweep, card backgrounds. They are NOT uploads; the media library is
 * handled separately by reference/media-scan.php + a later compression pass.
 *
 * Two problems being fixed:
 *   - the logos are 1684px wide but never render above 210px CSS,
 *   - spotlight.png is a soft RGBA gradient stored as PNG (227KB), a format
 *     that is about the worst possible choice for smooth colour ramps.
 *
 * Sources live in theme/assets/img/src/ (committed originals); the optimised
 * files land in theme/assets/img/. Rerun after replacing an original.
 *
 * Requires:  pip install pillow
 * Usage:     npm run images
 */

import { spawnSync } from 'node:child_process';
import { existsSync, readdirSync, statSync } from 'node:fs';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const ROOT = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const SRC = join(ROOT, 'theme/assets/img/src');
const OUT = join(ROOT, 'theme/assets/img');

// maxWidth = 2x the largest CSS width the file is ever rendered at, so it stays
// crisp on retina without shipping five times the pixels anyone can see.
const IMAGES = [
	// Footer logo. Stays PNG: inc/single-post.php also feeds this URL to the
	// Organization JSON-LD as the publisher logo.
	{ in: 'logo-full.png', out: 'logo-full.png', maxWidth: 420, format: 'png' },
	// Header logo, rendered at clamp(120px, 13vw, 175px).
	{ in: 'logo-green.png', out: 'logo-green.png', maxWidth: 350, format: 'png' },
	// CSS backgrounds — never referenced by markup, so WebP is free.
	{ in: 'spotlight.png', out: 'spotlight.webp', maxWidth: 612, format: 'webp', quality: 88 },
	{ in: 'team-soon-mark.png', out: 'team-soon-mark.webp', maxWidth: 360, format: 'webp', quality: 90 },
	{ in: 'service-card-bg.jpg', out: 'service-card-bg.webp', maxWidth: 1400, format: 'webp', quality: 80 },
	{ in: 'team-card-bg.jpg', out: 'team-card-bg.webp', maxWidth: 920, format: 'webp', quality: 82 },
];

const PY = `
import sys, os
from PIL import Image

src, dest, max_w, fmt, quality = sys.argv[1], sys.argv[2], int(sys.argv[3]), sys.argv[4], int(sys.argv[5])
im = Image.open(src)
if im.width > max_w:
    im = im.resize((max_w, round(im.height * max_w / im.width)), Image.LANCZOS)

if fmt == 'webp':
    im.save(dest, 'WEBP', quality=quality, method=6)
else:
    # Quantise to a 256-colour palette: these are flat brand marks, so the
    # palette is visually lossless and roughly halves the file.
    if im.mode == 'RGBA':
        im = im.quantize(colors=256, method=Image.FASTOCTREE)
    im.save(dest, 'PNG', optimize=True)

print(f"{os.path.basename(src)} {os.path.getsize(src)//1024}KB -> {os.path.basename(dest)} {os.path.getsize(dest)//1024}KB")
`;

if (!existsSync(SRC)) {
	console.error(`missing ${SRC} — move the originals there first.`);
	process.exit(1);
}

let failed = false;
let before = 0;
let after = 0;

for (const img of IMAGES) {
	const src = join(SRC, img.in);
	if (!existsSync(src)) {
		console.error(`missing source image: ${src}`);
		failed = true;
		continue;
	}
	const dest = join(OUT, img.out);
	const res = spawnSync(
		'python',
		['-c', PY, src, dest, String(img.maxWidth), img.format, String(img.quality ?? 90)],
		{ stdio: ['ignore', 'inherit', 'inherit'] }
	);
	if (res.status !== 0) {
		console.error(`optimising ${img.in} failed — is pillow installed? (pip install pillow)`);
		failed = true;
		continue;
	}
	before += statSync(src).size;
	after += statSync(dest).size;
}

// Anything in img/ that no longer has a source entry is a leftover from an
// earlier format; flag it rather than deleting behind the author's back.
const expected = new Set(IMAGES.map((i) => i.out));
for (const f of readdirSync(OUT)) {
	if (f !== 'src' && !expected.has(f)) console.warn(`note: ${f} is not produced by this script — stale?`);
}

console.log(`\ntotal ${Math.round(before / 1024)}KB -> ${Math.round(after / 1024)}KB`);
process.exit(failed ? 1 : 0);

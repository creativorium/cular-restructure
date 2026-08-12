/**
 * Subset the brand fonts to WOFF2.
 *
 * The upstream files are a 688KB Montserrat variable TTF (2677 glyphs: Latin,
 * Latin-ext, Cyrillic, Greek, Vietnamese) and a 19KB Luxia Display TTF. We set
 * English and Bahasa Indonesia — both pure Latin — so the non-Latin coverage is
 * pure weight. Subsetting + WOFF2 takes the pair from 708KB to 48KB.
 *
 * Sources live in theme/assets/fonts/src/ (TTF, committed) and the built WOFF2
 * lands next to them in theme/assets/fonts/. Rerun after replacing a source
 * font or after adding a glyph the subset does not cover.
 *
 * Requires:  pip install fonttools brotli
 * Usage:     npm run fonts
 */

import { spawnSync } from 'node:child_process';
import { existsSync, statSync } from 'node:fs';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const ROOT = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const SRC = join(ROOT, 'theme/assets/fonts/src');
const OUT = join(ROOT, 'theme/assets/fonts');

// Latin + Latin-ext + the punctuation and symbols we actually typeset
// (curly quotes, dashes, ellipsis, arrows for the menu chevrons, bullets).
const UNICODES = [
	'U+0000-00FF', // Basic Latin + Latin-1 Supplement
	'U+0100-017F', // Latin Extended-A
	'U+0131',
	'U+0152-0153',
	'U+02BB-02BC',
	'U+02C6',
	'U+02DA',
	'U+02DC',
	'U+0300-036F', // combining marks
	'U+2000-206F', // general punctuation: quotes, dashes, ellipsis, nbsp
	'U+2074',
	'U+20AC', // €
	'U+2122', // ™
	'U+2190-2193', // arrows
	'U+2212',
	'U+2215',
	'U+25CF', // •-ish bullet used in lists
	'U+2605', // ★ testimonial ratings
	'U+FEFF',
	'U+FFFD',
].join(',');

const FONTS = [
	{
		in: 'Montserrat.ttf',
		out: 'Montserrat.woff2',
		// Keeps the wght axis intact so 100-900 stays available from one file.
		features: 'kern,liga,clig,calt,ccmp,locl,mark,mkmk',
	},
	{
		in: 'LuxiaDisplay.ttf',
		out: 'LuxiaDisplay.woff2',
		features: 'kern,liga,clig,calt,ccmp',
	},
];

let failed = false;

for (const f of FONTS) {
	const src = join(SRC, f.in);
	if (!existsSync(src)) {
		console.error(`missing source font: ${src}`);
		failed = true;
		continue;
	}
	const dest = join(OUT, f.out);

	const res = spawnSync(
		'python',
		[
			'-m',
			'fontTools.subset',
			src,
			`--unicodes=${UNICODES}`,
			`--layout-features=${f.features}`,
			'--flavor=woff2',
			`--output-file=${dest}`,
			'--no-hinting',
			'--drop-tables+=DSIG',
		],
		{ stdio: ['ignore', 'inherit', 'inherit'] }
	);

	if (res.status !== 0) {
		console.error(`\nsubsetting ${f.in} failed — is fonttools installed? (pip install fonttools brotli)`);
		failed = true;
		continue;
	}

	const before = statSync(src).size;
	const after = statSync(dest).size;
	const kb = (b) => `${(b / 1024).toFixed(1)}KB`;
	console.log(`${f.in} ${kb(before)} -> ${f.out} ${kb(after)}  (-${Math.round((1 - after / before) * 100)}%)`);
}

process.exit(failed ? 1 : 0);

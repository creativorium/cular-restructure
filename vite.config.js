import { defineConfig } from 'vite';
import { resolve } from 'node:path';

// Vite builds the theme's global JS/SCSS (which in turn pulls in every
// block's SCSS via a glob import in src/main.js). Output goes to theme/dist
// with a manifest that theme/inc/enqueue.php reads to load hashed files.
const DEV_PORT = 5173;

export default defineConfig({
  root: resolve(__dirname, 'theme'),
  // './' — relative to the emitted file, NOT absolute from the domain root.
  //
  // This used to be '/wp-content/themes/cular/dist/', which bakes a site-root
  // path into every url() inside the built CSS. That is only correct when
  // WordPress is installed at the domain root: on the dev site, which lives at
  // dev.cularcreative.com/cular, every font and background 404'd because the
  // '/cular' prefix was missing. The stylesheet itself loaded fine (PHP builds
  // that URL from get_template_directory_uri()), so it presented as "the theme
  // works but its assets are gone".
  //
  // Relative URLs resolve against the CSS file's own location, so the same
  // build works at the root, in a subdirectory, and on any domain.
  //
  // enqueue.php is unaffected: the Vite manifest still stores paths like
  // `assets/main-xxx.css`, which it prefixes with CULAR_URI . '/dist/'.
  base: process.env.NODE_ENV === 'development' ? `http://localhost:${DEV_PORT}/` : './',
  build: {
    outDir: resolve(__dirname, 'theme/dist'),
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: resolve(__dirname, 'theme/src/main.js'),
    },
  },
  server: {
    port: DEV_PORT,
    strictPort: true,
    cors: true,
    origin: `http://localhost:${DEV_PORT}`,
  },
});

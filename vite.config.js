import { defineConfig } from 'vite';
import { resolve } from 'node:path';

// Vite builds the theme's global JS/SCSS (which in turn pulls in every
// block's SCSS via a glob import in src/main.js). Output goes to theme/dist
// with a manifest that theme/inc/enqueue.php reads to load hashed files.
const DEV_PORT = 5173;

export default defineConfig({
  root: resolve(__dirname, 'theme'),
  base: process.env.NODE_ENV === 'development' ? `http://localhost:${DEV_PORT}/` : '/wp-content/themes/cular/dist/',
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

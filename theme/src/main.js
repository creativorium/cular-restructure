// Global styles.
import './styles/main.scss';

// Pull in every block's SCSS automatically (one file per component folder).
// Vite compiles these into the single stylesheet enqueued by the theme.
const blockStyles = import.meta.glob('../blocks/**/*.scss', { eager: true });
void blockStyles;

// Pull in optional per-block front-end behaviour (blocks/<name>/view.js).
const blockScripts = import.meta.glob('../blocks/**/view.js', { eager: true });
void blockScripts;

// Global site behaviour goes here.
document.documentElement.classList.add('cular-ready');

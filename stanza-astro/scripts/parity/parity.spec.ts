/**
 * Visual parity harness — compares Astro preview against the PHP app.
 *
 * Run after starting both servers:
 *   astro build && astro preview &   (port 4321)
 *   php -S localhost:8888 -t /path/to/php-stanza &
 *
 * Usage:
 *   PHP_BASE=http://localhost:8888 ASTRO_BASE=http://localhost:4321 \
 *     npx playwright test scripts/parity/parity.spec.ts
 */
import { test, expect, chromium, type Page } from '@playwright/test';
import * as fs from 'fs';
import * as path from 'path';

const PHP_BASE = process.env.PHP_BASE ?? 'http://localhost:8888';
const ASTRO_BASE = process.env.ASTRO_BASE ?? 'http://localhost:4321';

const VIEWS = [
  { name: 'landing', php: '/', astro: '/en/' },
  { name: 'collection-home', php: '/?c=demo', astro: '/en/demo/' },
  { name: 'poem-list', php: '/?c=demo&v=list', astro: '/en/demo/index/' },
  { name: 'poem', php: '/?c=demo&p=the-sea_en.md', astro: '/en/demo/the-sea/' },
];

const THEMES: Array<'light' | 'dark'> = ['light', 'dark'];
const VIEWPORTS = [
  { name: 'desktop', width: 1280, height: 800 },
  { name: 'mobile', width: 390, height: 844 },
];

const REPORT_DIR = path.join(process.cwd(), 'scripts/parity/screenshots');

async function screenshot(
  page: Page,
  url: string,
  theme: 'light' | 'dark',
  outPath: string,
) {
  await page.goto(url, { waitUntil: 'networkidle' });
  // Apply theme via localStorage before screenshot
  await page.evaluate((t) => {
    localStorage.setItem('theme', t);
    document.documentElement.setAttribute('data-theme', t);
  }, theme);
  await page.waitForTimeout(200);
  await page.screenshot({ path: outPath, fullPage: true });
}

test.describe('visual parity', () => {
  test.beforeAll(() => {
    fs.mkdirSync(REPORT_DIR, { recursive: true });
  });

  for (const view of VIEWS) {
    for (const theme of THEMES) {
      for (const vp of VIEWPORTS) {
        const label = `${view.name}/${theme}/${vp.name}`;

        test(label, async () => {
          const browser = await chromium.launch({
            executablePath: '/opt/pw-browsers/chromium',
          });

          try {
            const context = await browser.newContext({
              viewport: { width: vp.width, height: vp.height },
            });
            const page = await context.newPage();

            const phpFile = path.join(
              REPORT_DIR,
              `${view.name}-${theme}-${vp.name}-php.png`,
            );
            const astroFile = path.join(
              REPORT_DIR,
              `${view.name}-${theme}-${vp.name}-astro.png`,
            );

            await screenshot(page, `${PHP_BASE}${view.php}`, theme, phpFile);
            await screenshot(page, `${ASTRO_BASE}${view.astro}`, theme, astroFile);

            // Both screenshots must exist — diff is manual / visual review
            expect(fs.existsSync(phpFile)).toBe(true);
            expect(fs.existsSync(astroFile)).toBe(true);

            await context.close();
          } finally {
            await browser.close();
          }
        });
      }
    }
  }
});

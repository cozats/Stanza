import { defineConfig } from '@playwright/test';

export default defineConfig({
  testDir: '.',
  timeout: 30_000,
  retries: 0,
  use: {
    headless: true,
  },
  reporter: [['list'], ['html', { outputFolder: 'scripts/parity/report', open: 'never' }]],
});

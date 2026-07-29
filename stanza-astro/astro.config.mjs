import { defineConfig } from 'astro/config';

export default defineConfig({
  output: 'static',
  site: 'https://stanza.pages.dev',
  i18n: {
    locales: ['en', 'el'],
    defaultLocale: 'en',
    routing: {
      prefixDefaultLocale: true,
    },
  },
});

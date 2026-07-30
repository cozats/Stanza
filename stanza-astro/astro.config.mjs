import { defineConfig } from 'astro/config';
import sitemap from '@astrojs/sitemap';
import remarkGfm from 'remark-gfm';
import remarkStanza from './src/lib/markdown/remark-stanza.ts';
import rehypeReveal from './src/lib/markdown/rehype-reveal.ts';

export default defineConfig({
  output: 'static',
  site: 'https://stanza.pages.dev',
  integrations: [sitemap()],
  i18n: {
    locales: ['en', 'el'],
    defaultLocale: 'en',
    routing: {
      prefixDefaultLocale: true,
    },
  },
  markdown: {
    remarkPlugins: [remarkGfm, remarkStanza],
    rehypePlugins: [rehypeReveal],
    remarkRehype: { allowDangerousHtml: true },
  },
});

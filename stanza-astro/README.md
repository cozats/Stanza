# Stanza Astro

Static rebuild of [Stanza](https://github.com/cozats/Stanza) using Astro 5 + Decap CMS. Visually and functionally identical to the PHP original; deployable free on Cloudflare Pages with no server.

## Quick start (Mac)

```bash
bash <(curl -fsSL https://raw.githubusercontent.com/cozats/Stanza/claude/stanza-astro-migration-yf6s3o/get-stanza-astro.sh)
```

Or clone manually:

```bash
git clone --branch claude/stanza-astro-migration-yf6s3o \
  https://github.com/cozats/Stanza.git ~/Documents/Claude/Stanza-Astro
cd ~/Documents/Claude/Stanza-Astro/stanza-astro
npm ci
npm run dev          # site at http://localhost:4321
```

To use the CMS locally (no OAuth needed):

```bash
# In a second terminal, from stanza-astro/:
npm run cms          # starts decap-server at http://localhost:8081
# Then visit http://localhost:4321/admin
```

## Commands

| Command | Description |
|---|---|
| `npm run dev` | Dev server at `localhost:4321` |
| `npm run build` | Build to `dist/` |
| `npm run preview` | Preview the production build |
| `npm test` | Run vitest unit tests |
| `npm run cms` | Start Decap local backend |

## Project structure

```
stanza-astro/
├── src/
│   ├── content/            # Content collections (markdown + JSON)
│   │   ├── collections/    # Collection metadata (JSON)
│   │   ├── poems/          # Poem files (markdown with frontmatter)
│   │   └── site.json       # Author profile
│   ├── layouts/Base.astro  # HTML shell + theme toggle + reveal observer
│   ├── components/         # Breadcrumbs, ControlBar, Dropdown
│   ├── pages/              # Route files ([lang]/, [lang]/[collection]/, …)
│   ├── lib/                # Markdown pipeline (remark-stanza, rehype-reveal)
│   ├── i18n/ui.ts          # EN/EL locale strings + helpers
│   └── styles/             # tokens.css, global.css
├── functions/api/          # Cloudflare Pages Functions (GitHub OAuth)
├── public/admin/           # Decap CMS entry point + config
├── tests/                  # Vitest tests
└── docs/                   # Deploy and migration guides
```

## Content

Add a poem: create `src/content/poems/my-poem-en.md`:

```markdown
---
title: My Poem
collection: demo
lang: en
translationKey: my-poem
date: 2024-01-01
---

First stanza line one
first stanza line two

Second stanza here
```

Add a collection: create `src/content/collections/my-collection.json`:

```json
{ "title": "My Collection", "author": "Poet Name", "order": 2 }
```

## Deployment

See [docs/DEPLOY.md](docs/DEPLOY.md) for Cloudflare Pages setup.  
See [docs/MIGRATION.md](docs/MIGRATION.md) to import from an existing PHP Stanza install.

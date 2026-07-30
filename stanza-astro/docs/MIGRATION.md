# Migrating from PHP Stanza

If you have an existing PHP Stanza installation and want to import its content into the Astro build, use the migration script.

## What gets imported

- `config.json` → `src/content/site.json` (author name, bio, photo)
- `collections/*/collection.json` → `src/content/collections/*.json`
- `collections/*/poems/*.md` (and `.txt`) → `src/content/poems/*.md` with proper frontmatter

## Running the migration

From inside `stanza-astro/`, point the script at your PHP install root:

```bash
node scripts/migrate-php-content.mjs /path/to/php-stanza-root
```

Dry-run mode (prints what would be written, touches nothing):

```bash
node scripts/migrate-php-content.mjs --dry-run /path/to/php-stanza-root
```

## What the script does

1. Reads `config.json` and writes `src/content/site.json`
2. For each `collections/{slug}/collection.json`, writes `src/content/collections/{slug}.json`
3. For each poem file:
   - Derives `title` from the first `# ` heading line (falls back to the de-slugified filename)
   - Derives `lang` from `_en` / `_el` suffix on the filename
   - Derives `translationKey` by stripping the lang suffix (so `autumn_el.md` and `autumn_en.md` share key `autumn`)
   - Derives `date` from the file's mtime
   - Strips the `# Title` line from the body (it becomes the frontmatter `title`)
   - Writes the result to `src/content/poems/{slug}.md`

The script is idempotent — running it again over already-imported content is safe.

## After migrating

```bash
npm run build     # verify everything compiles
npm run dev       # browse the imported content at http://localhost:4321
```

Check `src/content/poems/` for any files with unexpected titles or missing `collection` references, and adjust the frontmatter manually if needed.

## URL changes

PHP Stanza uses query-string URLs (`?c=collection&p=poem.md`). The Astro site uses clean paths (`/en/collection/poem/`). Old links will break. If your site is already live and has inbound links worth preserving, add a redirect map in `public/_redirects` (Cloudflare Pages / Netlify format):

```
/index.php?c=demo&p=the-sea_en.md   /en/demo/the-sea/   301
```

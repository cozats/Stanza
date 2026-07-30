# Deploying to Cloudflare Pages

## Prerequisites

- A [Cloudflare](https://cloudflare.com) account (free tier is sufficient)
- The repository pushed to GitHub (already at `cozats/Stanza`)
- A [GitHub OAuth App](https://github.com/settings/developers) for the CMS login

---

## Step 1 — Connect the repository

1. Log in to the Cloudflare dashboard → **Pages** → **Create a project** → **Connect to Git**
2. Authorize Cloudflare to access your GitHub account, then select `cozats/Stanza`

## Step 2 — Configure build settings

| Setting | Value |
|---|---|
| Production branch | `main` *(or your chosen deploy branch)* |
| Root directory | `stanza-astro` |
| Build command | `npm run build` |
| Build output directory | `dist` |
| Node version | `22` |

Set the Node version either in the **Environment variables** panel (`NODE_VERSION = 22`) or by keeping the `.nvmrc` file at the root of `stanza-astro/`.

## Step 3 — Create a GitHub OAuth App

1. Go to **GitHub → Settings → Developer Settings → OAuth Apps → New OAuth App**
2. Fill in:
   - **Application name**: Stanza CMS (or anything)
   - **Homepage URL**: `https://your-site.pages.dev`
   - **Authorization callback URL**: `https://your-site.pages.dev/api/callback`
3. Click **Register application**, then copy the **Client ID** and generate a **Client Secret**

## Step 4 — Add environment variables

In your Cloudflare Pages project → **Settings → Environment variables**, add:

| Variable | Value |
|---|---|
| `GITHUB_CLIENT_ID` | The Client ID from step 3 |
| `GITHUB_CLIENT_SECRET` | The Client Secret from step 3 |

Set these for both **Production** and **Preview** environments.

## Step 5 — Update config.yml

In `public/admin/config.yml`, replace the placeholder:

```yaml
backend:
  name: github
  repo: cozats/Stanza
  branch: main
  base_url: https://your-site.pages.dev   # ← replace with your actual Pages URL
  auth_endpoint: api/auth
```

Commit and push; Cloudflare Pages will rebuild automatically.

## Step 6 — Verify

1. Visit `https://your-site.pages.dev` — the site should render
2. Visit `https://your-site.pages.dev/admin` → click **Login with GitHub**
3. After OAuth, you should land in the Decap CMS editor

---

## Alternative: Netlify

See [netlify-fallback.md](netlify-fallback.md) for a single Netlify Function that replaces the two Cloudflare Pages Functions above.

---

## FTP / shared hosting

Because the build output is plain HTML/CSS/JS, you can also deploy to any
static host or FTP it to the same server running the PHP app:

```bash
npm run build
# upload dist/ to your server's public_html/stanza/ (or wherever)
```

The CMS OAuth functions won't work on a PHP host; use `npm run cms` locally
for editing, commit the content files, and then redeploy.

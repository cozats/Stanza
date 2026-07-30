# Netlify OAuth Fallback

If you host on Netlify instead of Cloudflare Pages, replace the two Cloudflare Pages Functions
with a single Netlify Function that proxies GitHub OAuth.

## Step 1 — Install dependencies

```bash
npm install @netlify/functions node-fetch
```

## Step 2 — Create `netlify/functions/auth.ts`

```typescript
import type { Handler } from '@netlify/functions';

const handler: Handler = async (event) => {
  const { queryStringParameters } = event;

  // Redirect to GitHub
  if (!queryStringParameters?.code) {
    const authUrl = new URL('https://github.com/login/oauth/authorize');
    authUrl.searchParams.set('client_id', process.env.GITHUB_CLIENT_ID!);
    authUrl.searchParams.set(
      'redirect_uri',
      `${process.env.URL}/.netlify/functions/auth`,
    );
    authUrl.searchParams.set('scope', 'repo,user');
    return { statusCode: 302, headers: { Location: authUrl.href }, body: '' };
  }

  // Exchange code for token
  const res = await fetch('https://github.com/login/oauth/access_token', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
    body: JSON.stringify({
      client_id: process.env.GITHUB_CLIENT_ID,
      client_secret: process.env.GITHUB_CLIENT_SECRET,
      code: queryStringParameters.code,
    }),
  });

  const data: any = await res.json();

  if (!data.access_token) {
    return { statusCode: 400, body: `OAuth error: ${data.error_description ?? data.error}` };
  }

  const payload = JSON.stringify({ token: data.access_token, provider: 'github' });
  const html = `<!DOCTYPE html><html><body><script>
(function(){
  var payload=${JSON.stringify(payload)};
  function onMsg(e){if(e.data==='authorizing:github'){window.opener.postMessage('authorization:github:success:'+payload,e.origin);window.removeEventListener('message',onMsg,false);window.close();}}
  window.addEventListener('message',onMsg,false);
  if(window.opener)window.opener.postMessage('authorizing:github','*');
})();
<\/script></body></html>`;

  return { statusCode: 200, headers: { 'Content-Type': 'text/html' }, body: html };
};

export { handler };
```

## Step 3 — Update `public/admin/config.yml`

Change the backend section:

```yaml
backend:
  name: github
  repo: cozats/Stanza
  branch: main
  base_url: https://your-site.netlify.app
  auth_endpoint: .netlify/functions/auth
```

## Step 4 — Add environment variables in Netlify UI

In **Site settings → Environment variables**, add:

- `GITHUB_CLIENT_ID` — from your GitHub OAuth App
- `GITHUB_CLIENT_SECRET` — from your GitHub OAuth App

## Step 5 — Create a GitHub OAuth App

1. Go to **GitHub → Settings → Developer Settings → OAuth Apps → New OAuth App**
2. Authorization callback URL: `https://your-site.netlify.app/.netlify/functions/auth`
3. Copy the Client ID and generate a Client Secret

## Build settings

In Netlify:
- **Base directory**: `stanza-astro`
- **Build command**: `npm run build`
- **Publish directory**: `stanza-astro/dist`

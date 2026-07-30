/**
 * Cloudflare Pages Function — GitHub OAuth callback.
 * GET /api/callback?code=...
 *
 * Exchanges the authorization code for a token and posts it back to the
 * opener window via postMessage, then closes itself.
 */

interface Env {
  GITHUB_CLIENT_ID: string;
  GITHUB_CLIENT_SECRET: string;
}

interface GitHubTokenResponse {
  access_token?: string;
  error?: string;
  error_description?: string;
}

export const onRequestGet: (ctx: { request: Request; env: Env }) => Promise<Response> = async ({
  request,
  env,
}) => {
  const code = new URL(request.url).searchParams.get('code');

  if (!code) {
    return new Response('Missing OAuth code', { status: 400 });
  }

  const tokenRes = await fetch('https://github.com/login/oauth/access_token', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
    body: JSON.stringify({
      client_id: env.GITHUB_CLIENT_ID,
      client_secret: env.GITHUB_CLIENT_SECRET,
      code,
    }),
  });

  const data = (await tokenRes.json()) as GitHubTokenResponse;

  if (!data.access_token) {
    const msg = data.error_description ?? data.error ?? 'Unknown GitHub OAuth error';
    return new Response(`OAuth failed: ${msg}`, { status: 400 });
  }

  const token = data.access_token;

  const html = `<!DOCTYPE html>
<html><body><script>
(function () {
  try {
    window.opener && window.opener.postMessage(
      { type: 'stanza-auth', token: ${JSON.stringify(token)} },
      window.location.origin
    );
  } catch (e) {}
  window.close();
})();
<\/script></body></html>`;

  return new Response(html, {
    headers: { 'Content-Type': 'text/html; charset=utf-8' },
  });
};

/**
 * Cloudflare Pages Function — GitHub OAuth initiation.
 * GET /api/auth → redirects to GitHub with correct scopes.
 *
 * Required Pages secrets:
 *   GITHUB_CLIENT_ID
 *   GITHUB_CLIENT_SECRET
 */

interface Env {
  GITHUB_CLIENT_ID: string;
  GITHUB_CLIENT_SECRET: string;
}

export const onRequestGet: (ctx: { request: Request; env: Env }) => Response = ({
  request,
  env,
}) => {
  const origin = new URL(request.url).origin;
  const authUrl = new URL('https://github.com/login/oauth/authorize');
  authUrl.searchParams.set('client_id', env.GITHUB_CLIENT_ID ?? '');
  authUrl.searchParams.set('redirect_uri', `${origin}/api/callback`);
  authUrl.searchParams.set('scope', 'repo,user');

  return Response.redirect(authUrl.href, 302);
};

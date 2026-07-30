/**
 * Cloudflare Pages Function — delete a file via GitHub Contents API.
 * POST /api/delete
 * Body: { path: string, sha: string, message?: string }
 * Header: Authorization: Bearer <github_token>
 */

interface Env {}

interface DeleteBody {
  path: string;
  sha: string;
  message?: string;
}

export const onRequestPost: (ctx: { request: Request; env: Env }) => Promise<Response> = async ({
  request,
}) => {
  const authHeader = request.headers.get('Authorization');
  const token = authHeader?.startsWith('Bearer ') ? authHeader.slice(7) : null;
  if (!token) {
    return json({ error: 'Missing Authorization header' }, 401);
  }

  let body: DeleteBody;
  try {
    body = (await request.json()) as DeleteBody;
  } catch {
    return json({ error: 'Invalid JSON body' }, 400);
  }

  const { path, sha, message } = body;
  if (!path || !sha) {
    return json({ error: 'path and sha are required' }, 400);
  }

  const repo = 'cozats/Stanza';
  const branch = 'main';
  const commitMessage = message ?? `Delete ${path}`;

  const ghRes = await fetch(`https://api.github.com/repos/${repo}/contents/${path}`, {
    method: 'DELETE',
    headers: {
      Authorization: `Bearer ${token}`,
      'Content-Type': 'application/json',
      Accept: 'application/vnd.github+json',
      'X-GitHub-Api-Version': '2022-11-28',
      'User-Agent': 'Stanza-Editor/1.0',
    },
    body: JSON.stringify({ message: commitMessage, sha, branch }),
  });

  if (ghRes.status === 204 || ghRes.ok) {
    return json({ ok: true });
  }

  const ghData = await ghRes.json();
  return json({ error: (ghData as { message?: string }).message ?? 'GitHub API error' }, ghRes.status);
};

function json(data: unknown, status = 200): Response {
  return new Response(JSON.stringify(data), {
    status,
    headers: { 'Content-Type': 'application/json' },
  });
}

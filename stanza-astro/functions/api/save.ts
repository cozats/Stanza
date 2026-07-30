/**
 * Cloudflare Pages Function — save a file via GitHub Contents API.
 * POST /api/save
 * Body: { path: string, content: string (base64), sha?: string, message?: string }
 * Header: Authorization: Bearer <github_token>
 */

interface Env {}

interface SaveBody {
  path: string;
  content: string;
  sha?: string;
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

  let body: SaveBody;
  try {
    body = (await request.json()) as SaveBody;
  } catch {
    return json({ error: 'Invalid JSON body' }, 400);
  }

  const { path, content, sha, message } = body;
  if (!path || !content) {
    return json({ error: 'path and content are required' }, 400);
  }

  const repo = 'cozats/Stanza';
  const branch = 'main';
  const commitMessage = message ?? (sha ? `Update ${path}` : `Create ${path}`);

  const putBody: Record<string, unknown> = { message: commitMessage, content, branch };
  if (sha) putBody.sha = sha;

  const ghRes = await fetch(`https://api.github.com/repos/${repo}/contents/${path}`, {
    method: 'PUT',
    headers: {
      Authorization: `Bearer ${token}`,
      'Content-Type': 'application/json',
      Accept: 'application/vnd.github+json',
      'X-GitHub-Api-Version': '2022-11-28',
      'User-Agent': 'Stanza-Editor/1.0',
    },
    body: JSON.stringify(putBody),
  });

  const ghData = await ghRes.json();
  if (!ghRes.ok) {
    return json({ error: (ghData as { message?: string }).message ?? 'GitHub API error' }, ghRes.status);
  }

  return json({ ok: true });
};

function json(data: unknown, status = 200): Response {
  return new Response(JSON.stringify(data), {
    status,
    headers: { 'Content-Type': 'application/json' },
  });
}

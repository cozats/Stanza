import { describe, it, expect, vi } from 'vitest';
import { onRequestGet as authHandler } from '../functions/api/auth';
import { onRequestGet as callbackHandler } from '../functions/api/callback';

const mockEnv = { GITHUB_CLIENT_ID: 'test-client-id', GITHUB_CLIENT_SECRET: 'test-secret' };

describe('auth handler', () => {
  it('redirects to GitHub with correct client_id and redirect_uri', () => {
    const req = new Request('https://stanza.pages.dev/api/auth');
    const res = authHandler({ request: req, env: mockEnv });

    expect(res.status).toBe(302);
    const loc = new URL(res.headers.get('location')!);
    expect(loc.origin).toBe('https://github.com');
    expect(loc.pathname).toBe('/login/oauth/authorize');
    expect(loc.searchParams.get('client_id')).toBe('test-client-id');
    expect(loc.searchParams.get('redirect_uri')).toBe(
      'https://stanza.pages.dev/api/callback',
    );
    expect(loc.searchParams.get('scope')).toContain('repo');
  });
});

describe('callback handler', () => {
  it('returns 400 when code is missing', async () => {
    const req = new Request('https://stanza.pages.dev/api/callback');
    const res = await callbackHandler({ request: req, env: mockEnv });
    expect(res.status).toBe(400);
  });

  it('exchanges code for token and returns postMessage HTML', async () => {
    vi.stubGlobal(
      'fetch',
      vi.fn().mockResolvedValue({
        json: async () => ({ access_token: 'ghp_test_token_abc123' }),
      }),
    );

    const req = new Request('https://stanza.pages.dev/api/callback?code=mycode');
    const res = await callbackHandler({ request: req, env: mockEnv });

    expect(res.status).toBe(200);
    const body = await res.text();
    expect(body).toContain('stanza-auth');
    expect(body).toContain('ghp_test_token_abc123');
    expect(body).not.toContain('authorizing:github');

    vi.unstubAllGlobals();
  });

  it('returns 400 when GitHub returns an error', async () => {
    vi.stubGlobal(
      'fetch',
      vi.fn().mockResolvedValue({
        json: async () => ({ error: 'bad_verification_code', error_description: 'Code expired' }),
      }),
    );

    const req = new Request('https://stanza.pages.dev/api/callback?code=expired');
    const res = await callbackHandler({ request: req, env: mockEnv });

    expect(res.status).toBe(400);
    expect(await res.text()).toContain('Code expired');

    vi.unstubAllGlobals();
  });
});

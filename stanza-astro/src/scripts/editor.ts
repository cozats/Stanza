const GH_TOKEN_KEY = 'stanza_gh_token';
const REPO = 'cozats/Stanza';
const REPO_ROOT = 'stanza-astro';

let token: string | null = localStorage.getItem(GH_TOKEN_KEY);

// ---------------------------------------------------------------------------
// Bootstrap
// ---------------------------------------------------------------------------

function init() {
  const lockBtn = document.getElementById('admin-lock-btn');
  if (!lockBtn) return;

  lockBtn.addEventListener('click', () => {
    if (token) {
      token = null;
      localStorage.removeItem(GH_TOKEN_KEY);
      document.body.classList.remove('stanza-authed');
      updateLockBtn(false);
      showToast('Signed out of editor', 'success');
    } else {
      startLogin();
    }
  });

  if (token) {
    document.body.classList.add('stanza-authed');
    updateLockBtn(true);
    bindListActions();
  }

  document.getElementById('edit-profile-btn')?.addEventListener('click', editProfile);
  document.getElementById('edit-collection-btn')?.addEventListener('click', editCollection);
  document.getElementById('delete-collection-btn')?.addEventListener('click', deleteCollection);
  document.getElementById('new-poem-btn')?.addEventListener('click', newPoem);
  document.getElementById('edit-poem-btn')?.addEventListener('click', editPoem);
  document.getElementById('delete-poem-btn')?.addEventListener('click', deletePoem);
}

function updateLockBtn(loggedIn: boolean) {
  const btn = document.getElementById('admin-lock-btn');
  if (!btn) return;
  btn.title = loggedIn ? 'Sign out of editor' : 'Sign in to edit';
  btn.setAttribute('aria-label', btn.title);
  btn.classList.toggle('is-authed', loggedIn);
  // Swap icon
  const locked = btn.querySelector<SVGElement>('.icon-lock-closed');
  const open = btn.querySelector<SVGElement>('.icon-lock-open');
  if (locked) locked.style.display = loggedIn ? 'none' : '';
  if (open) open.style.display = loggedIn ? '' : 'none';
}

// Inject per-poem edit/delete buttons in list view
function bindListActions() {
  document.querySelectorAll<HTMLElement>('.poem-item[data-poem-id]').forEach((li) => {
    if (li.querySelector('.poem-edit-actions')) return; // already injected
    const poemId = li.dataset.poemId!;
    const actions = document.createElement('span');
    actions.className = 'poem-edit-actions';
    actions.innerHTML =
      `<button title="Edit poem">${editSvg(16)}</button>` +
      `<button class="danger" title="Delete poem">${trashSvg(16)}</button>`;
    const [editBtn, delBtn] = actions.querySelectorAll('button');
    editBtn.addEventListener('click', (e) => { e.preventDefault(); e.stopPropagation(); editPoemById(poemId); });
    delBtn.addEventListener('click', (e) => { e.preventDefault(); e.stopPropagation(); deletePoemById(poemId); });
    li.appendChild(actions);
  });
}

// ---------------------------------------------------------------------------
// Auth
// ---------------------------------------------------------------------------

function startLogin() {
  const popup = window.open('/api/auth', 'stanza-auth', 'width=800,height=640,left=200,top=100');
  const handler = (e: MessageEvent) => {
    if (e.origin !== window.location.origin) return;
    if ((e.data as { type?: string })?.type !== 'stanza-auth') return;
    window.removeEventListener('message', handler);
    token = (e.data as { token: string }).token;
    localStorage.setItem(GH_TOKEN_KEY, token);
    popup?.close();
    document.body.classList.add('stanza-authed');
    updateLockBtn(true);
    bindListActions();
    showToast('Editor unlocked', 'success');
  };
  window.addEventListener('message', handler);
}

// ---------------------------------------------------------------------------
// GitHub API helpers
// ---------------------------------------------------------------------------

async function ghGet(path: string): Promise<{ sha: string; decoded: string }> {
  const res = await fetch(`https://api.github.com/repos/${REPO}/contents/${REPO_ROOT}/${path}`, {
    headers: {
      Authorization: `Bearer ${token}`,
      Accept: 'application/vnd.github+json',
      'X-GitHub-Api-Version': '2022-11-28',
      'User-Agent': 'Stanza-Editor/1.0',
    },
  });
  if (!res.ok) {
    const msg = (await res.json() as { message?: string }).message ?? res.statusText;
    throw new Error(`GitHub ${res.status}: ${msg}`);
  }
  const data = await res.json() as { sha: string; content: string };
  return { sha: data.sha, decoded: base64ToUtf8(data.content.replace(/\n/g, '')) };
}

async function apiSave(path: string, content: string, sha: string | undefined, message: string) {
  const res = await fetch('/api/save', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', Authorization: `Bearer ${token}` },
    body: JSON.stringify({ path: `${REPO_ROOT}/${path}`, content: utf8ToBase64(content), sha, message }),
  });
  const data = await res.json() as { ok?: boolean; error?: string };
  if (!data.ok) throw new Error(data.error ?? 'Save failed');
}

async function apiDelete(path: string, sha: string, message: string) {
  const res = await fetch('/api/delete', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', Authorization: `Bearer ${token}` },
    body: JSON.stringify({ path: `${REPO_ROOT}/${path}`, sha, message }),
  });
  const data = await res.json() as { ok?: boolean; error?: string };
  if (!data.ok) throw new Error(data.error ?? 'Delete failed');
}

// ---------------------------------------------------------------------------
// Modal
// ---------------------------------------------------------------------------

type FieldDef = {
  id: string;
  label: string;
  type: 'text' | 'textarea' | 'select';
  value?: string;
  options?: { value: string; label: string }[];
};

function showModal(
  title: string,
  fields: FieldDef[],
  onSave: (values: Record<string, string>) => Promise<void>,
) {
  const overlay = document.createElement('div');
  overlay.className = 'editor-overlay';
  overlay.id = 'stanza-editor-modal';
  overlay.innerHTML = `
    <div class="editor-box">
      <h3>${escHtml(title)}</h3>
      <form id="editor-form">
        ${fields
          .map(
            (f) => `
          <div class="editor-field">
            <label for="${f.id}">${escHtml(f.label)}</label>
            ${
              f.type === 'textarea'
                ? `<textarea id="${f.id}" name="${f.id}">${escHtml(f.value ?? '')}</textarea>`
                : f.type === 'select'
                  ? `<select id="${f.id}" name="${f.id}">${(f.options ?? []).map((o) => `<option value="${o.value}"${f.value === o.value ? ' selected' : ''}>${escHtml(o.label)}</option>`).join('')}</select>`
                  : `<input type="text" id="${f.id}" name="${f.id}" value="${escAttr(f.value ?? '')}" />`
            }
          </div>`,
          )
          .join('')}
        <div class="editor-actions">
          <button type="button" id="editor-cancel">Cancel</button>
          <button type="submit" class="btn-save" id="editor-save">Save</button>
        </div>
      </form>
    </div>`;
  document.body.appendChild(overlay);

  overlay.querySelector('#editor-cancel')!.addEventListener('click', closeModal);
  overlay.addEventListener('click', (e) => { if (e.target === overlay) closeModal(); });

  (overlay.querySelector('#editor-form') as HTMLFormElement).addEventListener('submit', async (e) => {
    e.preventDefault();
    const saveBtn = overlay.querySelector<HTMLButtonElement>('#editor-save')!;
    saveBtn.textContent = 'Saving…';
    saveBtn.disabled = true;

    const values: Record<string, string> = {};
    fields.forEach((f) => {
      const el = document.getElementById(f.id) as HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement;
      values[f.id] = el?.value ?? '';
    });

    try {
      await onSave(values);
      closeModal();
      showToast('Saved — rebuilding site…', 'success');
    } catch (err) {
      saveBtn.textContent = 'Save';
      saveBtn.disabled = false;
      showToast((err as Error).message, 'error');
    }
  });
}

function closeModal() {
  document.getElementById('stanza-editor-modal')?.remove();
}

// ---------------------------------------------------------------------------
// View handlers
// ---------------------------------------------------------------------------

async function editProfile() {
  let sha: string, data: Record<string, string>;
  try {
    const res = await ghGet('src/content/site.json');
    sha = res.sha;
    data = JSON.parse(res.decoded) as Record<string, string>;
  } catch (e) {
    showToast('Could not load profile: ' + (e as Error).message, 'error');
    return;
  }
  showModal('Edit Profile', [
    { id: 'author_name', label: 'Author name', type: 'text', value: data.author_name },
    { id: 'author_bio', label: 'Bio', type: 'textarea', value: data.author_bio },
    { id: 'author_photo', label: 'Photo URL', type: 'text', value: data.author_photo },
  ], async (values) => {
    await apiSave('src/content/site.json', JSON.stringify({ ...data, ...values }, null, 2), sha, 'Update author profile');
  });
}

async function editCollection() {
  const slug = document.body.dataset.collectionSlug ?? '';
  if (!slug) return;
  let sha: string, data: Record<string, unknown>;
  try {
    const res = await ghGet(`src/content/collections/${slug}.json`);
    sha = res.sha;
    data = JSON.parse(res.decoded) as Record<string, unknown>;
  } catch (e) {
    showToast('Could not load collection: ' + (e as Error).message, 'error');
    return;
  }
  showModal('Edit Collection', [
    { id: 'title', label: 'Title', type: 'text', value: String(data.title ?? '') },
    { id: 'author', label: 'Author', type: 'text', value: String(data.author ?? '') },
    { id: 'order', label: 'Order', type: 'text', value: String(data.order ?? '') },
  ], async (values) => {
    const order = parseInt(values.order);
    const updated = { ...data, title: values.title, author: values.author, ...(isNaN(order) ? {} : { order }) };
    await apiSave(`src/content/collections/${slug}.json`, JSON.stringify(updated, null, 2), sha, `Update collection ${slug}`);
  });
}

async function deleteCollection() {
  const slug = document.body.dataset.collectionSlug ?? '';
  if (!slug || !confirm(`Delete collection "${slug}"? This cannot be undone.`)) return;
  try {
    const { sha } = await ghGet(`src/content/collections/${slug}.json`);
    await apiDelete(`src/content/collections/${slug}.json`, sha, `Delete collection ${slug}`);
    showToast('Collection deleted — rebuilding…', 'success');
    setTimeout(() => { window.location.href = `/${document.body.dataset.lang ?? 'en'}/`; }, 2500);
  } catch (e) {
    showToast('Delete failed: ' + (e as Error).message, 'error');
  }
}

function newPoem() {
  const collSlug = document.body.dataset.collectionSlug ?? '';
  const lang = document.body.dataset.lang ?? 'en';
  showModal('New Poem', [
    { id: 'title', label: 'Title', type: 'text' },
    { id: 'translationKey', label: 'Slug (e.g. my-poem)', type: 'text' },
    { id: 'lang', label: 'Language', type: 'select', value: lang, options: [
      { value: 'en', label: 'English' },
      { value: 'el', label: 'Ελληνικά' },
    ]},
    { id: 'body', label: 'Poem body (Markdown)', type: 'textarea' },
  ], async (values) => {
    const slug = values.translationKey.trim() || slugify(values.title);
    const filename = `${slug}-${values.lang}`;
    const date = new Date().toISOString().slice(0, 10);
    const content = `---\ntitle: ${values.title}\ncollection: ${collSlug}\nlang: ${values.lang}\ntranslationKey: ${slug}\ndate: ${date}\n---\n\n# ${values.title}\n\n${values.body}`;
    await apiSave(`src/content/poems/${filename}.md`, content, undefined, `Add poem: ${values.title}`);
  });
}

async function editPoemById(poemId: string) {
  let sha: string, decoded: string;
  try {
    ({ sha, decoded } = await ghGet(`src/content/poems/${poemId}.md`));
  } catch (e) {
    showToast('Could not load poem: ' + (e as Error).message, 'error');
    return;
  }
  const { frontmatter, body } = parseFrontmatter(decoded);
  showModal('Edit Poem', [
    { id: 'title', label: 'Title', type: 'text', value: frontmatter.title },
    { id: 'body', label: 'Poem body (Markdown)', type: 'textarea', value: body.trimStart() },
  ], async (values) => {
    const content = buildFrontmatter({ ...frontmatter, title: values.title }) + '\n\n' + values.body;
    await apiSave(`src/content/poems/${poemId}.md`, content, sha, `Update poem: ${values.title}`);
  });
}

async function editPoem() {
  const poemId = document.body.dataset.poemId ?? '';
  if (poemId) return editPoemById(poemId);
}

async function deletePoemById(poemId: string) {
  if (!confirm(`Delete poem "${poemId}"? This cannot be undone.`)) return;
  try {
    const { sha } = await ghGet(`src/content/poems/${poemId}.md`);
    await apiDelete(`src/content/poems/${poemId}.md`, sha, `Delete poem ${poemId}`);
    showToast('Poem deleted — rebuilding…', 'success');
    setTimeout(() => window.location.reload(), 2500);
  } catch (e) {
    showToast('Delete failed: ' + (e as Error).message, 'error');
  }
}

async function deletePoem() {
  const poemId = document.body.dataset.poemId ?? '';
  if (poemId) return deletePoemById(poemId);
}

// ---------------------------------------------------------------------------
// Utilities
// ---------------------------------------------------------------------------

function parseFrontmatter(content: string): { frontmatter: Record<string, string>; body: string } {
  const m = content.match(/^---\r?\n([\s\S]*?)\r?\n---\r?\n([\s\S]*)$/);
  if (!m) return { frontmatter: {}, body: content };
  const fm: Record<string, string> = {};
  for (const line of m[1].split('\n')) {
    const i = line.indexOf(':');
    if (i > 0) fm[line.slice(0, i).trim()] = line.slice(i + 1).trim();
  }
  return { frontmatter: fm, body: m[2] };
}

function buildFrontmatter(fm: Record<string, string>): string {
  return `---\n${Object.entries(fm).filter(([, v]) => v !== undefined && v !== '').map(([k, v]) => `${k}: ${v}`).join('\n')}\n---`;
}

function slugify(s: string): string {
  return s.toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
}

function utf8ToBase64(str: string): string {
  const bytes = new TextEncoder().encode(str);
  const binStr = Array.from(bytes, (b) => String.fromCodePoint(b)).join('');
  return btoa(binStr);
}

function base64ToUtf8(b64: string): string {
  const binStr = atob(b64);
  const bytes = Uint8Array.from(binStr, (c) => c.codePointAt(0)!);
  return new TextDecoder().decode(bytes);
}

function escHtml(s: string): string {
  return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function escAttr(s: string): string {
  return s.replace(/"/g, '&quot;').replace(/'/g, '&#39;');
}

function showToast(msg: string, type: 'success' | 'error') {
  const el = document.createElement('div');
  el.className = `message ${type}`;
  el.textContent = msg;
  document.body.appendChild(el);
  setTimeout(() => el.remove(), 3500);
}

function editSvg(size: number): string {
  return `<svg width="${size}" height="${size}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>`;
}

function trashSvg(size: number): string {
  return `<svg width="${size}" height="${size}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>`;
}

// ---------------------------------------------------------------------------
// Run
// ---------------------------------------------------------------------------

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', init);
} else {
  init();
}

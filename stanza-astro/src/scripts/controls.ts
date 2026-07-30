/**
 * Public control-bar logic — sort, alignment, language.
 * localStorage keys match PHP exactly so visitor prefs survive migration.
 */

// ── Sort ──────────────────────────────────────────────────────────────────────

function applySort(order: string) {
  const list = document.querySelector('.poem-list');
  if (!list) return;
  const items = Array.from(list.querySelectorAll('.poem-item')) as HTMLElement[];
  if (items.length === 0) return;

  const lang = (document.body.dataset.lang as string) || 'en';

  items.sort((a, b) => {
    if (order === 'latest') {
      return parseInt(b.dataset.time ?? '0') - parseInt(a.dataset.time ?? '0');
    }
    return (a.dataset.title ?? '').localeCompare(b.dataset.title ?? '', lang);
  });

  items.forEach((item) => list.appendChild(item));
}

function updateSortButton(order: string) {
  const dropdown = document.getElementById('sort-dropdown');
  if (!dropdown) return;

  const btn = dropdown.querySelector('a');
  if (!btn) return;
  const labelValue = btn.querySelector('.sort-value');
  const iconContainer = btn.querySelector('.icon-wrapper');

  if (order === 'latest') {
    if (labelValue) labelValue.textContent = ': Latest';
    if (iconContainer)
      iconContainer.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 5h10"></path><path d="M11 9h7"></path><path d="M11 13h4"></path><path d="m3 17 3 3 3-3"></path><path d="M6 18V4"></path></svg>`;
  } else {
    if (labelValue) labelValue.textContent = ': A-Z';
    if (iconContainer)
      iconContainer.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>`;
  }

  dropdown.querySelectorAll('.dropdown-content button').forEach((b) => {
    const attr = b.getAttribute('onclick') ?? '';
    b.classList.toggle('active', attr.includes(order));
  });
}

function setSort(order: string) {
  localStorage.setItem('stanza_sort', order);
  applySort(order);
  updateSortButton(order);
  document.getElementById('sort-dropdown')?.classList.remove('active');
}

// ── Alignment ─────────────────────────────────────────────────────────────────

function applyAlignmentClass(align: string) {
  const body = document.body;
  if (!body.classList.contains('view-poem') && !body.classList.contains('view-list')) return;
  body.classList.remove('align-left', 'align-center', 'align-right');
  body.classList.add(`align-${align}`);
}

function setAlignment(align: string) {
  applyAlignmentClass(align);
  const view = document.body.classList.contains('view-poem') ? 'poem' : 'list';
  localStorage.setItem(`stanza_align_${view}`, align);

  document.querySelectorAll('#align-dropdown .dropdown-content button').forEach((btn) => {
    const attr = btn.getAttribute('onclick') ?? '';
    btn.classList.toggle('active', attr.includes(`'${align}'`));
  });

  document.getElementById('align-dropdown')?.classList.remove('active');
}

// ── Init ──────────────────────────────────────────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
  const savedSort = localStorage.getItem('stanza_sort') || 'alpha';
  applySort(savedSort);
  updateSortButton(savedSort);

  let currentAlign = 'center';
  if (document.body.classList.contains('view-poem')) {
    currentAlign = localStorage.getItem('stanza_align_poem') || 'center';
  } else if (document.body.classList.contains('view-list')) {
    currentAlign = localStorage.getItem('stanza_align_list') || 'center';
  }
  applyAlignmentClass(currentAlign);

  document.querySelectorAll('#align-dropdown .dropdown-content button').forEach((btn) => {
    const attr = btn.getAttribute('onclick') ?? '';
    btn.classList.toggle('active', attr.includes(`'${currentAlign}'`));
  });
});

// Expose to onclick= attributes in the toolbar HTML
(window as any).setSort = setSort;
(window as any).setAlignment = setAlignment;

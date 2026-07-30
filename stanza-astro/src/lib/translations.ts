/**
 * Ports the translation-key resolution from system/router.php:56-67.
 *
 * Priority:
 *   2 = exact lang match (slug_en / slug_el)
 *   1 = unsuffixed (no _en / _el)
 *   0 = other lang (fallback)
 *
 * Given a set of poems that share the same translationKey, returns
 * the best one for the requested language.
 */

import type { CollectionEntry } from 'astro:content';
import type { Lang } from '../i18n/ui';

type Poem = CollectionEntry<'poems'>;

export function resolveBestTranslation(poems: Poem[], lang: Lang): Poem | undefined {
  if (poems.length === 0) return undefined;

  let best: Poem | undefined;
  let bestScore = -1;

  for (const poem of poems) {
    const poemLang = poem.data.lang;
    let score: number;

    if (poemLang === lang) {
      score = 2;
    } else if (!poemLang) {
      score = 1;
    } else {
      score = 0;
    }

    if (score > bestScore) {
      bestScore = score;
      best = poem;
    }
  }

  return best;
}

/**
 * Groups poems by translationKey, then for each group resolves the
 * best poem for the given lang. Returns deduplicated list for display.
 */
export function resolvePoems(poems: Poem[], lang: Lang): Poem[] {
  const byKey = new Map<string, Poem[]>();

  for (const poem of poems) {
    const key = poem.data.translationKey ?? poem.id;
    if (!byKey.has(key)) byKey.set(key, []);
    byKey.get(key)!.push(poem);
  }

  const result: Poem[] = [];
  for (const group of byKey.values()) {
    const best = resolveBestTranslation(group, lang);
    if (best) result.push(best);
  }

  return result;
}

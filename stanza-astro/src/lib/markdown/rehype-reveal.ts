/**
 * rehype-reveal: mirrors the final HTML transformations of parsePoetryMarkdown.
 *
 * After remark-stanza and remark-rehype have run:
 * - div.stanza.reveal-on-scroll already set by remark-stanza's hProperties
 * - Inside stanzas: text newlines → <br> elements (nl2br equivalent)
 * - h1 → poem-title then reveal-on-scroll (matching PHP class order)
 * - h2-h6, ul, ol, blockquote, pre, table → reveal-on-scroll
 * - hr → poem-divider then reveal-on-scroll
 * - pre → code-block then reveal-on-scroll
 * - code (only outside pre) → inline-code
 * - a → target="_blank" rel="noopener"
 * - blockquote: strip inner <p> wrappers
 * - table: wrap in .poem-table-wrapper
 */

import type { Root, Element, Text, Properties, ElementContent } from 'hast';
import type { Plugin } from 'unified';
import { visit, SKIP } from 'unist-util-visit';

const REVEAL_TAGS = new Set(['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'hr', 'ul', 'ol', 'blockquote', 'pre', 'table']);

function addClass(props: Properties, ...classes: string[]) {
  const existing = (props.className as string[] | undefined) ?? [];
  const toAdd = classes.filter((c) => !existing.includes(c));
  props.className = [...existing, ...toAdd];
}

function prependClass(props: Properties, ...classes: string[]) {
  const existing = (props.className as string[] | undefined) ?? [];
  const toAdd = classes.filter((c) => !existing.includes(c));
  props.className = [...toAdd, ...existing];
}

/** nl2br: split text nodes on \n and insert <br> elements between parts */
function nl2brChildren(children: ElementContent[]): ElementContent[] {
  const result: ElementContent[] = [];
  for (const child of children) {
    if (child.type === 'text' && child.value.includes('\n')) {
      const parts = child.value.split('\n');
      parts.forEach((part, i) => {
        if (part) result.push({ type: 'text', value: part });
        if (i < parts.length - 1) {
          result.push({
            type: 'element',
            tagName: 'br',
            properties: {},
            children: [],
          } as Element);
        }
      });
    } else {
      result.push(child);
    }
  }
  return result;
}

const rehypeReveal: Plugin<[], Root> = function () {
  return (tree) => {
    // Strip <p> wrappers inside <blockquote> and trim surrounding whitespace text nodes
    visit(tree, 'element', (node: Element) => {
      if (node.tagName !== 'blockquote') return;
      const flatChildren: ElementContent[] = [];
      for (const child of node.children) {
        if (child.type === 'element' && child.tagName === 'p') {
          flatChildren.push(...child.children);
        } else if (child.type === 'text') {
          const trimmed = (child as Text).value.trim();
          if (trimmed) flatChildren.push({ type: 'text', value: trimmed } as Text);
        } else {
          flatChildren.push(child);
        }
      }
      node.children = flatChildren;
    });

    // Track which <code> elements are inside a <pre>
    const inPre = new Set<Element>();
    visit(tree, 'element', (node: Element) => {
      if (node.tagName === 'pre') {
        visit(node, 'element', (child: Element) => {
          if (child.tagName === 'code') inPre.add(child);
        });
      }
    });

    visit(tree, 'element', (node: Element) => {
      const tag = node.tagName;

      // Apply nl2br inside .stanza divs
      if (
        tag === 'div' &&
        (node.properties?.className as string[] | undefined)?.includes('stanza')
      ) {
        node.children = nl2brChildren(node.children);
        return;
      }

      if (REVEAL_TAGS.has(tag)) {
        if (tag === 'h1') {
          // PHP class order: poem-title then reveal-on-scroll
          addClass(node.properties, 'poem-title', 'reveal-on-scroll');
        } else if (tag === 'hr') {
          // PHP class order: poem-divider then reveal-on-scroll
          addClass(node.properties, 'poem-divider', 'reveal-on-scroll');
        } else if (tag === 'pre') {
          // PHP class order: code-block then reveal-on-scroll
          addClass(node.properties, 'code-block', 'reveal-on-scroll');
        } else {
          addClass(node.properties, 'reveal-on-scroll');
        }
      }

      // links → target="_blank" rel="noopener"
      if (tag === 'a') {
        node.properties.target = '_blank';
        node.properties.rel = 'noopener';
      }
    });

    // Add .inline-code only to code NOT inside pre
    visit(tree, 'element', (node: Element) => {
      if (node.tagName === 'code' && !inPre.has(node)) {
        addClass(node.properties, 'inline-code');
      }
    });

    // Wrap tables in .poem-table-wrapper
    visit(tree, 'element', (node: Element, index, parent: any) => {
      if (node.tagName !== 'table' || !parent || index == null) return;
      const parentEl = parent as Element;
      if (
        parentEl.type === 'element' &&
        parentEl.tagName === 'div' &&
        (parentEl.properties?.className as string[] | undefined)?.includes('poem-table-wrapper')
      ) return;

      const wrapper: Element = {
        type: 'element',
        tagName: 'div',
        properties: { className: ['poem-table-wrapper'] },
        children: [node],
      };
      parent.children[index] = wrapper;
      return SKIP;
    });
  };
};

export default rehypeReveal;

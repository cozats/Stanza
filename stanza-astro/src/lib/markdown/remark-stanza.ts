/**
 * remark-stanza: marks paragraph nodes with a data flag so rehype-reveal
 * can convert them to <div class="stanza reveal-on-scroll">.
 *
 * Standard remark produces <p> elements from blank-line-separated blocks.
 * We mark each paragraph with data.stanza = true so the rehype plugin
 * knows to convert them to stanza divs.
 */

import type { Root, Paragraph } from 'mdast';
import type { Plugin } from 'unified';

const remarkStanza: Plugin<[], Root> = function () {
  return (tree) => {
    for (const node of tree.children) {
      if (node.type === 'paragraph') {
        const para = node as Paragraph;
        if (!para.data) para.data = {};
        (para.data as any).stanza = true;
        // Signal remark-rehype to use a div instead of p
        (para.data as any).hName = 'div';
        (para.data as any).hProperties = { className: ['stanza', 'reveal-on-scroll'] };
      }
    }
  };
};

export default remarkStanza;

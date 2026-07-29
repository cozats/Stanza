<?php
/**
 * Generates golden HTML files from the real PHP parsePoetryMarkdown parser.
 * Usage: php scripts/gen-golden.php
 * Output: tests/fixtures/*.expected.html
 */
declare(strict_types=1);

// Inline parsePoetryMarkdown from system/core.php
function parsePoetryMarkdown(string $text): string {
    if (empty($text)) return "";
    $text = preg_replace('/<br\s*\/?>/i', "\n", $text);
    $text = preg_replace('/\\\\n/', "\n", $text);
    $text = preg_replace('/\\\([~=\[\]$])/', '$1', $text);

    $codeBlocks = [];
    $text = preg_replace_callback('/```(\w*)\n(.*?)```/s', function ($matches) use (&$codeBlocks) {
        $id = '{{CODE_BLOCK_' . count($codeBlocks) . '}}';
        $lang = $matches[1] ?: 'text';
        $code = htmlspecialchars($matches[2], ENT_QUOTES, 'UTF-8');
        $codeBlocks[$id] = '<pre class="code-block reveal-on-scroll"><code class="language-' . $lang . '">' . $code . '</code></pre>';
        return $id;
    }, $text);

    $inlineCode = [];
    $text = preg_replace_callback('/`([^`]+)`/', function ($matches) use (&$inlineCode) {
        $id = '{{INLINE_CODE_' . count($inlineCode) . '}}';
        $inlineCode[$id] = '<code class="inline-code">' . htmlspecialchars($matches[1], ENT_QUOTES, 'UTF-8') . '</code>';
        return $id;
    }, $text);

    $text = preg_replace('/^[-*_]{3,}\s*$/m', '{{HR}}', $text);
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    $text = str_replace('{{HR}}', '<hr class="poem-divider reveal-on-scroll">', $text);

    foreach ($codeBlocks as $id => $html) { $text = str_replace(htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8'), $html, $text); }
    foreach ($inlineCode as $id => $html) { $text = str_replace(htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8'), $html, $text); }

    $text = preg_replace('/^######\s+(.+)$/m', '<h6 class="reveal-on-scroll">$1</h6>', $text);
    $text = preg_replace('/^#####\s+(.+)$/m', '<h5 class="reveal-on-scroll">$1</h5>', $text);
    $text = preg_replace('/^####\s+(.+)$/m', '<h4 class="reveal-on-scroll">$1</h4>', $text);
    $text = preg_replace('/^###\s+(.+)$/m', '<h3 class="reveal-on-scroll">$1</h3>', $text);
    $text = preg_replace('/^##\s+(.+)$/m', '<h2 class="reveal-on-scroll">$1</h2>', $text);
    $text = preg_replace('/^#\s+(.+)$/m', '<h1 class="poem-title reveal-on-scroll">$1</h1>', $text);

    $text = preg_replace_callback('/^(&gt;.+(?:\n&gt;.*)*)$/m', function ($matches) {
        $quote = preg_replace('/^&gt;\s?/m', '', $matches[1]);
        return '<blockquote class="reveal-on-scroll">' . $quote . '</blockquote>';
    }, $text);

    $text = preg_replace('/\[([^\]]+)\]\(([^\)]+)\)/', '<a href="$2" target="_blank" rel="noopener">$1</a>', $text);
    $text = preg_replace('/\*\*\*(.+?)\*\*\*/', '<strong><em>$1</em></strong>', $text);
    $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);
    $text = preg_replace('/__(.+?)__/', '<strong>$1</strong>', $text);
    $text = preg_replace('/\*([^*\n]+?)\*/', '<em>$1</em>', $text);
    $text = preg_replace('/_([^_\n]+?)_/', '<em>$1</em>', $text);
    $text = preg_replace('/~~(.+?)~~/', '<del>$1</del>', $text);

    $text = preg_replace_callback('/((?:\|.+?\|\n)+)/s', function ($matches) {
        $rows = array_filter(explode("\n", trim($matches[1])));
        if (count($rows) < 2) return $matches[1];
        $html = '<div class="poem-table-wrapper"><table class="reveal-on-scroll">';
        foreach ($rows as $index => $row) {
            if (preg_match('/^\|[\s:-]+\|[\s:-]+/', trim($row))) continue;
            $cols = explode('|', trim($row, '|'));
            $html .= '<tr>';
            foreach ($cols as $col) { $tag = ($index === 0) ? 'th' : 'td'; $html .= "<$tag>" . trim($col) . "</$tag>"; }
            $html .= '</tr>';
        }
        $html .= '</table></div>';
        return $html;
    }, $text);

    $text = preg_replace_callback('/^([\*\-]\s+.*(?:\n(?:[\s\*\-]|\s{2,}).*)*)$/m', function ($matches) {
        $lines = explode("\n", $matches[1]);
        $html = '<ul class="poem-list reveal-on-scroll">';
        $currentLevel = 0;
        foreach ($lines as $line) {
            if (!preg_match('/^(\s*)([\*\-])\s+(.*)$/', $line, $m)) continue;
            $indent = strlen($m[1]);
            $level = (int) floor($indent / 2);
            if ($level > $currentLevel) { $html .= '<ul>'; $currentLevel = $level; }
            elseif ($level < $currentLevel) { $html .= str_repeat('</ul>', (int) ($currentLevel - $level)); $currentLevel = $level; }
            $html .= '<li>' . trim($m[3]) . '</li>';
        }
        $html .= str_repeat('</ul>', (int) $currentLevel) . '</ul>';
        return $html;
    }, $text);

    $text = preg_replace_callback('/^(\d+\.\s+.*(?:\n(?:\s*\d+\.|\s{2,}).*)*)$/m', function ($matches) {
        $lines = explode("\n", $matches[1]);
        $html = '<ol class="poem-list reveal-on-scroll">';
        foreach ($lines as $line) { $content = preg_replace('/^\s*\d+\.\s+/', '', trim($line)); if (!empty($content)) $html .= '<li>' . $content . '</li>'; }
        $html .= '</ol>';
        return $html;
    }, $text);

    $parts = preg_split('/(<h[1-6][^>]*>.*?<\/h[1-6]>|<hr[^>]*>|<ul[^>]*>.*?<\/ul>|<ol[^>]*>.*?<\/ol>|<blockquote[^>]*>.*?<\/blockquote>|<pre[^>]*>.*?<\/pre>|<div class="poem-table-wrapper">.*?<\/div>)/s', $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    $html = '';
    foreach ($parts as $part) {
        $part = trim($part);
        if (empty($part)) continue;
        if (preg_match('/^<(h[1-6]|hr|ul|ol|blockquote|pre|div)/', $part)) { $html .= $part . "\n"; }
        else {
            $stanzas = preg_split('/\n\s*\n/', $part);
            foreach ($stanzas as $stanza) { $stanza = trim($stanza); if (!empty($stanza)) $html .= '<div class="stanza reveal-on-scroll">' . nl2br($stanza) . '</div>'; }
        }
    }
    return $html;
}

$fixturesDir = __DIR__ . '/../tests/fixtures/';
$inputs = glob($fixturesDir . '*.input.md');

if (empty($inputs)) {
    echo "No fixture files found in $fixturesDir\n";
    exit(1);
}

foreach ($inputs as $input) {
    $content = file_get_contents($input);
    $html = parsePoetryMarkdown($content);
    $outputFile = str_replace('.input.md', '.expected.html', $input);
    file_put_contents($outputFile, $html);
    echo "Generated: " . basename($outputFile) . "\n";
}

echo "Done.\n";

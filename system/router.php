<?php
declare(strict_types=1);

require_once __DIR__ . '/core.php';

$isAdmin = !empty($_SESSION['is_admin']);

// Routing parameters
$c = $_GET['c'] ?? null; // collection slug
$v = $_GET['v'] ?? 'home'; // view (home, list, poem)
$p = $_GET['p'] ?? null; // poem filename

$collections = getCollections();

// Resolve View and Data
if ($c) {
    $collectionDir = COLLECTIONS_DIR . basename($c) . '/';
    if (!is_dir($collectionDir)) {
        header('Location: index.php');
        exit;
    }

    $metaFile = $collectionDir . 'collection.json';
    $meta = file_exists($metaFile) ? json_decode(file_get_contents($metaFile), true) : [];
    
    // Legacy support for meta from index.php if json not yet created
    if (empty($meta)) {
        $indexFile = $collectionDir . 'index.php';
        if (file_exists($indexFile)) {
            $content = file_get_contents($indexFile);
            preg_match("/define\('POET_NAME',\s*'([^']*)'\);/", $content, $nameMatch);
            preg_match("/define\('SITE_TITLE',\s*'([^']*)'\);/", $content, $titleMatch);
            $meta = ['author' => $nameMatch[1] ?? '', 'title' => $titleMatch[1] ?? $c];
        }
    }

    $displayPoetName = $meta['author'] ?: $lang['poet_name'];
    $displaySiteTitle = $meta['title'] ?: $lang['site_title'];
    $poemsDir = $collectionDir . 'poems/';

    if ($p) {
        $view = 'poem';
        $file = getLocalizedFilename($poemsDir, basename($p), $currentLang);
        $filepath = $poemsDir . $file;
        if (file_exists($filepath)) {
            $content = file_get_contents($filepath);
            $poemHtml = parsePoetryMarkdown($content !== false ? $content : "");
            $breadcrumbPoemTitle = getPoemTitle($filepath);
        } else {
            $view = 'list';
        }
    } elseif ($v === 'list') {
        $view = 'list';
        // List fetching logic (moved from template.php)
        $files = scandir($poemsDir);
        $groups = [];
        foreach ($files as $f) {
            if ($f === '.' || $f === '..' || is_dir($poemsDir . $f)) continue;
            $info = pathinfo($f);
            $base = $info['filename'];
            $cleanBase = preg_replace('/_(en|el)$/', '', $base);
            $priority = preg_match('/_' . $currentLang . '$/', $base) ? 2 : (!preg_match('/_(en|el)$/', $base) ? 1 : 0);
            if (!isset($groups[$cleanBase]) || $priority > $groups[$cleanBase]['priority']) {
                $groups[$cleanBase] = ['filename' => $f, 'priority' => $priority];
            }
        }
        $poems = array_column($groups, 'filename');
        // Sort logic will be handled in JS or initially here
    } else {
        $view = 'home';
        // Match target site EXACT literal string with no newlines
        $poemHtml = '<h1 class="poet-name-header reveal-on-scroll">' . htmlspecialchars($displayPoetName) . '</h1>';
        $poemHtml .= '<div class="stanza reveal-on-scroll"><h2 class="reveal-on-scroll"><em>' . htmlspecialchars($displaySiteTitle) . '</em></h2></div>';
        $poemHtml .= '<div class="stanza reveal-on-scroll"><h3 class="reveal-on-scroll">' . htmlspecialchars($lang['poems_label']) . '</h3></div>';
    }
} else {
    $view = 'landing';
}

// Ensure $c is null if not set to avoid issues in toolbar/layout
if (!isset($c)) $c = null;

// Global actions (Login, Profile, Collection Management)
if (isset($_GET['admin']) && !$isAdmin) $view = 'admin_login';
if (isset($_GET['logout'])) {
    unset($_SESSION['is_admin']);
    $params = $_GET;
    unset($params['logout']);
    $queryString = http_build_query($params);
    header('Location: ' . getCurrentBaseUrl() . ($queryString ? '?' . $queryString : ''));
    exit;
}
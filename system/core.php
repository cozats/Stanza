<?php
declare(strict_types=1);

// Increase upload limits
ini_set('upload_max_filesize', '64M');
ini_set('post_max_size', '64M');
ini_set('memory_limit', '128M');

// Set custom session path
$sessionPath = dirname(__DIR__) . '/sessions';
if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0755, true);
}
session_save_path($sessionPath);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
 |--------------------------------------------------------------------------
 | CONFIGURATION & PATHS
 |--------------------------------------------------------------------------
 */

define('ROOT_DIR', dirname(__DIR__));
define('CONFIG_FILE', ROOT_DIR . '/config.json');
define('UPLOADS_DIR', ROOT_DIR . '/uploads/');
define('COLLECTIONS_DIR', ROOT_DIR . '/collections/');
if (!is_dir(COLLECTIONS_DIR)) {
    mkdir(COLLECTIONS_DIR, 0755, true);
}
define('SYSTEM_DIR', ROOT_DIR . '/system/');
define('UI_LANGUAGE', 'en');

// Default configuration
$defaultConfig = [
    'author_name' => 'Poet Name',
    'author_bio' => 'This will be your about paragraph.',
    'author_photo' => '',
    'admin_password_hash' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
];

function loadConfig(): array {
    global $defaultConfig;
    if (file_exists(CONFIG_FILE)) {
        $config = json_decode(file_get_contents(CONFIG_FILE), true);
        return array_merge($defaultConfig, $config ?? []);
    }
    return $defaultConfig;
}

function saveConfig(array $config): bool {
    return file_put_contents(CONFIG_FILE, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
}

function slugify(string $text): string {
    // Replace non-letter or digits by _
    $text = preg_replace('~[^\p{L}\p{N}]+~u', '_', $text);
    // Transliterate (if needed, but simple regex is safer for all languages)
    $text = trim($text, '_');
    // lowercase
    $text = mb_strtolower($text);
    if (empty($text)) return 'collection_' . time();
    return $text;
}

$config = loadConfig();
define('ADMIN_PASSWORD_HASH', $config['admin_password_hash']);

/*
 |--------------------------------------------------------------------------
 | LOCALES
 |--------------------------------------------------------------------------
 */

$locales = [
    'el' => [
        'lang_toggle' => 'English',
        'collections_title' => 'Συλλογές',
        'no_collections' => 'Δεν υπάρχουν ακόμη δημοσιευμένες συλλογές.',
        'no_poems' => 'Δεν βρέθηκαν ποιήματα. Ανεβάστε ή γράψτε ένα',
        'add_collection' => 'Νέα Συλλογή',
        'author_name_label' => 'Όνομα Ποιητή',
        'collection_title_label' => 'Τίτλος Συλλογής',
        'btn_create' => 'Δημιουργία',
        'btn_cancel' => 'Ακύρωση',
        'btn_login' => 'Είσοδος',
        'btn_save' => 'Αποθήκευση',
        'pwd_label' => 'Κωδικός',
        'management' => 'Διαχείριση',
        'exit' => 'Έξοδος',
        'theme_dark' => 'Σκοτάδι',
        'theme_light' => 'Φως',
        'msg_created' => 'Η συλλογή δημιουργήθηκε.',
        'msg_error' => 'Σφάλμα.',
        'msg_saved' => 'Όλες οι αλλαγές αποθηκεύτηκαν.',
        'msg_deleted' => 'Η συλλογή διαγράφηκε.',
        'msg_del_error' => 'Σφάλμα κατά τη διαγραφή.',
        'about_link' => 'Σχετικά με το Stanza',
        'home' => 'Αρχική',
        'profile' => 'Προφίλ',
        'contents' => 'Περιεχόμενα',
        'edit_profile' => 'Επεξεργασία Προφίλ',
        'edit_collection' => 'Επεξεργασία Συλλογής',
        'bio_label' => 'Βιογραφικό',
        'photo_label' => 'Φωτογραφία',
        'delete_collection' => 'Διαγραφή',
        'confirm_delete' => 'Είστε σίγουροι ότι θέλετε να διαγράψετε αυτή τη συλλογή;',
        'choose_file' => 'Επιλογή αρχείου',
        'select_collection' => 'Επιλέξτε συλλογή',
        'new_password_label' => 'Νέος Κωδικός',
        'confirm_password_label' => 'Επαλήθευση Κωδικού',
        'msg_pwd_mismatch' => 'Οι κωδικοί δεν ταιριάζουν.',
        'poet_name' => 'Όνομα Ποιητή',
        'site_title' => 'Τίτλος Συλλογής',
        'archive' => 'Συλλογή',
        'delete_inline' => '(διαγραφή)',
        'confirm_delete_poem' => 'Είστε σίγουροι για τη διαγραφή;',
        'back_to_archive' => '← Επιστροφή στη Συλλογή',
        'view_poems' => 'Δείτε τα ποιήματα',
        'add_poem' => 'Προσθήκη Ποιήματος',
        'delete_poems' => 'Διαγραφή Ποιημάτων',
        'upload_title' => 'Ανεβάστε νέο ποίημα',
        'file_label' => 'Επιλογή αρχείου (.md/.txt)',
        'pwd_ph' => 'Κωδικός ασφαλείας',
        'btn_upload' => 'Ανέβασμα',
        'poems_label' => 'Ποιήματα',
        'msg_pwd_err' => 'Λάθος κωδικός.',
        'msg_upload_err' => 'Σφάλμα μεταφόρτωσης.',
        'msg_ext_err' => 'Επιτρέπονται μόνο αρχεία .md ή .txt.',
        'msg_size_err' => 'Το αρχείο είναι πολύ μεγάλο.',
        'msg_success' => 'Το ποίημα ανέβηκε επιτυχώς.',
        'msg_move_err' => 'Αποτυχία αποθήκευσης αρχείου.',
        'msg_del_success' => 'Το ποίημα διαγράφηκε.',
        'msg_del_err' => 'Αποτυχία διαγραφής.',
        'welcome_msg' => 'Καλωσήρθατε. Παρακαλώ ανεβάστε περιεχομενo για την αρχική σελίδα.',
        'sort' => 'Ταξινόμηση',
        'sort_alpha' => 'Α-Ω',
        'sort_latest' => 'Νεότερα',
        'align' => 'Στοίχιση',
        'align_left' => 'Αριστερά',
        'align_center' => 'Κέντρο',
        'align_right' => 'Δεξιά',
    ],
    'en' => [
        'lang_toggle' => 'Ελληνικά',
        'collections_title' => 'Collections',
        'no_collections' => 'No published collections yet.',
        'no_poems' => 'No poems found. Upload or write one',
        'add_collection' => 'New Collection',
        'author_name_label' => 'Poet Name',
        'collection_title_label' => 'Collection Title',
        'btn_create' => 'Create',
        'btn_cancel' => 'Cancel',
        'btn_login' => 'Login',
        'btn_save' => 'Save',
        'pwd_label' => 'Password',
        'management' => 'Management',
        'exit' => 'Exit',
        'theme_dark' => 'Dark',
        'theme_light' => 'Light',
        'msg_created' => 'Collection created.',
        'msg_error' => 'Error.',
        'msg_saved' => 'All changes were saved.',
        'msg_deleted' => 'Collection deleted.',
        'msg_del_error' => 'Error deleting collection.',
        'about_link' => 'About Stanza',
        'home' => 'Home',
        'profile' => 'Profile',
        'contents' => 'Index',
        'edit_profile' => 'Edit Profile',
        'edit_collection' => 'Edit Collection',
        'bio_label' => 'Bio',
        'photo_label' => 'Photo',
        'delete_collection' => 'Delete',
        'confirm_delete' => 'Are you sure you want to delete this collection?',
        'choose_file' => 'Choose file',
        'select_collection' => 'Select collection',
        'new_password_label' => 'New Password',
        'confirm_password_label' => 'Confirm Password',
        'msg_pwd_mismatch' => 'Passwords do not match.',
        'poet_name' => 'Poet Name',
        'site_title' => 'Collection Title',
        'archive' => 'Collection',
        'delete_inline' => '(delete)',
        'confirm_delete_poem' => 'Are you sure you want to delete this poem?',
        'back_to_archive' => '← Back to Collection',
        'view_poems' => 'View Poems',
        'add_poem' => 'Add Poem',
        'delete_poems' => 'Delete Poems',
        'upload_title' => 'Upload new poem',
        'file_label' => 'Choose file (.md/.txt)',
        'pwd_ph' => 'Security code',
        'btn_upload' => 'Upload',
        'poems_label' => 'Poems',
        'msg_pwd_err' => 'Incorrect password.',
        'msg_upload_err' => 'File upload error.',
        'msg_ext_err' => 'Only .md or .txt files are allowed.',
        'msg_size_err' => 'File too large.',
        'msg_success' => 'Poem uploaded successfully.',
        'msg_move_err' => 'Failed to move uploaded file.',
        'msg_del_success' => 'Poem deleted.',
        'msg_del_err' => 'Failed to delete poem.',
        'welcome_msg' => 'Welcome. Please upload the landing page content.',
        'sort' => 'Sort',
        'sort_alpha' => 'A-Z',
        'sort_latest' => 'Latest',
        'align' => 'Alignment',
        'align_left' => 'Left',
        'align_center' => 'Center',
        'align_right' => 'Right',
    ]
];

if (isset($_GET['lang'])) {
    $_SESSION['lang'] = in_array($_GET['lang'], ['el', 'en']) ? $_GET['lang'] : UI_LANGUAGE;
    $params = $_GET;
    unset($params['lang']);
    $queryString = http_build_query($params);
    header('Location: ' . explode('?', $_SERVER['REQUEST_URI'])[0] . ($queryString ? '?' . $queryString : ''));
    exit;
}
$currentLang = $_SESSION['lang'] ?? UI_LANGUAGE;
$lang = $locales[$currentLang] ?? $locales['en'];

/*
 |--------------------------------------------------------------------------
 | HELPERS
 |--------------------------------------------------------------------------
 */

function getCurrentBaseUrl(): string {
    return explode('?', $_SERVER['REQUEST_URI'] ?? '/')[0];
}

function deleteDirectory(string $dir): bool {
    if (!is_dir($dir)) return false;
    $files = array_diff(scandir($dir), ['.', '..']);
    foreach ($files as $file) {
        $path = $dir . '/' . $file;
        is_dir($path) ? deleteDirectory($path) : unlink($path);
    }
    return rmdir($dir);
}

function getCollections(): array {
    $collections = [];
    if (!is_dir(COLLECTIONS_DIR)) return $collections;

    $dirs = scandir(COLLECTIONS_DIR);
    foreach ($dirs as $dir) {
        if ($dir === '.' || $dir === '..') continue;
        $path = COLLECTIONS_DIR . $dir . '/';
        if (!is_dir($path)) continue;

        $metaFile = $path . 'collection.json';
        if (file_exists($metaFile)) {
            $meta = json_decode(file_get_contents($metaFile), true);
            $collections[] = [
                'slug' => $dir,
                'author' => $meta['author'] ?? '',
                'title' => $meta['title'] ?? $dir,
                'path' => '?c=' . $dir
            ];
        } else {
            // Fallback for legacy structure
            $indexFile = $path . 'index.php';
            if (file_exists($indexFile)) {
                $content = file_get_contents($indexFile);
                preg_match("/define\('POET_NAME',\s*'([^']*)'\);/", $content, $nameMatch);
                preg_match("/define\('SITE_TITLE',\s*'([^']*)'\);/", $content, $titleMatch);
                $collections[] = [
                    'slug' => $dir,
                    'author' => $nameMatch[1] ?? '',
                    'title' => $titleMatch[1] ?? $dir,
                    'path' => '?c=' . $dir
                ];
            }
        }
    }
    return $collections;
}

function getPoemTitle(string $filepath): string {
    $handle = @fopen($filepath, 'r');
    if (!$handle) return '';
    $title = '';
    while (($line = fgets($handle)) !== false) {
        $line = trim($line);
        if (strpos($line, '# ') === 0) {
            $title = trim(substr($line, 2));
            break;
        }
        if (ftell($handle) > 1024) break;
    }
    fclose($handle);
    return $title;
}

function getLocalizedFilename(string $dir, string $filename, string $lang): string {
    $info = pathinfo($filename);
    $ext = $info['extension'] ?? 'md';
    $base = $info['filename'];
    $cleanBase = preg_replace('/_(en|el)$/', '', $base);
    $localized = $cleanBase . '_' . $lang . '.' . $ext;
    return file_exists($dir . $localized) ? $localized : $filename;
}

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
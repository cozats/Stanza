<?php
declare(strict_types=1);

// Set custom session path for environments with restrictive default paths
// Note: We use the root sessions directory
$sessionPath = dirname(__DIR__, 2) . '/sessions';
if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0755, true);
}
session_save_path($sessionPath);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
 |--------------------------------------------------------------------------
 | USER CONFIGURATION / ΡΥΘΜΙΣΕΙΣ ΧΡΗΣΤΗ
 |--------------------------------------------------------------------------
 */

// Poet's full name (Displayed in Footer & Home) / Το ονοματεπώνυμο του ποιητή
// Leave empty to use localized placeholders / Αφήστε κενό για χρήση αυτόματων placeholders
define('POET_NAME', 'Poet Name');

// The title of your collection / Ο τίτλος της συλλογής σας
// Leave empty to use localized placeholders / Αφήστε κενό για χρήση αυτόματων placeholders
define('SITE_TITLE', 'Collection Title');

// Default UI Language (Options: 'el', 'en') / Προεπιλεγμένη γλώσσα (Επιλογές: 'el', 'en')
define('UI_LANGUAGE', 'en');

// Load admin password from main config.json (shared with landing page)
$configPath = dirname(__DIR__, 2) . '/config.json';
$adminPasswordHash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // default
$config = [];
if (file_exists($configPath)) {
    $config = json_decode(file_get_contents($configPath), true);
    if (!empty($config['admin_password_hash'])) {
        $adminPasswordHash = $config['admin_password_hash'];
    }
}

// Handle Admin Login Submission (uses shared password from config.json)
if (isset($_POST['admin_login'])) {
    $password = $_POST['password'] ?? '';
    if (password_verify($password, $adminPasswordHash)) {
        $_SESSION['is_admin'] = true;
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    } else {
        $_SESSION['message'] = 'Incorrect password / Λάθος κωδικός.';
        $_SESSION['msg_type'] = 'error';
    }
}



/*
 |--------------------------------------------------------------------------
 | SYSTEM CONFIGURATION / ΡΥΘΜΙΣΕΙΣ ΣΥΣΤΗΜΑΤΟΣ
 |--------------------------------------------------------------------------
 */

define('POEMS_DIR', __DIR__ . '/poems/');
define('MAX_FILE_SIZE', 2 * 1024 * 1024);

// Translation Dictionary
$locales = [
    'el' => [
        'poet_name' => 'Όνομα Ποιητή',
        'site_title' => 'Τίτλος Συλλογής',
        'archive' => 'Συλλογή',
        'theme_dark' => 'Σκοτάδι',
        'theme_light' => 'Φως',
        'no_poems' => 'Δεν υπάρχουν ποιήματα ακόμα.',
        'delete_inline' => '(διαγραφή)',
        'confirm_delete' => 'Είστε σίγουροι για τη διαγραφή;',
        'back_to_archive' => 'Επιστροφή στη Συλλογή',
        'view_poems' => 'Δείτε τα ποιήματα',
        'management' => 'Διαχείριση',
        'add_poem' => 'Προσθήκη Ποιήματος',
        'delete_poems' => 'Διαγραφή Ποιημάτων',
        'delete_collection' => 'Διαγραφή',
        'exit' => 'Έξοδος',
        'home' => 'Αρχική',
        'profile' => 'Προφίλ',
        'contents' => 'Περιεχόμενα',
        'upload_title' => 'Ανεβάστε νέο ποίημα',
        'file_label' => 'Επιλογή αρχείου (.md/.txt)',
        'pwd_label' => 'Κωδικός',
        'pwd_ph' => 'Κωδικός ασφαλείας',
        'btn_upload' => 'Ανέβασμα',
        'btn_login' => 'Είσοδος',
        'btn_cancel' => 'Ακύρωση',
        'lang_toggle' => 'English',
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
        'edit_profile' => 'Επεξεργασία Προφίλ',
        'bio_label' => 'Βιογραφικό',
        'photo_label' => 'Φωτογραφία',
        'choose_file' => 'Επιλογή αρχείου',
        'new_password_label' => 'Νέος Κωδικός',
        'confirm_password_label' => 'Επαλήθευση Κωδικού',
        'btn_save' => 'Αποθήκευση',
        'msg_saved' => 'Οι αλλαγές αποθηκεύτηκαν.',
        'msg_pwd_mismatch' => 'Οι κωδικοί δεν ταιριάζουν.',
    ],
    'en' => [
        'poet_name' => 'Poet Name',
        'site_title' => 'Collection Title',
        'archive' => 'Collection',
        'theme_dark' => 'Dark',
        'theme_light' => 'Light',
        'no_poems' => 'No poems yet.',
        'delete_inline' => '(delete)',
        'confirm_delete' => 'Are you sure you want to delete this poem?',
        'back_to_archive' => 'Back to Collection',
        'view_poems' => 'View Poems',
        'management' => 'Management',
        'add_poem' => 'Add Poem',
        'delete_poems' => 'Delete Poems',
        'delete_collection' => 'Delete',
        'exit' => 'Exit',
        'home' => 'Home',
        'profile' => 'Profile',
        'contents' => 'Index',
        'upload_title' => 'Upload new poem',
        'file_label' => 'Choose file (.md/.txt)',
        'pwd_label' => 'Password',
        'pwd_ph' => 'Security code',
        'btn_upload' => 'Upload',
        'btn_login' => 'Login',
        'btn_cancel' => 'Cancel',
        'lang_toggle' => 'Ελληνικά',
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
        'edit_profile' => 'Edit Profile',
        'bio_label' => 'Bio',
        'photo_label' => 'Photo',
        'choose_file' => 'Choose file',
        'new_password_label' => 'New Password',
        'confirm_password_label' => 'Confirm Password',
        'btn_save' => 'Save',
        'msg_saved' => 'Changes saved.',
        'msg_pwd_mismatch' => 'Passwords do not match.',
    ]
];

// Initialize Session


// Ensure poems directory exists
if (!is_dir(POEMS_DIR)) {
    mkdir(POEMS_DIR, 0755, true);
}

// Helper: Safely get current script URL without query params
function getCurrentBaseUrl(): string
{
    return explode('?', $_SERVER['REQUEST_URI'] ?? '/')[0];
}

// Handle Language Switch
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = in_array($_GET['lang'], ['el', 'en']) ? $_GET['lang'] : UI_LANGUAGE;
    $params = $_GET;
    unset($params['lang']);
    $queryString = http_build_query($params);
    header('Location: ' . getCurrentBaseUrl() . ($queryString ? '?' . $queryString : ''));
    exit;
}
$currentLang = $_SESSION['lang'] ?? UI_LANGUAGE;
$lang = $locales[$currentLang] ?? $locales['en'];

// Handle Sort State (Client-side now handles the toggle, but we keep this for initial load if needed)
$sortOrder = $_SESSION['sort_order'] ?? 'alpha';

if (isset($_GET['logout'])) {
    unset($_SESSION['is_admin']);
    $params = $_GET;
    unset($params['logout'], $params['admin']);
    $queryString = http_build_query($params);
    header('Location: ' . getCurrentBaseUrl() . ($queryString ? '?' . $queryString : ''));
    exit;
}
$isAdmin = !empty($_SESSION['is_admin']);

// Resolve displayed poet name and site title
$displayPoetName = POET_NAME ?: $lang['poet_name'];
$displaySiteTitle = SITE_TITLE ?: $lang['site_title'];

/**
 * --- Helper Functions ---
 */

function getLocalizedFilename(string $filename, string $lang): string
{
    $info = pathinfo($filename);
    $ext = $info['extension'] ?? 'md';
    $base = $info['filename'];
    $cleanBase = preg_replace('/_(en|el)$/', '', $base);
    $localized = $cleanBase . '_' . $lang . '.' . $ext;
    return file_exists(POEMS_DIR . $localized) ? $localized : $filename;
}

function getPoemTitle(string $filepath): string
{
    $handle = @fopen($filepath, 'r');
    if (!$handle)
        return '';
    $title = '';
    while (($line = fgets($handle)) !== false) {
        $line = trim($line);
        if (strpos($line, '# ') === 0) {
            $title = trim(substr($line, 2));
            break;
        }
        if (ftell($handle) > 1024)
            break;
    }
    fclose($handle);
    return $title;
}

function parsePoetryMarkdown(string $text): string
{
    // First, convert any <br> tags in markdown to actual newlines (before escaping)
    $text = preg_replace('/<br\s*\/?>/i', "\n", $text);
    // Handle markdown hard breaks (trailing backslash before newline)
    $text = preg_replace('/\\\\\n/', "\n", $text);
    // Remove escaped characters (common from editor)
    $text = preg_replace('/\\\\([~=\[\]$])/', '$1', $text);

    // Extract and protect code blocks BEFORE any other processing
    $codeBlocks = [];
    $text = preg_replace_callback('/```(\w*)\n(.*?)```/s', function ($matches) use (&$codeBlocks) {
        $id = '{{CODE_BLOCK_' . count($codeBlocks) . '}}';
        $lang = $matches[1] ?: 'text';
        $code = htmlspecialchars($matches[2], ENT_QUOTES, 'UTF-8');
        $codeBlocks[$id] = '<pre class="code-block reveal-on-scroll"><code class="language-' . $lang . '">' . $code . '</code></pre>';
        return $id;
    }, $text);

    // Extract and protect inline code
    $inlineCode = [];
    $text = preg_replace_callback('/`([^`]+)`/', function ($matches) use (&$inlineCode) {
        $id = '{{INLINE_CODE_' . count($inlineCode) . '}}';
        $inlineCode[$id] = '<code class="inline-code">' . htmlspecialchars($matches[1], ENT_QUOTES, 'UTF-8') . '</code>';
        return $id;
    }, $text);

    // Convert horizontal rules BEFORE escaping (---, ***, ___ with optional spaces)
    $text = preg_replace('/^[-*_]{3,}\s*$/m', '{{HR}}', $text);

    // Escape HTML
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

    // Restore placeholders
    $text = str_replace('{{HR}}', '<hr class="poem-divider reveal-on-scroll">', $text);
    foreach ($codeBlocks as $id => $html) {
        $text = str_replace(htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8'), $html, $text);
    }
    foreach ($inlineCode as $id => $html) {
        $text = str_replace(htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8'), $html, $text);
    }

    // Handle headings (H1-H6) - order matters, most specific first
    $text = preg_replace('/^######\s+(.+)$/m', '<h6 class="reveal-on-scroll">$1</h6>', $text);
    $text = preg_replace('/^#####\s+(.+)$/m', '<h5 class="reveal-on-scroll">$1</h5>', $text);
    $text = preg_replace('/^####\s+(.+)$/m', '<h4 class="reveal-on-scroll">$1</h4>', $text);
    $text = preg_replace('/^###\s+(.+)$/m', '<h3 class="reveal-on-scroll">$1</h3>', $text);
    $text = preg_replace('/^##\s+(.+)$/m', '<h2 class="reveal-on-scroll">$1</h2>', $text);
    $text = preg_replace('/^#\s+(.+)$/m', '<h1 class="poem-title reveal-on-scroll">$1</h1>', $text);

    // Handle blockquotes
    $text = preg_replace_callback('/^(&gt;.+(?:\n&gt;.*)*)$/m', function ($matches) {
        $quote = preg_replace('/^&gt;\s?/m', '', $matches[1]);
        return '<blockquote class="reveal-on-scroll">' . $quote . '</blockquote>';
    }, $text);

    // Handle links [text](url)
    $text = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2" target="_blank" rel="noopener">$1</a>', $text);

    // Handle bold and italic (order matters: bold+italic first, then bold, then italic)
    $text = preg_replace('/\*\*\*(.+?)\*\*\*/', '<strong><em>$1</em></strong>', $text);
    $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);
    $text = preg_replace('/__(.+?)__/', '<strong>$1</strong>', $text);
    $text = preg_replace('/\*([^*\n]+?)\*/', '<em>$1</em>', $text);
    $text = preg_replace('/_([^_\n]+?)_/', '<em>$1</em>', $text);

    // Handle strikethrough ~~text~~
    $text = preg_replace('/~~(.+?)~~/', '<del>$1</del>', $text);

    // Handle Tables (GFM style)
    $text = preg_replace_callback('/((?:\|.+?\|\n)+)/s', function ($matches) {
        $rows = array_filter(explode("\n", trim($matches[1])));
        if (count($rows) < 2)
            return $matches[1];

        $html = '<div class="poem-table-wrapper"><table class="reveal-on-scroll">';
        foreach ($rows as $index => $row) {
            if (preg_match('/^\|[\s:-]+\|[\s:-]+/', trim($row)))
                continue;
            $cols = explode('|', trim($row, '|'));
            $html .= '<tr>';
            foreach ($cols as $col) {
                $tag = ($index === 0) ? 'th' : 'td';
                $html .= "<$tag>" . trim($col) . "</$tag>";
            }
            $html .= '</tr>';
        }
        $html .= '</table></div>';
        return $html;
    }, $text);

    // Handle unordered lists (improved nesting)
    $text = preg_replace_callback('/^([\*\-]\s+.*(?:\n(?:[\s\*\-]|\s{2,}).*)*)$/m', function ($matches) {
        $lines = explode("\n", $matches[1]);
        $html = '<ul class="poem-list reveal-on-scroll">';
        $currentLevel = 0;
        foreach ($lines as $line) {
            if (!preg_match('/^(\s*)([\*\-])\s+(.*)$/', $line, $m))
                continue;
            $indent = strlen($m[1]);
            $level = (int) floor($indent / 2);
            if ($level > $currentLevel) {
                $html .= '<ul>';
                $currentLevel = $level;
            } elseif ($level < $currentLevel) {
                $html .= str_repeat('</ul>', (int) ($currentLevel - $level));
                $currentLevel = $level;
            }
            $html .= '<li>' . trim($m[3]) . '</li>';
        }
        $html .= str_repeat('</ul>', (int) $currentLevel) . '</ul>';
        return $html;
    }, $text);

    // Handle ordered lists
    $text = preg_replace_callback('/^(\d+\.\s+.*(?:\n(?:\s*\d+\.|\s{2,}).*)*)$/m', function ($matches) {
        $lines = explode("\n", $matches[1]);
        $html = '<ol class="poem-list reveal-on-scroll">';
        foreach ($lines as $line) {
            $content = preg_replace('/^\s*\d+\.\s+/', '', trim($line));
            if (!empty($content))
                $html .= '<li>' . $content . '</li>';
        }
        $html .= '</ol>';
        return $html;
    }, $text);

    // Split into parts
    $parts = preg_split('/(<h[1-6][^>]*>.*?<\/h[1-6]>|<hr[^>]*>|<ul[^>]*>.*?<\/ul>|<ol[^>]*>.*?<\/ol>|<blockquote[^>]*>.*?<\/blockquote>|<pre[^>]*>.*?<\/pre>|<div class="poem-table-wrapper">.*?<\/div>)/s', $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    $html = '';
    foreach ($parts as $part) {
        $part = trim($part);
        if (empty($part))
            continue;
        if (preg_match('/^<(h[1-6]|hr|ul|ol|blockquote|pre|div)/', $part)) {
            $html .= $part . "\n"; // Preserve block
        } else {
            $stanzas = preg_split('/\n\s*\n/', $part);
            foreach ($stanzas as $stanza) {
                $stanza = trim($stanza);
                if (!empty($stanza))
                    $html .= '<div class="stanza reveal-on-scroll">' . nl2br($stanza) . '</div>';
            }
        }
    }
    return $html;
}

function handleUpload(bool $isAdmin, array $lang): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' || empty($_FILES))
        return;
    if (!$isAdmin) {
        $_SESSION['message'] = 'Unauthorized access.';
        $_SESSION['msg_type'] = 'error';
        return;
    }
    $file = $_FILES['poem'] ?? null;
    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['message'] = $lang['msg_upload_err'];
        $_SESSION['msg_type'] = 'error';
        return;
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['md', 'txt'], true)) {
        $_SESSION['message'] = $lang['msg_ext_err'];
        $_SESSION['msg_type'] = 'error';
        return;
    }
    if ($file['size'] > MAX_FILE_SIZE) {
        $_SESSION['message'] = $lang['msg_size_err'];
        $_SESSION['msg_type'] = 'error';
        return;
    }
    $filename = preg_replace('/[^\p{L}\p{N}\.\-_]/u', '_', $file['name']);
    $filename = preg_replace('/_+/', '_', $filename);
    $filename = trim($filename, '_');
    $destination = POEMS_DIR . $filename;
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        $_SESSION['message'] = $lang['msg_success'];
        $_SESSION['msg_type'] = 'success';
    } else {
        $_SESSION['message'] = $lang['msg_move_err'];
        $_SESSION['msg_type'] = 'error';
    }
    header('Location: ' . $_SERVER['PHP_SELF'] . '?view=list');
    exit;
}

function handleDelete(bool $isAdmin, array $lang): void
{
    if (!$isAdmin || !isset($_GET['delete']))
        return;
    $file = basename($_GET['delete']);
    $filepath = POEMS_DIR . $file;
    if (file_exists($filepath)) {
        if (unlink($filepath)) {
            $_SESSION['message'] = $lang['msg_del_success'];
            $_SESSION['msg_type'] = 'success';
        } else {
            $_SESSION['message'] = $lang['msg_del_err'];
            $_SESSION['msg_type'] = 'error';
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF'] . '?view=list');
    exit;
}

/**
 * --- Main Logic ---
 */

handleUpload($isAdmin, $lang);
handleDelete($isAdmin, $lang);

$view = $_GET['view'] ?? 'home';
if (isset($_GET['admin']) && !$isAdmin)
    $view = 'admin_login';
$poemHtml = '';
$poems = [];

// Content Loading Logic
if (isset($_GET['poem'])) {
    if ($view !== 'admin_login') {
        $view = 'poem';
    }
    $file = basename($_GET['poem']);
    $file = getLocalizedFilename($file, $currentLang);
    $filepath = POEMS_DIR . $file;
    if (file_exists($filepath)) {
        $poemHtml = parsePoetryMarkdown(file_get_contents($filepath));
    }
} elseif ($view === 'home') {
    // Generate landing page dynamically using localized placeholders
    $poemHtml = '<h1 class="poet-name-header reveal-on-scroll">' . htmlspecialchars($displayPoetName) . '</h1>';
    $poemHtml .= '<div class="stanza reveal-on-scroll"><h2 class="reveal-on-scroll"><em>' . htmlspecialchars($displaySiteTitle) . '</em></h2></div>';
    $poemHtml .= '<div class="stanza reveal-on-scroll"><h3 class="reveal-on-scroll">' . htmlspecialchars($lang['poems_label']) . '</h3></div>';
}

$breadcrumbPoemTitle = '';
if ($view === 'poem' && isset($file)) {
    $breadcrumbPoemTitle = getPoemTitle(POEMS_DIR . $file);
}

// List fetching logic
if ($view === 'list') {
    $files = scandir(POEMS_DIR);
    $groups = [];
    foreach ($files as $f) {
        if ($f === '.' || $f === '..' || is_dir(POEMS_DIR . $f))
            continue;

        $info = pathinfo($f);
        $base = $info['filename'];
        $cleanBase = preg_replace('/_(en|el)$/', '', $base);

        $priority = 0;
        if (preg_match('/_' . $currentLang . '$/', $base)) {
            $priority = 2;
        } elseif (!preg_match('/_(en|el)$/', $base)) {
            $priority = 1;
        }

        if (!isset($groups[$cleanBase]) || $priority > $groups[$cleanBase]['priority']) {
            $groups[$cleanBase] = ['filename' => $f, 'priority' => $priority];
        }
    }

    $poems = array_column($groups, 'filename');

    if ($sortOrder === 'latest') {
        usort($poems, function ($a, $b) {
            return filemtime(POEMS_DIR . $b) <=> filemtime(POEMS_DIR . $a);
        });
    } else {
        if (class_exists('Collator')) {
            $collator = new Collator($currentLang === 'el' ? 'el_GR' : 'en_US');
            usort($poems, function ($a, $b) use ($collator) {
                $titleA = getPoemTitle(POEMS_DIR . $a) ?: $a;
                $titleB = getPoemTitle(POEMS_DIR . $b) ?: $b;
                return $collator->compare($titleA, $titleB);
            });
        } else {
            usort($poems, 'strcmp');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $currentLang ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $displaySiteTitle ?></title>
    <link rel="icon" type="image/png" href="favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;0,600;1,400&subset=greek&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --bg-color: #e4e2d7;
            --text-color: #2c2c2c;
            --accent-color: #8c3b3b;
            --border-color: #8c3b3b;
            --font-main: 'EB Garamond', serif;
        }

        [data-theme="dark"] {
            --bg-color: #1a1a1a;
            --text-color: #e0e0e0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: var(--font-main);
            margin: 0;
            line-height: 1.7;
            font-size: 24px;
            -webkit-font-smoothing: antialiased;
            transition: background-color 0.3s, color 0.3s;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 3rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        header {
            text-align: center;
            margin-bottom: 4rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--border-color);
        }

        header.no-border {
            border-bottom: none;
        }

        h1.site-title {
            font-weight: 400;
            font-style: italic;
            letter-spacing: 0.05em;
            margin: 0;
            font-size: 3rem;
        }

        h1.site-title a {
            text-decoration: none;
            color: var(--accent-color);
        }

        .theme-toggle,
        .std-link {
            color: #666;
            text-decoration: none;
            font-size: 1.2rem;
            transition: color 0.3s;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            font-family: inherit;
        }

        [data-theme="dark"] .theme-toggle,
        [data-theme="dark"] .std-link {
            color: #aaa;
        }

        .theme-toggle:hover,
        .std-link:hover {
            color: var(--accent-color);
        }

        /* Fixed Theme Toggle */
        .theme-toggle-fixed {
            position: fixed;
            top: 20px;
            right: 20px;
            background: transparent;
            color: var(--accent-color);
            border: none;
            padding: 8px;
            border-radius: 50%;
            cursor: pointer;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s;
        }

        .theme-toggle-fixed:hover {
            transform: scale(1.1);
        }

        .theme-toggle-fixed svg {
            width: 20px;
            height: 20px;
            stroke: var(--accent-color);
        }

        .theme-toggle-fixed .icon-sun,
        .theme-toggle-fixed .icon-moon {
            display: none;
        }

        .theme-toggle-fixed .icon-moon {
            display: block;
        }

        [data-theme="dark"] .theme-toggle-fixed .icon-moon {
            display: none;
        }

        [data-theme="dark"] .theme-toggle-fixed .icon-sun {
            display: block;
        }

        .poem-list {
            list-style: none;
            padding: 0;
            text-align: center;
        }

        .poem-list li {
            margin: 1.8rem 0;
        }

        .poem-list a {
            text-decoration: none;
            color: var(--text-color);
            font-size: 1.8rem;
            border-bottom: 1px solid transparent;
            transition: all 0.3s ease;
        }

        .poem-list a:hover {
            border-bottom-color: var(--accent-color);
            color: var(--accent-color);
        }

        .poem-content {
            margin: 3rem auto;
            max-width: 744px;
        }

        :is(.view-poem, .view-list, .view-home).align-left :is(.stanza, .poem-title, .poet-name-header, .poem-content h2, .poem-content h3, .poem-list) {
            text-align: left;
        }

        :is(.view-poem, .view-list, .view-home).align-center :is(.stanza, .poem-title, .poet-name-header, .poem-content h2, .poem-content h3, .poem-list) {
            text-align: center;
        }

        :is(.view-poem, .view-list, .view-home).align-right :is(.stanza, .poem-title, .poet-name-header, .poem-content h2, .poem-content h3, .poem-list) {
            text-align: right;
        }

        .poem-title {
            text-align: center;
            font-weight: 400;
            margin-bottom: 3.5rem;
            font-size: 2.5rem;
            color: var(--accent-color);
        }

        .poet-name-header {
            text-align: center;
            font-weight: 400;
            margin-bottom: 1rem;
            font-size: 2.5rem;
            color: var(--text-color);
        }

        .poem-content h2 {
            text-align: center;
            font-weight: 400;
            font-size: 2.6rem;
            margin: 3rem 0 2rem;
            color: var(--accent-color);
        }

        .poem-content h3 {
            text-align: center;
            font-weight: 400;
            font-size: 1.5rem;
            margin: 2rem 0;
        }

        .stanza {
            margin-bottom: 2rem;
            white-space: pre-wrap;
            text-align: center;
            padding: 0 1rem;
            line-height: 1.2;
        }

        .stanza a, .poem-content a {
            color: var(--accent-color);
            text-decoration: underline;
        }

        footer {
            text-align: center;
            margin-top: auto;
            color: #aaa;
            font-size: 0.8rem;
            padding-bottom: 1rem;
        }

        .admin-link {
            color: var(--accent-color);
            text-decoration: underline;
            font-size: 0.9rem;
            margin-top: 5px;
            display: inline-block;
        }

        .action-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 2rem;
            padding: 0.8rem 2rem;
            color: var(--text-color);
            text-decoration: none;
            font-size: 1.5rem;
            transition: all 0.3s;
        }

        .action-button svg {
            width: 24px;
            height: 24px;
            transition: transform 0.3s;
        }

        .action-button:hover {
            color: var(--accent-color);
        }

        .action-button:hover svg {
            transform: translateX(5px);
            color: var(--accent-color);
        }

        .reveal-on-scroll {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        .reveal-on-scroll.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .view-home .container {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .view-home main {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        main {
            margin-top: 8rem;
        }

        .view-home .poem-content {
            margin-top: 0;
        }

        .view-home footer {
            margin-top: 2rem;
        }

        input:focus {
            outline: 2px solid var(--accent-color);
            outline-offset: -2px;
        }

        .admin-toolbar {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--accent-color);
            color: #fff;
            padding: 12px 24px;
            border-radius: 50px;
            display: flex;
            gap: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            backdrop-filter: blur(5px);
        }

        .admin-toolbar a,
        .admin-toolbar button {
            color: #fff;
            text-decoration: none;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: opacity 0.3s;
            background: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        .admin-toolbar a:hover,
        .admin-toolbar button:hover {
            opacity: 0.8;
        }

        .dropdown {
            position: relative;
            display: flex;
            align-items: center;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: var(--accent-color);
            min-width: 320px;
            box-shadow: 0 -8px 24px rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            z-index: 1002;
            margin-bottom: 28px;
            padding: 1.8rem;
            animation: slideUpFade 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Language dropdown - match player style */
        #lang-dropdown .dropdown-content {
            min-width: 140px;
            padding: 8px;
        }

        #lang-dropdown .dropdown-content button {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 15px;
            background: none;
            border: none;
            color: white;
            font-family: inherit;
            font-size: 0.9rem;
            text-align: left;
            cursor: pointer;
            border-radius: 8px;
            transition: background 0.2s;
        }

        #lang-dropdown .dropdown-content button:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        #lang-dropdown .dropdown-content button.active {
            background: rgba(255, 255, 255, 0.2);
            font-weight: 600;
        }

        .dropdown.active .dropdown-content {
            display: block !important;
        }

        @keyframes slideUpFade {
            from {
                opacity: 0;
                transform: translate(-50%, 10px);
            }

            to {
                opacity: 1;
                transform: translate(-50%, 0);
            }
        }

        .dropdown-content::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 8px solid transparent;
            border-top-color: var(--accent-color);
        }

        .dropdown-content button {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 15px;
            background: none;
            border: none;
            color: white;
            font-family: inherit;
            font-size: 0.9rem;
            text-align: left;
            cursor: pointer;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .dropdown-content button:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .dropdown-content button.active {
            background: rgba(255, 255, 255, 0.2);
            font-weight: 600;
        }

        .dropdown-content h3 {
            color: white;
            font-weight: 400;
            font-variant: small-caps;
            margin-top: 0;
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 1.2rem;
        }

        .dropdown-content label {
            display: block;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
            text-align: left;
        }

        .dropdown-content input[type="text"],
        .dropdown-content input[type="password"],
        .dropdown-content textarea,
        .dropdown-content select {
            width: 100%;
            padding: 0.7rem;
            margin-bottom: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-family: inherit;
            font-size: 1rem;
            border-radius: 4px;
        }

        .dropdown-content select option {
            color: #333;
        }

        .dropdown-content textarea {
            resize: vertical;
            min-height: 80px;
        }

        .dropdown-content input::placeholder,
        .dropdown-content textarea::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .dropdown-content button[type="submit"] {
            width: 100%;
            padding: 0.7rem;
            background: white;
            color: var(--accent-color);
            border: none;
            font-family: inherit;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 4px;
            transition: opacity 0.2s;
            font-weight: 600;
        }

        .dropdown-content button[type="submit"]:hover {
            opacity: 0.9;
        }

        .file-input-wrapper {
            position: relative;
            margin-bottom: 1rem;
        }

        .file-input-wrapper input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-input-label {
            display: block;
            padding: 0.7rem;
            border: 1px dashed rgba(255, 255, 255, 0.4);
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 0.9rem;
            text-align: center;
            border-radius: 4px;
        }

        #upload-form {
            /* Handled by .dropdown-content */
        }

        .breadcrumbs {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 100;
            width: max-content;
            max-width: 90%;
            justify-content: center;
            white-space: nowrap;
            height: 36px;
        }

        .breadcrumbs a {
            text-decoration: none;
            color: var(--text-color);
            opacity: 0.6;
            transition: all 0.3s;
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            flex-shrink: 0;
        }

        .breadcrumbs a:hover {
            opacity: 1;
            color: var(--accent-color);
        }

        .breadcrumb-separator {
            color: var(--accent-color);
            font-weight: bold;
            font-family: serif;
            flex-shrink: 0;
        }

        .breadcrumb-current {
            color: var(--accent-color);
            font-weight: 600;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            flex-shrink: 0;
        }

        .breadcrumbs svg {
            width: 14px;
            height: 14px;
            stroke-width: 2px;
            margin-right: 4px;
            vertical-align: -2px;
            flex-shrink: 0;
        }

        @media (max-width: 600px) {
            .breadcrumbs {
                top: 15px;
                left: 0;
                width: 100%;
                max-width: 100%;
                transform: none;
                padding: 0 7rem 0 1rem; /* Extra right padding to clear theme toggle */
                overflow-x: auto;
                justify-content: flex-start;
                scrollbar-width: none;
                -webkit-overflow-scrolling: touch;
                /* Mask effect to fade text before toggle */
                mask-image: linear-gradient(to right, black calc(100% - 9rem), transparent calc(100% - 4rem));
                -webkit-mask-image: linear-gradient(to right, black calc(100% - 9rem), transparent calc(100% - 4rem));
                height: 36px;
            }

            .theme-toggle-fixed {
                top: 15px;
            }

            .breadcrumbs::-webkit-scrollbar {
                display: none;
            }

            .breadcrumbs a,
            .breadcrumb-current {
                max-width: none;
            }

            .container {
                padding: 1rem;
            }

            h1.site-title {
                font-size: 2rem;
            }

            .poem-list a {
                font-size: 1.5rem;
            }

            .poem-title {
                line-height: 1.2;
            }

            .admin-toolbar {
                left: 10px;
                right: 10px;
                bottom: 10px;
                height: 60px;
                padding: 0;
                border-radius: 30px;
                display: flex;
                align-items: center;
                gap: 0;
                /* Reset desktop gap */
            }

            .admin-toolbar>a,
            .admin-toolbar>.dropdown {
                flex: 1;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                position: static;
            }

            .admin-toolbar a {
                gap: 0;
                font-size: 0;
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .admin-toolbar a span:not(.icon-wrapper) {
                display: none;
            }

            .admin-toolbar .icon-wrapper {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .admin-toolbar a svg {
                width: 26px !important;
                height: 26px !important;
                stroke-width: 1.5 !important;
            }

            .dropdown-content {
                position: fixed;
                bottom: 80px;
                /* Positioned above the 60px toolbar + 10px gap + 10px padding */
                left: 50%;
                top: auto;
                transform: translateX(-50%) !important;
                width: 90%;
                max-width: 320px;
                margin: 0;
                box-shadow: 0 0 40px rgba(0, 0, 0, 0.4);
                z-index: 1005;
            }

            .dropdown-content::after {
                display: none;
            }

            @keyframes slideUpFade {
                from {
                    opacity: 0;
                    transform: translate(-50%, 10px);
                }

                to {
                    opacity: 1;
                    transform: translate(-50%, 0);
                }
            }
        }



        .file-upload-wrapper {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .file-input {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }

        .file-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 80px;
            padding: 12px 24px;
            border: 1px dashed var(--border-color);
            background: rgba(140, 59, 59, 0.05);
            color: var(--text-color);
            cursor: pointer;
            text-align: center;
            transition: all 0.3s;
            font-size: 0.9rem;
            border-radius: 50px;
            line-height: 1;
        }

        .file-label:hover {
            background: rgba(140, 59, 59, 0.1);
            border-style: solid;
        }

        .file-name-display {
            display: block;
            font-size: 0.8rem;
            color: #888;
            font-style: italic;
        }

        .file-name-display:empty {
            display: none;
        }

        #upload-form button[type="submit"] {
            background: var(--accent-color);
            color: #fff;
            border: none;
            padding: 0.7rem 2rem;
            cursor: pointer;
            font-family: inherit;
            font-size: 1rem;
            display: block;
            margin: 0 auto;
            transition: opacity 0.3s;
            border-radius: 50px;
        }

        #upload-form button[type="submit"]:hover {
            opacity: 0.9;
        }

        .message {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            padding: 0.5rem 1.2rem;
            border-radius: 20px;
            font-size: 0.85rem;
            z-index: 2000;
            animation: fadeInOut 3s ease-in-out forwards;
        }

        @keyframes fadeInOut {
            0% {
                opacity: 0;
                transform: translateX(-50%) translateY(-10px);
            }

            15% {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }

            85% {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }

            100% {
                opacity: 0;
                transform: translateX(-50%) translateY(-10px);
            }
        }

        .message.success {
            background: rgb(120, 160, 120);
            color: white;
            border: none;
        }

        .message.error {
            background: rgb(180, 100, 100);
            color: white;
            border: none;
        }

        /* Custom Modal Styles */
        .login-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            animation: fadeIn 0.3s ease;
        }

        .login-box {
            background: var(--accent-color);
            padding: 2rem;
            border-radius: 12px;
            width: 90%;
            max-width: 300px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            text-align: center;
            color: white;
            animation: slideIn 0.3s ease;
        }

        .login-box h3 {
            margin-top: 0;
            margin-bottom: 1.5rem;
            font-weight: 400;
            font-size: 1.2rem;
        }

        .login-box form input {
            width: 100%;
            padding: 12px;
            margin-bottom: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 4px;
            background: white;
            color: #2c2c2c;
            font-family: inherit;
        }

        .login-box button {
            width: 100%;
            padding: 12px;
            border-radius: 4px;
            border: none;
            background: white;
            color: var(--accent-color);
            font-family: inherit;
            cursor: pointer;
            transition: opacity 0.3s;
        }

        .login-box button:hover {
            opacity: 0.9;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();

        const i18n = {
            dark: "<?= $lang['theme_dark'] ?>",
            light: "<?= $lang['theme_light'] ?>"
        };

        function toggleUpload(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            const dropdown = document.getElementById('upload-dropdown');
            if (dropdown) dropdown.classList.toggle('active');

            // Close other dropdowns
            const alignDropdown = document.getElementById('align-dropdown');
            if (alignDropdown) alignDropdown.classList.remove('active');
        }

        function updateFileName(input) {
            const display = document.getElementById('file-name-display');
            if (input.files && input.files[0]) {
                display.textContent = input.files[0].name;
            } else {
                display.textContent = '';
            }
        }

        function toggleTheme() {
            const html = document.documentElement;
            const newTheme = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeButton(newTheme);
        }

        function updateThemeButton(theme) {
            const toggle = document.getElementById('theme-toggle');
            if (toggle) {
                toggle.title = theme === 'dark' ? i18n.light : i18n.dark;
            }
        }

        let confirmUrl = '';
        function showConfirm(message, url) {
            confirmUrl = url;
            const msgEl = document.getElementById('confirm-message');
            if (msgEl) msgEl.textContent = message;
            const modal = document.getElementById('confirm-modal');
            if (modal) modal.style.display = 'flex';
        }

        function closeConfirm() {
            const modal = document.getElementById('confirm-modal');
            if (modal) modal.style.display = 'none';
        }

        function setAlignment(align) {
            const body = document.body;

            ['align-left', 'align-center', 'align-right'].forEach(cls => body.classList.remove(cls));
            body.classList.add(`align-${align}`);
            localStorage.setItem('poem_alignment', align);

            // Update UI buttons
            document.querySelectorAll('.dropdown-content button').forEach(btn => {
                const clickAttr = btn.getAttribute('onclick');
                if (clickAttr) {
                    btn.classList.toggle('active', clickAttr.includes(`'${align}'`));
                }
            });

            // Close dropdown after selection
            const dropdown = document.getElementById('align-dropdown');
            if (dropdown) dropdown.classList.remove('active');
        }

        function toggleDropdown(e, id) {
            e.preventDefault();
            e.stopPropagation();

            // Close all other dropdowns first
            document.querySelectorAll('.dropdown').forEach(d => {
                if (d.id !== id) d.classList.remove('active');
            });

            const dropdown = document.getElementById(id);
            if (dropdown) dropdown.classList.toggle('active');
        }

        function setSort(order) {
            localStorage.setItem('sort_order', order);
            applySort(order);
            updateSortButton(order);

            const dropdown = document.getElementById('sort-dropdown');
            if (dropdown) dropdown.classList.remove('active');
        }

        function applySort(order) {
            const list = document.querySelector('.poem-list');
            if (!list) return;
            const items = Array.from(list.querySelectorAll('.poem-item'));
            if (items.length === 0) return;

            items.sort((a, b) => {
                if (order === 'latest') {
                    return parseInt(b.dataset.time) - parseInt(a.dataset.time);
                } else {
                    return a.dataset.title.localeCompare(b.dataset.title, '<?= $currentLang ?>');
                }
            });

            items.forEach(item => list.appendChild(item));
        }

        function updateSortButton(order) {
            const dropdown = document.getElementById('sort-dropdown');
            if (!dropdown) return;

            const btn = dropdown.querySelector('a');
            const labelValue = btn.querySelector('.sort-value');
            const iconContainer = btn.querySelector('.icon-wrapper');

            if (order === 'latest') {
                labelValue.textContent = ": <?= $lang['sort_latest'] ?>";
                // Keep the "Latest" icon for the main button
                iconContainer.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 5h10"></path><path d="M11 9h7"></path><path d="M11 13h4"></path><path d="m3 17 3 3 3-3"></path><path d="M6 18V4"></path></svg>`;
            } else {
                labelValue.textContent = ": <?= $lang['sort_alpha'] ?>";
                // Keep the "A-Z" icon for the main button
                iconContainer.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>`;
            }

            // Update active state in dropdown
            dropdown.querySelectorAll('.dropdown-content button').forEach(b => {
                b.classList.toggle('active', b.getAttribute('onclick').includes(`'${order}'`));
            });
        }

        document.addEventListener("DOMContentLoaded", function () {
            const savedSort = localStorage.getItem('sort_order') || 'alpha';
            applySort(savedSort);
            updateSortButton(savedSort);

            const savedAlign = localStorage.getItem('poem_alignment') || 'center';
            setAlignment(savedAlign);

            updateThemeButton(document.documentElement.getAttribute('data-theme'));

            const themeToggle = document.getElementById('theme-toggle');
            if (themeToggle) {
                themeToggle.addEventListener('click', toggleTheme);
            }

            const confirmBtn = document.getElementById('confirm-btn');
            if (confirmBtn) {
                confirmBtn.addEventListener('click', () => {
                    window.location.href = confirmUrl;
                });
            }

            const confirmModal = document.getElementById('confirm-modal');
            if (confirmModal) {
                confirmModal.addEventListener('click', (e) => {
                    if (e.target.id === 'confirm-modal') closeConfirm();
                });
            }

            // Close dropdowns when clicking outside
            document.addEventListener('click', function (e) {
                if (!e.target.closest('.dropdown')) {
                    document.querySelectorAll('.dropdown').forEach(d => d.classList.remove('active'));
                }
            });

            // Scroll breadcrumbs to end on mobile to show current page
            const breadcrumbs = document.querySelector('.breadcrumbs');
            if (breadcrumbs && window.innerWidth <= 600) {
                breadcrumbs.scrollLeft = breadcrumbs.scrollWidth;
            }

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });
            document.querySelectorAll('.reveal-on-scroll').forEach(el => observer.observe(el));
        });
    </script>
    <?php if ($isAdmin): ?>
    <script src="../../system/editor.js?v=<?= time() ?>" defer></script>
    <?php endif; ?>
</head>

<body class="view-<?= $view ?><?= $isAdmin ? ' admin-mode' : '' ?>">
    <!-- Theme Toggle - Fixed Top Right -->
    <button class="theme-toggle-fixed" id="theme-toggle" title="<?= $lang['theme_dark'] ?>">
        <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
        </svg>
        <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="5"></circle>
            <line x1="12" y1="1" x2="12" y2="3"></line>
            <line x1="12" y1="21" x2="12" y2="23"></line>
            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
            <line x1="1" y1="12" x2="3" y2="12"></line>
            <line x1="21" y1="12" x2="23" y2="12"></line>
            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
        </svg>
    </button>
    <div class="container">
        <div class="breadcrumbs">
            <a href="../../index.php">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h3.5z"></path>
                    <line x1="16" y1="8" x2="2" y2="22"></line>
                    <path d="M11 11l2.5 2.5"></path>
                    <path d="M13 9l2.5 2.5"></path>
                    <path d="M15 7l2.5 2.5"></path>
                </svg>
                <?= $lang['profile'] ?>
            </a>
            <span class="breadcrumb-separator">›</span>
            <?php if ($view === 'home'): ?>
                <span class="breadcrumb-current"><?= $displaySiteTitle ?></span>
            <?php elseif ($view === 'list'): ?>
                <a href="index.php"><?= $displaySiteTitle ?></a>
                <span class="breadcrumb-separator">›</span>
                <span class="breadcrumb-current"><?= $lang['contents'] ?></span>
            <?php else: ?>
                <a href="index.php"><?= $displaySiteTitle ?></a>
                <span class="breadcrumb-separator">›</span>
                <a href="index.php?view=list"><?= $lang['contents'] ?></a>
                <span class="breadcrumb-separator">›</span>
                <span class="breadcrumb-current"><?= $breadcrumbPoemTitle ?: str_replace(['_', '-'], [' ', ' '], preg_replace('/_(en|el)$/', '', pathinfo($file, PATHINFO_FILENAME))) ?></span>
            <?php endif; ?>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="message <?= htmlspecialchars($_SESSION['msg_type']) ?>">
                <?= htmlspecialchars($_SESSION['message']) ?>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['msg_type']); ?>
        <?php endif; ?>

        <main>
            <?php if ($view === 'admin_login'): ?>
                <div class="login-overlay" onclick="if(event.target === this) window.location='<?= getCurrentBaseUrl() ?>'">
                    <div class="login-box">
                        <h3><?= $lang['management'] ?></h3>
                        <form method="POST">
                            <input type="hidden" name="admin_login" value="1">
                            <input type="password" name="password" placeholder="<?= $lang['pwd_label'] ?>" required
                                autofocus>
                            <button type="submit"><?= $lang['btn_login'] ?></button>
                        </form>
                    </div>
                </div>
            <?php elseif ($view === 'list'): ?>
                <h1 class="poem-title reveal-on-scroll"><?= $displaySiteTitle ?></h1>
                <ul class="poem-list">
                    <?php if (empty($poems)): ?>
                        <li><span style="color: #999; font-style: italic;"><?= $lang['no_poems'] ?></span></li>
                    <?php else: ?>
                        <?php foreach ($poems as $poem):
                            $title = getPoemTitle(POEMS_DIR . $poem);
                            $cleanDisplay = preg_replace('/_(en|el)$/', '', pathinfo($poem, PATHINFO_FILENAME));
                            $displayName = $title ?: str_replace(['_', '-'], [' ', ' '], $cleanDisplay);
                            $mtime = filemtime(POEMS_DIR . $poem);
                            ?>
                            <li class="reveal-on-scroll poem-item" data-title="<?= htmlspecialchars($displayName) ?>"
                                data-time="<?= $mtime ?>">
                                <a href="?view=poem&poem=<?= urlencode($poem) ?>"><?= htmlspecialchars($displayName) ?></a>
                                <?php if ($isAdmin): ?>
                                    <a href="#"
                                        onclick="showConfirm('<?= addslashes($lang['confirm_delete']) ?>', '?delete=<?= urlencode($poem) ?>')"
                                        style="color: #999; font-size: 0.8rem; margin-left: 15px; text-decoration: none;"><?= $lang['delete_inline'] ?></a>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            <?php elseif ($view === 'poem' || $view === 'home'): ?>
                <div class="poem-content">
                    <?= $poemHtml ?>
                </div>
                <div style="text-align: center; margin-top: 3rem;">
                    <?php if ($view === 'poem'): ?>
                        <a href="index.php?view=list" class="std-link">&larr; <?= $lang['back_to_archive'] ?></a>
                    <?php else: ?>
                        <a href="index.php?view=list" class="action-button">
                            <?= $lang['view_poems'] ?>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </main>

        <?php if ($isAdmin): ?>
            <div class="admin-toolbar">
                <div class="dropdown" id="lang-dropdown">
                    <button type="button" onclick="toggleDropdown(event, 'lang-dropdown')">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="2" y1="12" x2="22" y2="12"></line>
                            <path
                                d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
                            </path>
                        </svg>
                        <span><?= $currentLang === 'el' ? 'Ελληνικά' : 'English' ?></span>
                    </button>
                    <div class="dropdown-content">
                        <button onclick="window.location.href='?lang=el'"
                            class="<?= $currentLang === 'el' ? 'active' : '' ?>">Ελληνικά</button>
                        <button onclick="window.location.href='?lang=en'"
                            class="<?= $currentLang === 'en' ? 'active' : '' ?>">English</button>
                    </div>
                </div>

                <div class="dropdown" id="profile-dropdown">
                    <button type="button" onclick="toggleDropdown(event, 'profile-dropdown')">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="8" r="4"></circle>
                            <path d="M20 21a8 8 0 1 0-16 0"></path>
                        </svg>
                        <span><?= $lang['edit_profile'] ?></span>
                    </button>
                    <div class="dropdown-content">
                        <h3><?= $lang['edit_profile'] ?></h3>
                        <form action="../../index.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="update_profile" value="1">
                            <input type="hidden" name="redirect" value="../../index.php">
                            <label><?= $lang['poet_name'] ?></label>
                            <input type="text" name="author_name" value="<?= htmlspecialchars($config['author_name'] ?? '') ?>"
                                required>
                            <label><?= $lang['bio_label'] ?></label>
                            <textarea name="author_bio"><?= htmlspecialchars($config['author_bio'] ?? '') ?></textarea>
                            <label><?= $lang['photo_label'] ?></label>
                            <div class="file-input-wrapper">
                                <div class="file-input-label" id="photo-label"><?= $lang['choose_file'] ?></div>
                                <input type="file" name="author_photo" accept="image/*"
                                    onchange="document.getElementById('photo-label').textContent = this.files[0]?.name || '<?= $lang['choose_file'] ?>'">
                            </div>
                            <label><?= $lang['new_password_label'] ?></label>
                            <input type="password" name="new_password">
                            <label><?= $lang['confirm_password_label'] ?></label>
                            <input type="password" name="confirm_password">
                            <button type="submit"><?= $lang['btn_save'] ?></button>
                        </form>
                    </div>
                </div>

                <?php if ($view === 'list'): ?>
                    <div class="dropdown" id="sort-dropdown">
                        <a href="#" onclick="toggleDropdown(event, 'sort-dropdown')">
                            <span class="icon-wrapper"></span>
                            <span><?= $lang['sort'] ?></span><span class="sort-value"></span>
                        </a>
                        <div class="dropdown-content">
                            <button onclick="setSort('alpha')">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <?= $lang['sort_alpha'] ?>
                            </button>
                            <button onclick="setSort('latest')">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 5h10"></path>
                                    <path d="M11 9h7"></path>
                                    <path d="M11 13h4"></path>
                                    <path d="m3 17 3 3 3-3"></path>
                                    <path d="M6 18V4"></path>
                                </svg>
                                <?= $lang['sort_latest'] ?>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($view === 'list' || $view === 'poem'): ?>
                    <div class="dropdown" id="align-dropdown">
                        <a href="#" onclick="toggleDropdown(event, 'align-dropdown')">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <line x1="17" y1="10" x2="3" y2="10"></line>
                                <line x1="21" y1="6" x2="3" y2="6"></line>
                                <line x1="21" y1="14" x2="3" y2="14"></line>
                                <line x1="17" y1="18" x2="3" y2="18"></line>
                            </svg>
                            <span><?= $lang['align'] ?></span>
                        </a>
                        <div class="dropdown-content">
                            <button onclick="setAlignment('left')">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="17" y1="10" x2="3" y2="10"></line>
                                    <line x1="21" y1="6" x2="3" y2="6"></line>
                                    <line x1="21" y1="14" x2="3" y2="14"></line>
                                    <line x1="17" y1="18" x2="3" y2="18"></line>
                                </svg>
                                <?= $lang['align_left'] ?>
                            </button>
                            <button onclick="setAlignment('center')">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="10" x2="6" y2="10"></line>
                                    <line x1="21" y1="6" x2="3" y2="6"></line>
                                    <line x1="21" y1="14" x2="3" y2="14"></line>
                                    <line x1="18" y1="18" x2="6" y2="18"></line>
                                </svg>
                                <?= $lang['align_center'] ?>
                            </button>
                            <button onclick="setAlignment('right')">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="21" y1="10" x2="7" y2="10"></line>
                                    <line x1="21" y1="6" x2="3" y2="6"></line>
                                    <line x1="21" y1="14" x2="3" y2="14"></line>
                                    <line x1="21" y1="18" x2="7" y2="18"></line>
                                </svg>
                                <?= $lang['align_right'] ?>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="dropdown" id="upload-dropdown">
                    <a href="#" onclick="toggleUpload(event)">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span><?= $lang['add_poem'] ?></span>
                    </a>
                    <div id="upload-form" class="dropdown-content upload-popup">
                        <h3><?= $lang['upload_title'] ?></h3>
                        <form action="" method="post" enctype="multipart/form-data" style="text-align: center;">
                            <div class="file-upload-wrapper">
                                <input type="file" name="poem" id="poem_file" class="file-input" required
                                    onchange="updateFileName(this)">
                                <label for="poem_file" class="file-label">
                                    <span><?= $lang['file_label'] ?></span>
                                    <span id="file-name-display" class="file-name-display"></span>
                                </label>
                            </div>
                            <button type="submit"><?= $lang['btn_upload'] ?></button>
                            <button type="button" onclick="toggleUpload(event)"
                                style="background: none; color: white; border: none; margin-top: 5px; width: auto; cursor: pointer; font-size: 0.9rem; font-family: inherit; display: block; margin: 5px auto 0; text-decoration: underline; opacity: 0.8;"><?= $lang['btn_cancel'] ?></button>
                        </form>
                    </div>
                </div>

                <a href="#" onclick="openEditor()">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20h9"></path>
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                    </svg>
                    <span>Write</span>
                </a>

                <?php if ($view === 'poem' && isset($_GET['poem'])): ?>
                <a href="#" onclick="openEditor('<?= urlencode($_GET['poem']) ?>')">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    <span>Edit</span>
                </a>
                <?php endif; ?>

                <a href="index.php?view=list">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6h18"></path>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    <span><?= $lang['delete_poems'] ?></span>
                </a>
                <?php
                $logoutParams = $_GET;
                $logoutParams['logout'] = '1';
                ?>
                <a href="?<?= http_build_query($logoutParams) ?>">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span><?= $lang['exit'] ?></span>
                </a>
            </div>
        <?php endif; ?>
        <footer>
            &copy; <?= date('Y') ?> <?= $displayPoetName ?>. All rights reserved.
            &bull;
            <a href="https://cozats.github.io/Stanza/" target="_blank" class="admin-link"
                style="text-decoration: none;"><?= $lang['about_link'] ?></a>
            <br>
            <?php
            $adminParams = $_GET;
            $adminParams['admin'] = '1';
            ?>
            <a href="?<?= http_build_query($adminParams) ?>" class="admin-link"><?= $lang['management'] ?></a>
        </footer>
    </div>
    <!-- Custom Confirm Modal -->
    <div id="confirm-modal" class="login-overlay" style="display: none;">
        <div class="login-box">
            <h3 id="confirm-message">Confirm?</h3>
            <div style="display: flex; gap: 1rem;">
                <button type="button" id="confirm-btn"
                    style="background: white; color: var(--accent-color); flex: 1; padding: 0.7rem; border-radius: 4px; border: none; cursor: pointer; font-family: inherit; font-weight: 600;"><?= $lang['delete_collection'] ?></button>
                <button type="button" onclick="closeConfirm()"
                    style="background: transparent; color: white; border: 1px solid white; flex: 1; padding: 0.7rem; border-radius: 4px; cursor: pointer; font-family: inherit;"><?= $lang['btn_cancel'] ?></button>
            </div>
        </div>
    </div>
</body>

</html>
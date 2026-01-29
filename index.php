<?php
declare(strict_types=1);

// Increase upload limits to 64MB
ini_set('upload_max_filesize', '64M');
ini_set('post_max_size', '64M');
ini_set('memory_limit', '128M');

// Set custom session path for environments with restrictive default paths
$sessionPath = __DIR__ . '/sessions';
if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0755, true);
}
session_save_path($sessionPath);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
 |--------------------------------------------------------------------------
 | MASTER LANDING PAGE CONFIGURATION
 |--------------------------------------------------------------------------
 */

// Config file path
define('CONFIG_FILE', __DIR__ . '/config.json');
define('UPLOADS_DIR', __DIR__ . '/uploads/');

// Ensure uploads directory exists
if (!is_dir(UPLOADS_DIR)) {
    mkdir(UPLOADS_DIR, 0755, true);
}

// Default configuration
$defaultConfig = [
    'author_name' => 'Poet Name',
    'author_bio' => 'This will be your about paragraph. You can edit this in the management section.',
    'author_photo' => '',
    'admin_password_hash' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
];

// Load or create config
function loadConfig(): array
{
    global $defaultConfig;
    if (file_exists(CONFIG_FILE)) {
        $config = json_decode(file_get_contents(CONFIG_FILE), true);
        return array_merge($defaultConfig, $config ?? []);
    }
    return $defaultConfig;
}

function saveConfig(array $config): bool
{
    return file_put_contents(CONFIG_FILE, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
}

$config = loadConfig();

// Admin Password (Hash).
define('ADMIN_PASSWORD_HASH', $config['admin_password_hash']);

// Default UI Language
define('UI_LANGUAGE', 'en');

$locales = [
    'el' => [
        'lang_toggle' => 'English',
        'collections_title' => 'Συλλογές',
        'no_collections' => 'Δεν υπάρχουν ακόμη δημοσιευμένες συλλογές.',
        'add_collection' => 'Νέα Συλλογή',
        'author_name_label' => 'Όνομα Συγγραφέα',
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
    ],
    'en' => [
        'lang_toggle' => 'Ελληνικά',
        'collections_title' => 'Collections',
        'no_collections' => 'No published collections yet.',
        'add_collection' => 'New Collection',
        'author_name_label' => 'Author Name',
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
    ]
];

define('COLLECTIONS_DIR', __DIR__ . '/collections/');

// Helper: Get current script URL without query params
function getCurrentBaseUrl(): string
{
    return explode('?', $_SERVER['REQUEST_URI'] ?? '/')[0];
}

// Helper: Recursively delete directory
function deleteDirectory(string $dir): bool
{
    if (!is_dir($dir))
        return false;
    $files = array_diff(scandir($dir), ['.', '..']);
    foreach ($files as $file) {
        $path = $dir . '/' . $file;
        is_dir($path) ? deleteDirectory($path) : unlink($path);
    }
    return rmdir($dir);
}

// Handle Language Switch
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = in_array($_GET['lang'], ['el', 'en']) ? $_GET['lang'] : UI_LANGUAGE;
    header('Location: ' . getCurrentBaseUrl());
    exit;
}
$currentLang = $_SESSION['lang'] ?? UI_LANGUAGE;
$lang = $locales[$currentLang] ?? $locales['en'];

// Handle Admin Login
if (isset($_POST['admin_login'])) {
    $password = $_POST['password'] ?? '';
    if (password_verify($password, ADMIN_PASSWORD_HASH)) {
        $_SESSION['is_admin'] = true;
    } else {
        $_SESSION['message'] = 'Incorrect password.';
        $_SESSION['msg_type'] = 'error';
    }
    header('Location: ' . getCurrentBaseUrl());
    exit;
}

// Handle Logout
if (isset($_GET['logout'])) {
    unset($_SESSION['is_admin']);
    header('Location: ' . getCurrentBaseUrl());
    exit;
}

$isAdmin = !empty($_SESSION['is_admin']);
$showAdminLogin = isset($_GET['admin']) && !$isAdmin;

// Handle Profile Update (with photo upload)
if (isset($_POST['update_profile']) && $isAdmin) {
    $redirect = $_POST['redirect'] ?? getCurrentBaseUrl();
    $config['author_name'] = trim($_POST['author_name'] ?? $config['author_name']);
    $config['author_bio'] = trim($_POST['author_bio'] ?? $config['author_bio']);

    // Handle photo upload
    if (isset($_FILES['author_photo']) && $_FILES['author_photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['author_photo']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['author_photo']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $filename = 'author_photo_' . time() . '.' . $ext;
                $destination = UPLOADS_DIR . $filename;
                if (move_uploaded_file($_FILES['author_photo']['tmp_name'], $destination)) {
                    // Delete old photo if exists
                    if (!empty($config['author_photo']) && file_exists(__DIR__ . '/' . $config['author_photo'])) {
                        @unlink(__DIR__ . '/' . $config['author_photo']);
                    }
                    $config['author_photo'] = 'uploads/' . $filename;
                } else {
                    $_SESSION['message'] = 'Upload failed: Could not move file';
                    $_SESSION['msg_type'] = 'error';
                    header('Location: ' . $redirect);
                    exit;
                }
            } else {
                $_SESSION['message'] = 'Upload failed: Invalid file type (' . $ext . ')';
                $_SESSION['msg_type'] = 'error';
                header('Location: ' . $redirect);
                exit;
            }
        } else {
            $errorMessages = [
                UPLOAD_ERR_INI_SIZE => 'File too large (php.ini limit)',
                UPLOAD_ERR_FORM_SIZE => 'File too large (form limit)',
                UPLOAD_ERR_PARTIAL => 'Partial upload',
                UPLOAD_ERR_NO_TMP_DIR => 'No temp directory',
                UPLOAD_ERR_CANT_WRITE => 'Cannot write to disk',
                UPLOAD_ERR_EXTENSION => 'Blocked by extension',
            ];
            $errMsg = $errorMessages[$_FILES['author_photo']['error']] ?? 'Unknown error';
            $_SESSION['message'] = 'Upload failed: ' . $errMsg;
            $_SESSION['msg_type'] = 'error';
            header('Location: ' . $redirect);
            exit;
        }
    }
    // Handle password update
    $newPwd = $_POST['new_password'] ?? '';
    $confirmPwd = $_POST['confirm_password'] ?? '';

    if (!empty($newPwd)) {
        if ($newPwd === $confirmPwd) {
            $config['admin_password_hash'] = password_hash($newPwd, PASSWORD_DEFAULT);
        } else {
            $_SESSION['message'] = $lang['msg_pwd_mismatch'];
            $_SESSION['msg_type'] = 'error';
            header('Location: ' . $redirect);
            exit;
        }
    }

    if (saveConfig($config)) {
        $_SESSION['message'] = $lang['msg_saved'];
        $_SESSION['msg_type'] = 'success';
    } else {
        $_SESSION['message'] = $lang['msg_error'];
        $_SESSION['msg_type'] = 'error';
    }
    header('Location: ' . $redirect);
    exit;
}

// Handle Collection Update
if (isset($_POST['update_collection']) && $isAdmin) {
    $slug = basename($_POST['collection_slug'] ?? '');
    $newAuthorName = trim($config['author_name']);
    $newTitle = trim($_POST['collection_title'] ?? '');

    $collectionFile = COLLECTIONS_DIR . $slug . '/index.php';

    if (file_exists($collectionFile) && !empty($newTitle)) {
        $content = file_get_contents($collectionFile);

        $content = preg_replace(
            "/define\('POET_NAME',\s*'[^']*'\);/",
            "define('POET_NAME', '" . addslashes($newAuthorName) . "');",
            $content
        );
        $content = preg_replace(
            "/define\('SITE_TITLE',\s*'[^']*'\);/",
            "define('SITE_TITLE', '" . addslashes($newTitle) . "');",
            $content
        );

        if (file_put_contents($collectionFile, $content)) {
            $_SESSION['message'] = $lang['msg_saved'];
            $_SESSION['msg_type'] = 'success';
        } else {
            $_SESSION['message'] = $lang['msg_error'];
            $_SESSION['msg_type'] = 'error';
        }
    }
    header('Location: ' . getCurrentBaseUrl());
    exit;
}

// Handle Collection Deletion
if (isset($_GET['delete_collection']) && $isAdmin) {
    $slug = basename($_GET['delete_collection']);
    $collectionDir = COLLECTIONS_DIR . $slug . '/';

    if (is_dir($collectionDir)) {
        if (deleteDirectory($collectionDir)) {
            $_SESSION['message'] = $lang['msg_deleted'];
            $_SESSION['msg_type'] = 'success';
        } else {
            $_SESSION['message'] = $lang['msg_del_error'];
            $_SESSION['msg_type'] = 'error';
        }
    } else {
        $_SESSION['message'] = $lang['msg_del_error'];
        $_SESSION['msg_type'] = 'error';
    }
    header('Location: ' . getCurrentBaseUrl());
    exit;
}

// Handle Collection Creation
if (isset($_POST['create_collection']) && $isAdmin) {
    $authorName = trim($config['author_name']);
    $collectionTitle = trim($_POST['collection_title'] ?? '');

    if (!empty($collectionTitle)) {
        $slug = preg_replace('/[^a-z0-9\-_]/i', '_', strtolower($collectionTitle));
        $slug = preg_replace('/_+/', '_', $slug);
        $slug = trim($slug, '_');
        if (empty($slug))
            $slug = 'collection_' . time();

        $newDir = COLLECTIONS_DIR . $slug . '/';

        if (!is_dir($newDir)) {
            mkdir($newDir, 0755, true);
            mkdir($newDir . 'poems/', 0755, true);

            $templatePath = __DIR__ . '/system/template.php';
            if (file_exists($templatePath)) {
                $templateContent = file_get_contents($templatePath);

                $templateContent = str_replace(
                    ["define('POET_NAME', 'Poet Name');", "define('SITE_TITLE', 'Collection Title');"],
                    ["define('POET_NAME', '" . addslashes($authorName) . "');", "define('SITE_TITLE', '" . addslashes($collectionTitle) . "');"],
                    $templateContent
                );

                file_put_contents($newDir . 'index.php', $templateContent);

                $_SESSION['message'] = $lang['msg_created'];
                $_SESSION['msg_type'] = 'success';
            } else {
                $_SESSION['message'] = $lang['msg_error'] . ' (Template not found)';
                $_SESSION['msg_type'] = 'error';
            }
        } else {
            $_SESSION['message'] = $lang['msg_error'] . ' (Directory exists)';
        }
    } else {
        $_SESSION['message'] = $lang['msg_error'] . ' (Title empty)';
        $_SESSION['msg_type'] = 'error';
    }
    header('Location: ' . getCurrentBaseUrl());
    exit;
}

// Scan collections directory
function getCollections(): array
{
    $collections = [];
    if (!is_dir(COLLECTIONS_DIR))
        return $collections;

    $dirs = scandir(COLLECTIONS_DIR);
    foreach ($dirs as $dir) {
        if ($dir === '.' || $dir === '..')
            continue;
        $path = COLLECTIONS_DIR . $dir . '/';
        if (!is_dir($path))
            continue;

        $indexFile = $path . 'index.php';
        if (!file_exists($indexFile))
            continue;

        $content = file_get_contents($indexFile);

        preg_match("/define\('POET_NAME',\s*'([^']*)'\);/", $content, $nameMatch);
        preg_match("/define\('SITE_TITLE',\s*'([^']*)'\);/", $content, $titleMatch);

        $collections[] = [
            'slug' => $dir,
            'author' => $nameMatch[1] ?? '',
            'title' => $titleMatch[1] ?? $dir,
            'path' => 'collections/' . $dir . '/'
        ];
    }
    return $collections;
}

$collections = getCollections();
$collectionCount = count($collections);
$message = $_SESSION['message'] ?? null;
$msgType = $_SESSION['msg_type'] ?? 'info';
unset($_SESSION['message'], $_SESSION['msg_type']);
?>
<!DOCTYPE html>
<html lang="<?= $currentLang ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($config['author_name']) ?></title>
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
        }

        header {
            text-align: center;
            margin-bottom: 4rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--border-color);
        }

        nav {
            margin-top: 1.5rem;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 30px;
        }

        nav a,
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

        [data-theme="dark"] nav a,
        [data-theme="dark"] .theme-toggle,
        [data-theme="dark"] .std-link {
            color: #aaa;
        }

        nav a:hover,
        .theme-toggle:hover,
        .std-link:hover {
            color: var(--accent-color);
        }

        .author-section {
            text-align: center;
            margin-bottom: 4rem;
        }

        .author-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1.5rem;
            border: 3px solid var(--border-color);
        }

        .author-photo-placeholder {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent-color) 0%, #5a2a2a 100%);
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
        }

        .author-name {
            font-size: 2.5rem;
            font-weight: 400;
            margin: 0 0 0.5rem;
        }

        .author-bio {
            font-size: 1.2rem;
            font-style: italic;
            opacity: 0.8;
            margin: 0;
        }

        .author-bio a {
            color: var(--accent-color);
            text-decoration: underline;
        }

        .collections-title {
            font-weight: 400;
            font-size: 1.5rem;
            text-align: center;
            margin-bottom: 2rem;
            color: var(--accent-color);
        }

        .collections-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }

        .collections-grid.single-collection {
            grid-template-columns: 1fr;
            max-width: 450px;
            margin: 0 auto;
        }

        @media (max-width: 600px) {
            .collections-grid {
                grid-template-columns: 1fr;
            }
        }

        .collection-card {
            aspect-ratio: 1;
            background: rgba(140, 59, 59, 0.03);
            border: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 2rem;
            text-decoration: none;
            color: var(--text-color);
            transition: all 0.3s ease;
            position: relative;
        }

        .collection-card:hover {
            background: rgba(140, 59, 59, 0.08);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .card-author {
            font-size: clamp(0.9rem, 2.5vw, 1.1rem);
            opacity: 0.7;
            margin-bottom: 0.5rem;
        }

        .card-title {
            font-size: clamp(1.5rem, 5vw, 2.5rem);
            font-style: italic;
            font-weight: 400;
            color: var(--accent-color);
        }

        .delete-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 5px 12px;
            font-size: 0.8rem;
            cursor: pointer;
            border-radius: 4px;
            opacity: 0;
            transition: opacity 0.3s;
            font-family: inherit;
        }

        .collection-card:hover .delete-btn {
            opacity: 1;
        }

        .delete-btn:hover {
            background: #6a2a2a;
        }

        .no-collections-container {
            grid-column: 1 / -1;
            text-align: center;
            padding: 4rem 2rem;
            background: rgba(140, 59, 59, 0.03);
            border-radius: 12px;
            border: 1px dashed var(--accent-color);
            margin: 2rem 0;
        }

        .no-collections {
            font-size: 1.2rem;
            color: var(--text-color);
            opacity: 0.7;
            margin-bottom: 2rem;
        }

        .btn-cta {
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            border-radius: 8px;
            cursor: pointer;
            font-family: inherit;
            transition: transform 0.2s, background 0.2s;
        }

        .btn-cta:hover {
            background: #6a2a2a;
            transform: scale(1.05);
        }

        footer {
            text-align: center;
            margin-top: auto;
            color: #aaa;
            font-size: 0.8rem;
            padding: 2rem 0 1rem;
        }

        footer a {
            color: #888;
            text-decoration: underline;
        }

        footer a:hover {
            color: var(--accent-color);
        }

        .admin-link {
            color: var(--accent-color);
            text-decoration: underline;
            font-size: 0.9rem;
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

        .dropdown.active .dropdown-content {
            display: block;
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
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .dropdown-content button[type="submit"]:hover {
            opacity: 0.9;
        }

        .dropdown-content button:not([type="submit"]) {
            width: 100%;
            padding: 0.6rem 1rem;
            background: transparent;
            color: white;
            border: none;
            font-family: inherit;
            font-size: 0.95rem;
            cursor: pointer;
            text-align: left;
            border-radius: 4px;
        }

        .dropdown-content button:not([type="submit"]):hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .dropdown-content button.active {
            background: rgba(255, 255, 255, 0.2);
            font-weight: 600;
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
            text-align: center;
            border-radius: 4px;
            cursor: pointer;
        }

        .file-input-label:hover {
            background: rgba(255, 255, 255, 0.2);
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

        .login-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }

        .login-box {
            background: var(--accent-color);
            padding: 2rem;
            border-radius: 12px;
            min-width: 300px;
        }

        .login-box h3 {
            color: white;
            margin: 0 0 1.5rem;
            text-align: center;
            font-weight: 400;
        }

        #confirm-message {
            font-size: 1.2rem;
            line-height: 1.4;
        }

        .login-box input {
            width: 100%;
            padding: 0.7rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            background: white;
            color: #333;
            font-family: inherit;
            font-size: 1rem;
            border-radius: 4px;
        }

        .login-box input::placeholder {
            color: #999;
        }

        .login-box input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(140, 59, 59, 0.2);
        }

        .login-box button {
            width: 100%;
            padding: 0.7rem;
            background: white;
            color: var(--accent-color);
            border: none;
            font-family: inherit;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 4px;
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

        /* Theme toggle - fixed top right */
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

        @media (max-width: 600px) {
            .theme-toggle-fixed {
                top: 15px;
            }

            .admin-toolbar {
                left: 10px;
                right: 10px;
                justify-content: center;
                gap: 15px;
                padding: 10px 15px;
            }
        }
    </style>
</head>

<body>
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

        <main>
            <?php if ($message): ?>
                <div class="message <?= $msgType ?>"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <section class="author-section">
                <?php if ($config['author_photo']): ?>
                    <img src="<?= htmlspecialchars($config['author_photo']) ?>"
                        alt="<?= htmlspecialchars($config['author_name']) ?>" class="author-photo">
                <?php else: ?>
                    <div class="author-photo-placeholder">
                        <?= mb_substr($config['author_name'], 0, 1) ?>
                    </div>
                <?php endif; ?>
                <h1 class="author-name"><?= htmlspecialchars($config['author_name']) ?></h1>
                <p class="author-bio"><?= htmlspecialchars($config['author_bio']) ?></p>
            </section>

            <h2 class="collections-title reveal-on-scroll"><?= $lang['collections_title'] ?></h2>

            <div class="collections-grid<?= $collectionCount === 1 ? ' single-collection' : '' ?>">
                <?php if (empty($collections)): ?>
                    <div class="no-collections-container reveal-on-scroll">
                        <p class="no-collections"><?= $lang['no_collections'] ?></p>
                        <?php if ($isAdmin): ?>
                            <button type="button" class="btn-cta" onclick="toggleDropdown('add-dropdown')">+
                                <?= $lang['add_collection'] ?></button>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <?php foreach ($collections as $col): ?>
                        <a href="<?= htmlspecialchars($col['path']) ?>" class="collection-card reveal-on-scroll">
                            <span
                                class="card-author"><?= htmlspecialchars($col['author'] ?: $lang['author_name_label']) ?></span>
                            <span class="card-title"><?= htmlspecialchars($col['title'] ?: $col['slug']) ?></span>
                            <?php if ($isAdmin): ?>
                                <button type="button" class="delete-btn"
                                    onclick="event.preventDefault(); showConfirm('<?= addslashes($lang['confirm_delete']) ?>', '?delete_collection=<?= urlencode($col['slug']) ?>')"><?= $lang['delete_collection'] ?></button>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>

        <footer>
            &copy; <?= date('Y') ?> <?= htmlspecialchars($config['author_name']) ?>
            &bull;
            <a href="https://cozats.github.io/Stanza/" target="_blank"><?= $lang['about_link'] ?></a>
            <br>
            <a href="?admin" class="admin-link"><?= $lang['management'] ?></a>
        </footer>
    </div>

    <?php if ($isAdmin): ?>
        <div class="admin-toolbar">
            <div class="dropdown" id="lang-dropdown">
                <button type="button" onclick="toggleDropdown('lang-dropdown')">
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
                <button type="button" onclick="toggleDropdown('profile-dropdown')"><svg width="14" height="14"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="12" cy="8" r="4"></circle>
                        <path d="M20 21a8 8 0 1 0-16 0"></path>
                    </svg> <?= $lang['edit_profile'] ?></button>
                <div class="dropdown-content">
                    <h3><?= $lang['edit_profile'] ?></h3>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="update_profile" value="1">
                        <input type="hidden" name="redirect" value="index.php">
                        <label><?= $lang['author_name_label'] ?></label>
                        <input type="text" name="author_name" value="<?= htmlspecialchars($config['author_name']) ?>"
                            required>
                        <label><?= $lang['bio_label'] ?></label>
                        <textarea name="author_bio"><?= htmlspecialchars($config['author_bio']) ?></textarea>
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
            <div class="dropdown" id="edit-collection-dropdown">
                <button type="button" onclick="toggleDropdown('edit-collection-dropdown')"><svg width="14" height="14"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    <?= $lang['edit_collection'] ?></button>
                <div class="dropdown-content">
                    <h3><?= $lang['edit_collection'] ?></h3>
                    <form method="POST" id="edit-collection-form">
                        <input type="hidden" name="update_collection" value="1">
                        <label><?= $lang['select_collection'] ?></label>
                        <select name="collection_slug" id="collection-select" onchange="updateCollectionFields()">
                            <?php foreach ($collections as $col): ?>
                                <option value="<?= htmlspecialchars($col['slug']) ?>"
                                    data-title="<?= htmlspecialchars($col['title']) ?>">
                                    <?= htmlspecialchars($col['title'] ?: $col['slug']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <label><?= $lang['collection_title_label'] ?></label>
                        <input type="text" name="collection_title" id="collection-title" required>
                        <button type="submit"><?= $lang['btn_save'] ?></button>
                    </form>
                </div>
            </div>
            <div class="dropdown" id="add-dropdown">
                <button type="button" onclick="toggleDropdown('add-dropdown')">+ <?= $lang['add_collection'] ?></button>
                <div class="dropdown-content">
                    <h3><?= $lang['add_collection'] ?></h3>
                    <form method="POST">
                        <input type="hidden" name="create_collection" value="1">
                        <label><?= $lang['collection_title_label'] ?></label>
                        <input type="text" name="collection_title" required
                            placeholder="<?= $lang['collection_title_label'] ?>">
                        <button type="submit"><?= $lang['btn_create'] ?></button>
                    </form>
                </div>
            </div>
            <a href="?logout">
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

    <?php if ($showAdminLogin): ?>
        <div class="login-overlay" onclick="if(event.target === this) window.location='<?= getCurrentBaseUrl() ?>'">
            <div class="login-box">
                <h3><?= $lang['management'] ?></h3>
                <form method="POST">
                    <input type="hidden" name="admin_login" value="1">
                    <input type="password" name="password" placeholder="<?= $lang['pwd_label'] ?>" required>
                    <button type="submit"><?= $lang['btn_login'] ?></button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- Custom Confirm Modal -->
    <div id="confirm-modal" class="login-overlay" style="display: none;">
        <div class="login-box">
            <h3 id="confirm-message">Confirm?</h3>
            <div style="display: flex; gap: 1rem;">
                <button type="button" id="confirm-btn"
                    style="background: white; color: var(--accent-color); flex: 1; padding: 0.7rem; border-radius: 4px; border: none; cursor: pointer; font-family: inherit;"><?= $lang['delete_collection'] ?></button>
                <button type="button" onclick="closeConfirm()"
                    style="background: transparent; color: white; border: 1px solid white; flex: 1; padding: 0.7rem; border-radius: 4px; cursor: pointer; font-family: inherit;"><?= $lang['btn_cancel'] ?></button>
            </div>
        </div>
    </div>

    <script>
        // Theme Toggle
        const themeToggle = document.getElementById('theme-toggle');
        const savedTheme = localStorage.getItem('theme') || 'light';
        if (savedTheme === 'dark') {
            document.body.setAttribute('data-theme', 'dark');
        }

        themeToggle.addEventListener('click', () => {
            const isDark = document.body.getAttribute('data-theme') === 'dark';
            document.body.setAttribute('data-theme', isDark ? 'light' : 'dark');
            localStorage.setItem('theme', isDark ? 'light' : 'dark');
        });

        // Dropdown Toggle
        function toggleDropdown(id) {
            document.querySelectorAll('.dropdown').forEach(d => {
                if (d.id !== id) d.classList.remove('active');
            });
            document.getElementById(id).classList.toggle('active');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown').forEach(d => d.classList.remove('active'));
            }
        });

        // Update collection fields when selection changes
        function updateCollectionFields() {
            const select = document.getElementById('collection-select');
            const option = select.options[select.selectedIndex];
            document.getElementById('collection-title').value = option.dataset.title || '';
        }
        // Initialize on load
        if (document.getElementById('collection-select')) {
            updateCollectionFields();
        }

        // Reveal on scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.reveal-on-scroll').forEach(el => observer.observe(el));

        // Custom Confirm Logic
        let confirmUrl = '';
        function showConfirm(message, url) {
            confirmUrl = url;
            document.getElementById('confirm-message').textContent = message;
            document.getElementById('confirm-modal').style.display = 'flex';
        }
        function closeConfirm() {
            document.getElementById('confirm-modal').style.display = 'none';
        }
        document.getElementById('confirm-btn').addEventListener('click', () => {
            window.location.href = confirmUrl;
        });
        document.getElementById('confirm-modal').addEventListener('click', (e) => {
            if (e.target.id === 'confirm-modal') closeConfirm();
        });
    </script>
</body>

</html>
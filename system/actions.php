<?php
declare(strict_types=1);

require_once __DIR__ . '/core.php';

$isAdmin = !empty($_SESSION['is_admin']);

// Handle Admin Login
if (isset($_POST['admin_login'])) {
    $password = $_POST['password'] ?? '';
    if (password_verify($password, ADMIN_PASSWORD_HASH)) {
        $_SESSION['is_admin'] = true;
    } else {
        $_SESSION['message'] = 'Incorrect password.';
        $_SESSION['msg_type'] = 'error';
    }
    header('Location: ' . getCurrentBaseUrl() . (isset($_GET['c']) ? '?c=' . urlencode($_GET['c']) : ''));
    exit;
}

// Handle Profile Update
if (isset($_POST['update_profile']) && $isAdmin) {
    $config['author_name'] = trim($_POST['author_name'] ?? $config['author_name']);
    $config['author_bio'] = trim($_POST['author_bio'] ?? $config['author_bio']);

    if (isset($_FILES['author_photo']) && $_FILES['author_photo']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['author_photo']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $filename = 'author_photo_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES['author_photo']['tmp_name'], UPLOADS_DIR . $filename)) {
                if (!empty($config['author_photo']) && file_exists(ROOT_DIR . '/' . $config['author_photo'])) {
                    @unlink(ROOT_DIR . '/' . $config['author_photo']);
                }
                $config['author_photo'] = 'uploads/' . $filename;
            }
        }
    }

    if (!empty($_POST['new_password']) && $_POST['new_password'] === $_POST['confirm_password']) {
        $config['admin_password_hash'] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    }

    if (saveConfig($config)) {
        $_SESSION['message'] = $lang['msg_saved'];
        $_SESSION['msg_type'] = 'success';
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

// Handle Collection Creation
if (isset($_POST['create_collection']) && $isAdmin) {
    $title = trim($_POST['collection_title'] ?? '');
    if ($title) {
        $slug = preg_replace('/[^a-z0-9]/', '_', strtolower($title));
        $slug = preg_replace('/_+/', '_', $slug);
        $slug = trim($slug, '_');
        $dir = COLLECTIONS_DIR . $slug . '/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
            mkdir($dir . 'poems/', 0755, true);
            file_put_contents($dir . 'collection.json', json_encode([
                'title' => $title,
                'author' => $config['author_name']
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            $_SESSION['message'] = $lang['msg_created'];
            $_SESSION['msg_type'] = 'success';
        }
    }
    header('Location: index.php');
    exit;
}

// Handle Collection Update
if (isset($_POST['update_collection']) && $isAdmin) {
    $slug = basename($_POST['collection_slug'] ?? '');
    $newTitle = trim($_POST['collection_title'] ?? '');
    $dir = COLLECTIONS_DIR . $slug . '/';
    $metaFile = $dir . 'collection.json';
    if (is_dir($dir) && $newTitle) {
        $meta = file_exists($metaFile) ? json_decode(file_get_contents($metaFile), true) : [];
        $meta['title'] = $newTitle;
        file_put_contents($metaFile, json_encode($meta, JSON_PRETTY_PRINT));
        $_SESSION['message'] = $lang['msg_saved'];
        $_SESSION['msg_type'] = 'success';
    }
    header('Location: index.php');
    exit;
}

// Handle Collection Deletion
if (isset($_GET['delete_collection']) && $isAdmin) {
    $slug = basename($_GET['delete_collection']);
    $dir = COLLECTIONS_DIR . $slug . '/';
    if (is_dir($dir)) {
        if (deleteDirectory($dir)) {
            $_SESSION['message'] = $lang['msg_deleted'];
            $_SESSION['msg_type'] = 'success';
        }
    }
    header('Location: index.php');
    exit;
}

// Handle Poem Deletion
if (isset($_GET['delete_poem']) && $isAdmin && isset($_GET['c'])) {
    $slug = basename($_GET['c']);
    $poem = basename($_GET['delete_poem']);
    $filepath = COLLECTIONS_DIR . $slug . '/poems/' . $poem;
    if (file_exists($filepath)) {
        if (unlink($filepath)) {
            $_SESSION['message'] = $lang['msg_del_success'];
            $_SESSION['msg_type'] = 'success';
        }
    }
    header('Location: ?c=' . urlencode($slug) . '&v=list');
    exit;
}



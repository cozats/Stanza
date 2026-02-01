<?php
declare(strict_types=1);

// Set custom session path
$sessionPath = dirname(__DIR__) . '/sessions';
if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0755, true);
}
session_save_path($sessionPath);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security: Check if admin is logged in
if (empty($_SESSION['is_admin'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Resolve collection slug
$collection = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $collection = $data['collection'] ?? '';
} else {
    $collection = $_GET['collection'] ?? '';
}

if (empty($collection)) {
    http_response_code(400);
    echo json_encode(['error' => 'Collection not specified']);
    exit;
}

$poemsDir = dirname(__DIR__) . '/collections/' . preg_replace('#[/\\\\]#', '', $collection) . '/poems/';

if (!is_dir($poemsDir)) {
    mkdir($poemsDir, 0755, true);
}

// GET Mode: Fetch raw content
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $file = preg_replace('#[/\\\\]#', '', (string)($_GET['file'] ?? ''));
    if (empty($file)) {
        echo json_encode(['error' => 'File not specified']);
        exit;
    }

    $filepath = $poemsDir . $file;
    if (file_exists($filepath)) {
        header('Content-Type: text/markdown');
        echo file_get_contents($filepath);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'File not found']);
    }
    exit;
}

// POST Mode: Save content
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // $data is already decoded above
    $content = $data['content'] ?? '';
    $filename = preg_replace('#[/\\\\]#', '', (string)($data['filename'] ?? ''));
    $title = $data['title'] ?? '';

    if (empty($content)) {
        http_response_code(400);
        echo json_encode(['error' => 'Content is empty']);
        exit;
    }

    // If no filename, generate from title or timestamp
    if (empty($filename)) {
        if (!empty($title)) {
            $slug = preg_replace('/[^\p{L}\p{N}\-_]/u', '_', mb_strtolower($title));
            $slug = preg_replace('/_+/', '_', $slug);
            $filename = trim($slug, '_') . '.md';
            if ($filename === '.md') $filename = 'poem_' . time() . '.md';
        } else {
            $filename = 'poem_' . time() . '.md';
        }
    }

    $filepath = $poemsDir . $filename;

    if (file_put_contents($filepath, $content)) {
        echo json_encode(['success' => true, 'filename' => $filename]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to write file']);
    }
    exit;
}

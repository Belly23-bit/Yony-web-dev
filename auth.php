<?php
session_start();
header('Content-Type: application/json');
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo json_encode(['error' => 'Username and password required']);
        exit;
    }

    $stmt = $pdo->prepare('SELECT id, password FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        echo json_encode(['success' => true, 'message' => 'Login successful']);
    } else {
        echo json_encode(['error' => 'Invalid credentials']);
    }
}
?>
Documents.php
<?php
session_start();
header('Content-Type: application/json');
require 'db_connect.php';

$upload_dir = 'uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

$allowed_types = [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.ms-excel',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'application/vnd.ms-powerpoint',
    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'image/jpeg',
    'image/png'
];

// Upload file
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    $file = $_FILES['file'];
    if (!in_array($file['type'], $allowed_types)) {
        echo json_encode(['error' => 'Invalid file type']);
        exit;
    }

    $filename = uniqid() . '_' . basename($file['name']);
    $filepath = $upload_dir . $filename;

    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        $stmt = $pdo->prepare('INSERT INTO documents (filename, filepath, filetype, uploaded_by) VALUES (?, ?, ?, ?)');
        $stmt->execute([$file['name'], $filepath, $file['type'], $_SESSION['user_id']]);
        echo json_encode(['success' => true, 'filename' => $file['name'], 'id' => $pdo->lastInsertId()]);
    } else {
        echo json_encode(['error' => 'File upload failed']);
    }
}

// Get all documents
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['download'])) {
    $stmt = $pdo->query('SELECT id, filename, filetype, uploaded_at FROM documents ORDER BY uploaded_at DESC');
    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($documents);
}

// Download file
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['download'])) {
    $id = (int)$_GET['download'];
    $stmt = $pdo->prepare('SELECT filename, filepath, filetype FROM documents WHERE id = ?');
    $stmt->execute([$id]);
    $file = $stmt->fetch();

    if ($file && file_exists($file['filepath'])) {
        header('Content-Type: ' . $file['filetype']);
        header('Content-Disposition: attachment; filename="' . $file['filename'] . '"');
        readfile($file['filepath']);
        exit;
    } else {
        echo json_encode(['error' => 'File not found']);
    }
}
?>

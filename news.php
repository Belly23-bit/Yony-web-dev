<?php
session_start();
header('Content-Type: application/json');
require 'db_connect.php';

// Create news/event
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);
    $title = $data['title'] ?? '';
    $content = $data['content'] ?? '';
    $date = $data['date'] ?? '';

    if (empty($title) || empty($content) || empty($date)) {
        echo json_encode(['error' => 'All fields required']);
        exit;
    }

    $stmt = $pdo->prepare('INSERT INTO news (title, content, event_date, posted_by) VALUES (?, ?, ?, ?)');
    $stmt->execute([$title, $content, $date, $_SESSION['user_id']]);
    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
}

// Get all news/events
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query('SELECT id, title, content, event_date, posted_at FROM news ORDER BY posted_at DESC');
    $news = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($news);
}
?>

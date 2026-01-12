<?php
session_start();
// PERBAIKAN PATH: Mundur 2 langkah ke root -> config
include '../../config/db.php';

header('Content-Type: application/json');

// 1. Cek Login
if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Silakan login terlebih dahulu']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari Session & Post
    $user_id = $_SESSION['user']['id'];
    $username = $_SESSION['user']['username']; // Ambil nama user dari session
    
    $product_id = intval($_POST['product_id']);
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);

    // 2. Validasi
    if ($product_id <= 0 || empty($comment)) {
        echo json_encode(['status' => 'error', 'message' => 'Komentar tidak boleh kosong']);
        exit;
    }

    // 3. Simpan ke tabel 'product_reviews'
    $stmt = $conn->prepare("INSERT INTO product_reviews (user_id, product_id, username, rating, comment, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    
    // Types: i=int, i=int, s=string, i=int, s=string
    $stmt->bind_param("iisis", $user_id, $product_id, $username, $rating, $comment);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $stmt->error]);
    }
}
?>
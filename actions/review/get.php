<?php
// PERBAIKAN PATH: Mundur 2 langkah ke root -> config
include '../../config/db.php';

header('Content-Type: application/json');

$product_id = intval($_GET['id'] ?? 0);

if ($product_id > 0) {
    // Ambil data langsung dari tabel product_reviews
    $stmt = $conn->prepare("SELECT * FROM product_reviews WHERE product_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $reviews = [];
    while($row = $result->fetch_assoc()) {
        $reviews[] = [
            'username' => htmlspecialchars($row['username']), 
            'rating' => (int)$row['rating'],
            'comment' => htmlspecialchars($row['comment']),
            'created_at' => date('d M Y', strtotime($row['created_at']))
        ];
    }
    
    echo json_encode($reviews);
} else {
    echo json_encode([]);
}
?>
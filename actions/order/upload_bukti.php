<?php
session_start();
// INTEGRASI: Mundur 2 langkah
include '../../config/db.php'; 

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = isset($_POST['order_id']) ? mysqli_real_escape_string($conn, $_POST['order_id']) : 0;
    
    if (empty($order_id) || empty($_FILES['proof_image']['name'])) {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
        exit;
    }

    $file = $_FILES['proof_image'];
    
    // INTEGRASI PATH FISIK: Mundur 2 langkah dari actions/order/ ke root, lalu masuk assets
    $target_dir = __DIR__ . '/../../assets/uploads/proofs/';

    // Buat folder jika belum ada
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $file_name = "proof_" . $order_id . "_" . time() . "." . $ext;
    
    // Upload File
    if (move_uploaded_file($file['tmp_name'], $target_dir . $file_name)) {
        // UPDATE Database: Simpan nama file saja (atau relative path assets/...)
        // Disini kita simpan nama file saja biar konsisten sama admin panel yg manggil assets/...
        $update = $conn->query("UPDATE orders SET proof_image = '$file_name', status = 'verifying' WHERE id = '$order_id'");
        
        if($update) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal update database: ' . $conn->error]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal upload file ke folder']);
    }
}
?>
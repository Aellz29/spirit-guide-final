<?php
session_start();
// INTEGRASI: Mundur 2 langkah
include '../../config/db.php'; 

// SECURITY: Pastikan yang akses adalah Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Tendang ke login jika bukan admin
    header("Location: ../../login.php"); 
    exit;
}

// 1. Ambil data dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$to = isset($_GET['to']) ? mysqli_real_escape_string($conn, $_GET['to']) : null;

if ($id && $to) {
    // 2. Update status di database
    $sql = "UPDATE orders SET status = '$to' WHERE id = '$id'";
    
    if ($conn->query($sql)) {
        // 3. INTEGRASI REDIRECT: Balik ke folder admin/orders.php (Mundur 2 langkah, masuk admin)
        header("Location: ../../admin/orders.php?status=updated");
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Data ID atau Status tidak ditemukan!";
}
exit;
?>
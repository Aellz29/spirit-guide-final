<?php
session_start();
// Naik 2 folder ke atas untuk mencari config/db.php
require "../../config/db.php"; 

header('Content-Type: application/json');

// --- FITUR 1: UPLOAD BUKTI ---
if (isset($_GET['action']) && $_GET['action'] == 'upload') {
    $order_id = $_POST['order_id'] ?? 0; // Ini adalah ID angka (Primary Key)
    
    if (isset($_FILES['bukti']) && $_FILES['bukti']['error'] == 0) {
        $ext = pathinfo($_FILES['bukti']['name'], PATHINFO_EXTENSION);
        $name = "proof_" . $order_id . "_" . time() . "." . $ext;
        
        // Buat folder upload jika belum ada (Naik 2 level ke assets)
        $targetDir = "../../assets/uploads/proofs/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        if(move_uploaded_file($_FILES['bukti']['tmp_name'], $targetDir . $name)) {
            // Update DB menggunakan ID angka
            $stmt = $conn->prepare("UPDATE orders SET proof_image = ? WHERE id = ?");
            $stmt->bind_param("si", $name, $order_id);
            $stmt->execute();
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal simpan file']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'File tidak ada']);
    }
    exit;
}

// --- FITUR 2: BUAT PESANAN ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = isset($_SESSION['user']) ? $_SESSION['user']['id'] : null;
    
    // Ambil Data
    $nama = $_POST['nama'] ?? 'Guest';
    $phone = $_POST['whatsapp'] ?? '';
    $addr = $_POST['address'] ?? '';
    
    // Data Lokasi & Ongkir
    $prov = $_POST['full_province'] ?? ''; 
    $city = $_POST['full_city'] ?? '';     
    $ship_cost = (int)($_POST['shipping_cost'] ?? 0);
    
    $payment = $_POST['payment'] ?? 'QRIS';
    $cart = json_decode($_POST['cart_data'], true);
    
    if (empty($cart)) {
        die(json_encode(['status' => 'error', 'message' => 'Keranjang kosong'])); 
    }

    // Hitung Total
    $subtotal = 0;
    foreach ($cart as $item) {
        $subtotal += ($item['price'] * $item['qty']);
    }
    $total_final = $subtotal + $ship_cost;

    // INSERT QUERY (SESUAI DATABASE KAMU)
    // Hapus 'order_id' karena database pakai 'id' auto increment
    $sql = "INSERT INTO orders (user_id, name, phone, address, province, city, total_price, shipping_cost, payment_method, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', NOW())";
    
    $stmt = $conn->prepare($sql);
    
    if(!$stmt) {
        die(json_encode(['status' => 'error', 'message' => 'SQL Error: ' . $conn->error]));
    }

    // Bind Params (10 parameter): i=int, s=string, d=decimal
    // user_id(i), name(s), phone(s), addr(s), prov(s), city(s), total(d), ship(d), pay(s), status(s) -> sdh hardcoded pending
    $stmt->bind_param("isssssdds", $user_id, $nama, $phone, $addr, $prov, $city, $total_final, $ship_cost, $payment);
    
    if ($stmt->execute()) {
        $new_order_id = $stmt->insert_id; // Ambil ID Angka yang baru dibuat

        // Simpan Item
        $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, product_id, qty, price) VALUES (?, ?, ?, ?)");
        
        foreach ($cart as $item) {
            $stmtItem->bind_param("iiid", $new_order_id, $item['id'], $item['qty'], $item['price']);
            $stmtItem->execute();
        }

        // Auto-Save Profil (Jika Member)
        if ($user_id) {
            $cek = $conn->query("SELECT address_full FROM users WHERE id = $user_id")->fetch_assoc();
            if (empty($cek['address_full'])) {
                $upd = $conn->prepare("UPDATE users SET full_name=?, phone=?, address_full=? WHERE id=?");
                $upd->bind_param("sssi", $nama, $phone, $addr, $user_id);
                $upd->execute();
            }
        }

        // Kirim ID angka ke JS
        echo json_encode(['status' => 'success', 'order_id' => $new_order_id, 'total' => $total_final]);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }
}
?>
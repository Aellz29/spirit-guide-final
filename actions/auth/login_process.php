<?php
session_start();

// INTEGRASI: Mundur 2 langkah (../../) untuk akses config dari folder actions/auth/
include '../../config/db.php'; 

// Cek apakah file diakses via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $identifier = trim($_POST['identifier'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validasi Input Kosong
    if (empty($identifier) || empty($password)) {
        // Redirect balik ke login dengan pesan error
        header("Location: ../../login.php?error=" . urlencode("Silakan isi email dan password."));
        exit;
    }

    // Query Cari User berdasarkan Email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $identifier);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verifikasi Password (Support hash & plain text untuk legacy)
        if (password_verify($password, $user['password']) || $password === $user['password'] || md5($password) === $user['password']) {
            
            // Login Sukses: Simpan Session
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role']
            ];
            $_SESSION['role'] = $user['role'];

            // INTEGRASI: Redirect berdasarkan Role
            if ($user['role'] === 'admin') {
                // Admin masuk ke folder admin
                header("Location: ../../admin/index.php");
            } else {
                // User biasa masuk ke home (root)
                header("Location: ../../index.php");
            }
            exit;

        } else {
            // Password Salah
            header("Location: ../../login.php?error=" . urlencode("Password salah."));
            exit;
        }
    } else {
        // Email Tidak Ditemukan
        header("Location: ../../login.php?error=" . urlencode("Email tidak terdaftar."));
        exit;
    }
    
    $stmt->close();
} else {
    // Jika user coba buka file ini langsung tanpa lewat form
    header("Location: ../../login.php");
    exit;
}
?>


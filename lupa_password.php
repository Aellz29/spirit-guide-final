<?php
session_start();
require 'config/db.php';
date_default_timezone_set('Asia/Jakarta');

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Cek apakah email ada di database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user) {
        // Buat token reset password (simulasi)
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // === PERBAIKAN DISINI ===
        // Menggunakan kolom 'reset_expiry' sesuai database kamu.
        // Mengubah WHERE clause menjadi 'WHERE id = ?' agar cocok dengan parameter $user['id'].
        $update = $conn->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE id = ?");
        $update->bind_param("ssi", $token, $expiry, $user['id']);
        $update->execute();

        // Simulasi pengiriman email
        $link = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/reset_password.php?token=" . $token;

        $message = "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4'>
                        <strong>Simulasi Email:</strong><br>
                        <a href='$link' class='underline font-bold text-blue-600 break-all'>$link</a>
                    </div>";
    } else {
        $message = "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4'>
                        Email tidak ditemukan.
                    </div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password | Spirit Guide</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap">
</head>
<body>
    <div class="absolute inset-0 bg-black/70"></div>
    
    <div class="relative z-10 w-full max-w-md bg-white/10 backdrop-blur-lg border border-white/20 p-8 rounded-2xl shadow-2xl">
        <h2 class="text-2xl font-black mb-2 text-center text-white">Reset Password</h2>
        <p class="text-gray-300 text-sm text-center mb-6">Masukkan email Anda untuk menerima link reset.</p>
        
        <?= $message ?>

        <form method="POST" class="space-y-4">
            <input type="email" name="email" required placeholder="Email Address" class="w-full p-3 rounded-lg bg-black/50 border border-white/20 text-white placeholder-gray-400 focus:border-amber-500 focus:outline-none transition">
            <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-black font-bold rounded-lg transition">Kirim Link</button>
        </form>
        <div class="text-center mt-4">
            <a href="login.php" class="text-sm text-gray-400 hover:text-white">Kembali ke Login</a>
        </div>
    </div>
</body>
</html>
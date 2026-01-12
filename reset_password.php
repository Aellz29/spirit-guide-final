<?php
session_start();
require "config/db.php";
date_default_timezone_set('Asia/Jakarta');

$token = $_GET['token'] ?? '';
$valid = false;
$msg = '';

// Validasi Token
if ($token) {
    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    if ($user) $valid = true;
    else $msg = "<div class='text-red-500 font-bold mb-4 bg-red-100 p-3 rounded'>Token tidak valid atau kadaluarsa.</div>";
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && $valid) {
    $pass = $_POST['password'];
    // Gunakan password_hash jika di login.php pakai password_verify. 
    // Jika masih pakai MD5, gunakan md5($pass). Disarankan password_hash.
    $hashed = md5($pass); // Sesuaikan dengan sistem login kamu saat ini (MD5/Bcrypt)

    $upd = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE id = ?");
    $upd->bind_param("si", $hashed, $user['id']);
    
    if ($upd->execute()) {
        $msg = "<div class='text-green-500 font-bold mb-4 bg-green-100 p-3 rounded'>Password berhasil diubah! <a href='login.php' class='underline'>Login</a></div>";
        $valid = false; // Sembunyikan form
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Password Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; background: #000; color: white; }</style>
</head>
<body class="flex items-center justify-center min-h-screen p-4 bg-gray-900">
    <div class="w-full max-w-md bg-white/10 backdrop-blur-lg border border-white/20 p-8 rounded-2xl">
        <h2 class="text-2xl font-black mb-6 text-center text-white">Password Baru</h2>
        <?= $msg ?>
        <?php if ($valid): ?>
        <form method="POST" class="space-y-4">
            <input type="password" name="password" required placeholder="Password Baru" class="w-full p-3 rounded-lg bg-black/50 border border-white/20 text-white focus:border-amber-500 outline-none">
            <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-black font-bold rounded-lg">Simpan</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
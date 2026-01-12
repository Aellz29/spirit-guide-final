<?php
session_start();
require 'config/db.php';
date_default_timezone_set('Asia/Jakarta');

$message = '';
$msgType = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Cek apakah email ada di database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user) {
        // 1. Buat Token & Expiry
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // 2. Update Database
        $update = $conn->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE id = ?");
        $update->bind_param("ssi", $token, $expiry, $user['id']);
        
        if ($update->execute()) {
            // 3. GENERATE LINK
            $link = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/reset_password.php?token=" . $token;

            // === KEAMANAN TINGKAT LANJUT (SIMULASI) ===
            // JANGAN TAMPILKAN LINK DI LAYAR!
            // Simpan link ke file text di server (sebagai simulasi email masuk)
            $logContent = "Waktu: " . date('Y-m-d H:i:s') . "\nEmail: $email\nLink Reset: $link\n-------------------------\n";
            file_put_contents('log_reset.txt', $logContent, FILE_APPEND);

            // Pesan ke User (Teman yang iseng tidak akan melihat linknya disini)
            $msgType = "success";
            $message = "Permintaan diterima! <br>Silakan cek <b>Inbox Email</b> (atau file <code>log_reset.txt</code>) untuk link reset password.";
        } else {
            $msgType = "error";
            $message = "Terjadi kesalahan sistem.";
        }
    } else {
        // SECURITY PRACTICE:
        // Jangan beri tahu jika email tidak ditemukan (agar hacker tidak bisa absen email).
        // Tapi untuk tahap development/kuliah, kita beri tahu saja.
        $msgType = "error";
        $message = "Email tidak terdaftar dalam sistem kami.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password | Spirit Guide</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center relative overflow-hidden">
    
    <div class="absolute inset-0 z-0">
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-amber-500/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 w-full max-w-md p-6">
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-8 rounded-3xl shadow-2xl">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-amber-500/20 mb-4">
                    <i class="fa fa-lock text-2xl text-amber-500"></i>
                </div>
                <h2 class="text-2xl font-black text-white mb-2">Lupa Password?</h2>
                <p class="text-gray-400 text-sm">Masukkan email yang terdaftar untuk mereset password Anda.</p>
            </div>

            <?php if ($message): ?>
                <div class="<?= $msgType == 'success' ? 'bg-green-500/20 border-green-500/50 text-green-200' : 'bg-red-500/20 border-red-500/50 text-red-200' ?> border px-4 py-3 rounded-xl mb-6 text-sm text-center">
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-5">
                <div>
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1 mb-2 block">Email Address</label>
                    <div class="relative">
                        <input type="email" name="email" required placeholder="nama@email.com" 
                            class="w-full pl-12 pr-4 py-3.5 rounded-xl bg-black/40 border border-white/10 text-white placeholder-gray-500 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-all">
                        <i class="fa fa-envelope absolute left-4 top-4 text-gray-500"></i>
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-black font-bold rounded-xl shadow-lg shadow-amber-500/20 transition-all transform hover:scale-[1.02]">
                    Kirim Link Reset
                </button>
            </form>

            <div class="text-center mt-8 pt-6 border-t border-white/10">
                <a href="login.php" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-white transition group">
                    <i class="fa fa-arrow-left group-hover:-translate-x-1 transition"></i> Kembali ke Login
                </a>
            </div>
        </div>
    </div>

</body>
</html>
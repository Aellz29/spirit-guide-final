<?php
session_start();
include 'config/db.php'; // Path Config

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    if ($username === '' || $email === '' || $password === '' || $confirm_password === '') {
        $message = "Semua kolom wajib diisi.";
    } elseif ($password !== $confirm_password) {
        $message = "Password dan verifikasi password tidak sama.";
    } else {
        // Cek username/email kembar
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows > 0) {
            $message = "Username atau Email sudah digunakan.";
        } else {
            // Insert User Baru
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, created_at) VALUES (?, ?, ?, 'customer', NOW())");
            $stmt->bind_param("sss", $username, $email, $hash);

            if ($stmt->execute()) {
                header("Location: login.php?message=" . urlencode("Pendaftaran berhasil! Silakan login."));
                exit;
            } else {
                $message = "Terjadi kesalahan sistem.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar Akun | Spirit Guide</title>
  
  <link href="assets/css/style.css" rel="stylesheet">
  
  <style>
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background-color: #000;
      color: white;
      margin: 0;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
    }
    img.bg-image { position: fixed; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.5; z-index: 0; }
    .bg-overlay { position: fixed; inset: 0; background: rgba(0, 0, 0, 0.7); z-index: 1; }
    .form-container {
      position: relative; z-index: 2; background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(20px);
      border-radius: 24px; border: 1px solid rgba(255, 255, 255, 0.1); padding: 3rem; width: 90%; max-width: 420px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); text-align: center;
    }
    h2 { font-size: 2rem; font-weight: 800; color: #fff; margin-bottom: 0.5rem; letter-spacing: -0.05em; }
    p.subtitle { color: #9ca3af; margin-bottom: 2rem; font-size: 0.9rem; }
    .input-field {
      width: 100%; padding: 14px 16px; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px;
      background: rgba(0, 0, 0, 0.3); color: #fff; font-size: 0.95rem; margin-bottom: 1rem; outline: none; transition: 0.2s;
    }
    .input-field:focus { border-color: #f59e0b; background: rgba(0,0,0,0.5); }
    .btn-submit {
      width: 100%; padding: 14px; border: none; border-radius: 12px; background: #f59e0b;
      font-weight: 700; color: #000; font-size: 1rem; cursor: pointer; transition: 0.3s; margin-top: 1rem;
    }
    .btn-submit:hover { background: #d97706; transform: translateY(-2px); }
    .msg-box { padding: 12px; border-radius: 10px; margin-bottom: 20px; font-weight: 600; font-size: 0.9rem; background-color: rgba(220, 38, 38, 0.2); border: 1px solid rgba(220, 38, 38, 0.5); color: #fca5a5; }
  </style>
</head>
<body>
  <img src="assets/img/SpiritGuide.jpg" class="bg-image">
  <div class="bg-overlay"></div>

  <div class="form-container">
    <h2>Join Spirit Guide</h2>
    <p class="subtitle">Buat akun untuk mulai berbelanja</p>

    <?php if ($message): ?>
      <div class="msg-box"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="POST">
      <input class="input-field" type="text" name="username" placeholder="Username" required>
      <input class="input-field" type="email" name="email" placeholder="Email Address" required>
      <input class="input-field" type="password" name="password" placeholder="Password" required>
      <input class="input-field" type="password" name="confirm_password" placeholder="Ulangi Password" required>
      <button class="btn-submit" type="submit">Daftar Sekarang</button>
    </form>

    <p style="margin-top:1.5rem; color:#9ca3af; font-size:0.9rem;">
      Sudah punya akun? <a href="login.php" style="color:#f59e0b; font-weight:600; text-decoration:none;">Masuk di sini</a>
    </p>
  </div>
</body>
</html>
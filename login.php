<?php 
// Tangkap pesan error/sukses dari URL
$message = $_GET['error'] ?? $_GET['message'] ?? ''; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | Spirit Guide</title>
  
  <link href="assets/css/style.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
  
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
      border-radius: 24px; border: 1px solid rgba(255, 255, 255, 0.1); padding: 3rem; width: 90%; max-width: 400px;
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
    <h2>Welcome Back</h2>
    <p class="subtitle">Masuk untuk melanjutkan belanja</p>

    <?php if (!empty($message)): ?>
      <div class="msg-box"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="POST" action="actions/auth/login_process.php">
      <input type="email" name="identifier" placeholder="Email Address" required class="input-field">
      <input type="password" name="password" placeholder="Password" required class="input-field">
      <button type="submit" class="btn-submit">Masuk Sekarang</button>
    </form>

    <p style="margin-top:1.5rem; color:#9ca3af; font-size:0.9rem;">
      Belum punya akun? <a href="daftar.php" style="color:#f59e0b; font-weight:600; text-decoration:none;">Daftar dulu</a>
    </p>
    <p style="margin-top:1.5rem; color:#9ca3af; font-size:0.9rem;">
      Lupa Password? <a href="lupa_password.php" style="color:#f59e0b; font-weight:600; text-decoration:none;">klik disini</a>
    </p>
  </div>
</body>
</html>
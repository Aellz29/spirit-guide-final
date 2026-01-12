<?php
session_start();
require "config/db.php";

// Cek Login (Wajib)
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];
$success_msg = "";
$error_msg = "";

// --- 1. LOGIC UPDATE PROFIL ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $full_name = trim($_POST['full_name']);
    $phone     = trim($_POST['phone']);
    $address   = trim($_POST['address']);
    
    // Pastikan kolom di tabel 'users' adalah: full_name, phone, address_full
    // Jika beda, sesuaikan query ini
    $stmt = $conn->prepare("UPDATE users SET full_name=?, phone=?, address_full=? WHERE id=?");
    $stmt->bind_param("sssi", $full_name, $phone, $address, $user_id);
    
    if ($stmt->execute()) {
        $success_msg = "Data profil berhasil disimpan!";
        // Update session biar langsung berubah tanpa logout
        $_SESSION['user']['full_name'] = $full_name;
    } else {
        $error_msg = "Gagal menyimpan data: " . $conn->error;
    }
}

// --- 2. AMBIL DATA USER TERBARU ---
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// --- 3. AMBIL RIWAYAT PESANAN ---
// Mengambil data pesanan urut dari yang terbaru
$orders = $conn->query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya | Spirit Guide</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #F9FAFB; }
        .input-field {
            width: 100%; background-color: #fff; border: 1px solid #E5E7EB;
            border-radius: 0.75rem; padding: 0.75rem 1rem; font-size: 0.875rem;
            color: #111827; outline: none; transition: all 0.2s;
        }
        .input-field:focus { border-color: #F59E0B; box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1); }
        .input-field:disabled { background-color: #F3F4F6; color: #9CA3AF; cursor: not-allowed; }
    </style>
</head>
<body class="antialiased text-gray-900">

    <?php include 'partials/navbar.php'; ?>

    <main class="pt-32 pb-20 px-4 md:px-6 min-h-screen">
        <div class="max-w-6xl mx-auto">
            
            <div class="mb-8 border-b border-gray-200 pb-4 flex flex-col md:flex-row justify-between items-end gap-4">
                <div>
                    <h1 class="text-3xl font-black uppercase tracking-tighter">Akun Saya</h1>
                    <p class="text-sm text-gray-500 font-bold">Kelola profil dan pantau pesananmu</p>
                </div>
                <a href="actions/auth/logout.php" class="bg-red-50 text-red-600 px-5 py-2 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-red-100 transition">
    <i class="fa fa-sign-out-alt mr-2"></i> Logout
</a>
            </div>

            <?php if($success_msg): ?>
                <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 font-bold text-sm flex items-center gap-2">
                    <i class="fa fa-check-circle"></i> <?= $success_msg ?>
                </div>
            <?php endif; ?>
            
            <?php if($error_msg): ?>
                <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 font-bold text-sm flex items-center gap-2">
                    <i class="fa fa-exclamation-circle"></i> <?= $error_msg ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100 h-fit">
                    <h2 class="text-lg font-bold uppercase tracking-widest mb-6 flex items-center gap-2">
                        <i class="fa fa-user-circle text-amber-500"></i> Data Pengiriman
                    </h2>
                    
                    <form method="POST">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Username / Email</label>
                                <input type="text" value="<?= htmlspecialchars($user['username']) ?>" disabled class="input-field">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Nama Lengkap</label>
                                <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" class="input-field" placeholder="Nama Penerima Paket">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">No WhatsApp</label>
                                <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" class="input-field" placeholder="08...">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Alamat Utama</label>
                                <textarea name="address" rows="3" class="input-field resize-none" placeholder="Alamat lengkap untuk pengiriman otomatis"><?= htmlspecialchars($user['address_full'] ?? '') ?></textarea>
                            </div>
                            <button type="submit" name="update_profile" class="w-full bg-black text-white py-3 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-amber-500 hover:text-black transition shadow-lg">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <div class="lg:col-span-2 space-y-6">
                    <h2 class="text-lg font-bold uppercase tracking-widest flex items-center gap-2 mb-2">
                        <i class="fa fa-box-open text-amber-500"></i> Riwayat Pesanan
                    </h2>

                    <?php if($orders->num_rows > 0): ?>
                        <div class="grid gap-4">
                            <?php while($o = $orders->fetch_assoc()): ?>
                                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition group">
                                    
                                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-gray-100 pb-4 mb-4 gap-2">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Order ID</span>
                                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-[10px] font-mono font-bold">#<?= $o['id'] ?></span>
                                            </div>
                                            <p class="text-[10px] text-gray-400 mt-1">
                                                <i class="far fa-clock mr-1"></i> <?= date('d M Y • H:i', strtotime($o['created_at'])) ?>
                                            </p>
                                        </div>
                                        
                                        <div>
                                            <?php 
                                                $st = strtolower($o['status']);
                                                $badgeClass = 'bg-gray-100 text-gray-500';
                                                $label = $o['status'];

                                                if($st == 'pending') { 
                                                    $badgeClass = 'bg-yellow-50 text-yellow-600 border border-yellow-100'; 
                                                    $label = 'Menunggu Konfirmasi';
                                                }
                                                elseif($st == 'verifying') { 
                                                    $badgeClass = 'bg-blue-50 text-blue-600 border border-blue-100'; 
                                                    $label = 'Cek Pembayaran';
                                                }
                                                elseif($st == 'shipping' || $st == 'dikirim') { 
                                                    $badgeClass = 'bg-indigo-50 text-indigo-600 border border-indigo-100'; 
                                                    $label = 'Sedang Dikirim';
                                                }
                                                elseif($st == 'success' || $st == 'selesai') { 
                                                    $badgeClass = 'bg-green-50 text-green-600 border border-green-100'; 
                                                    $label = 'Selesai';
                                                }
                                                elseif($st == 'canceled' || $st == 'batal') { 
                                                    $badgeClass = 'bg-red-50 text-red-600 border border-red-100'; 
                                                    $label = 'Dibatalkan';
                                                }
                                            ?>
                                            <span class="<?= $badgeClass ?> px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest flex items-center gap-1 w-fit">
                                                <div class="w-1.5 h-1.5 rounded-full bg-current animate-pulse"></div>
                                                <?= $label ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col md:flex-row justify-between items-end gap-4">
                                        <div class="text-sm text-gray-600">
                                            <p class="text-[10px] uppercase font-bold text-gray-400 mb-1">Dikirim ke:</p>
                                            <p class="font-bold"><?= htmlspecialchars($o['name']) ?></p>
                                            <p class="text-xs"><?= htmlspecialchars($o['city']) ?>, <?= htmlspecialchars($o['province']) ?></p>
                                        </div>

                                        <div class="text-right">
                                            <?php if(!empty($o['tracking_number'])): ?>
                                                <div class="mb-2">
                                                    <p class="text-[10px] uppercase font-bold text-gray-400">Nomor Resi</p>
                                                    <p class="font-mono font-bold text-blue-600 select-all cursor-pointer bg-blue-50 px-2 py-1 rounded">
                                                        <?= $o['tracking_number'] ?>
                                                    </p>
                                                </div>
                                            <?php endif; ?>

                                            <p class="text-[10px] uppercase font-bold text-gray-400">Total Belanja</p>
                                            <p class="text-xl font-black text-gray-900">
                                                Rp <?= number_format($o['total_price'], 0, ',', '.') ?>
                                            </p>
                                        </div>
                                    </div>

                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-gray-200">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                                <i class="fa fa-shopping-bag text-2xl"></i>
                            </div>
                            <p class="text-gray-900 font-bold text-sm mb-1">Belum ada riwayat pesanan.</p>
                            <p class="text-gray-400 text-xs mb-4">Yuk mulai belanja koleksi terbaik kami!</p>
                            <a href="index.php" class="bg-black text-white px-6 py-2 rounded-full text-xs font-bold uppercase tracking-widest hover:bg-amber-500 hover:text-black transition">
                                Mulai Belanja
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </main>

    <?php include 'partials/footer.php'; ?>
</body>
</html>
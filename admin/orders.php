<?php
session_start();
include '../config/db.php';
include '../partials/navbar.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php"); exit;
}

$query = "SELECT * FROM orders ORDER BY id DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <title>Admin Orders | Spirit Guide</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { background: #0a0a0a; color: #e0e0e0; font-family: sans-serif; }</style>
</head>
<body class="p-4 md:p-10">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-2xl font-bold uppercase tracking-tighter italic text-amber-400">Orders <span class="text-white">Data</span></h1>
            </div>
            <a href="index.php" class="text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-white transition">← Kembali ke Dashboard</a>
        </div>
        
        <div class="overflow-x-auto bg-white/5 rounded-3xl border border-white/10">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] uppercase tracking-wider text-gray-500 border-b border-white/10">
                        <th class="p-5">ID</th>
                        <th class="p-5">Pelanggan</th>
                        <th class="p-5">Bukti</th>
                        <th class="p-5 text-center">Status</th>
                        <th class="p-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr class="border-b border-white/5 hover:bg-white/5 transition">
                        <td class="p-5 font-mono text-amber-500">#<?= $row['id'] ?></td>
                        <td class="p-5">
                            <div class="font-bold text-white"><?= htmlspecialchars($row['name']); ?></div>
                            <div class="text-[10px] text-gray-400 italic"><?= $row['payment_method']; ?></div>
                        </td>
                        <td class="p-5">
                            <?php if(!empty($row['proof_image'])): ?>
                                <a href="../assets/uploads/proofs/<?= $row['proof_image'] ?>" target="_blank" class="text-[10px] text-amber-500 hover:underline font-bold uppercase">Lihat Bukti</a>
                            <?php else: ?>
                                <span class="text-[10px] text-gray-600 italic">No Upload</span>
                            <?php endif; ?>
                        </td>
                        <td class="p-5 text-center">
                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase border border-white/10 bg-white/5 text-gray-300"><?= $row['status'] ?></span>
                        </td>
                        <td class="p-5 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="../actions/order/update_status.php?id=<?= $row['id'] ?>&to=verifying" class="bg-amber-600 text-white px-3 py-2 rounded-lg text-[10px] font-bold uppercase hover:bg-amber-700">Verif</a>
                                <a href="../actions/order/update_status.php?id=<?= $row['id'] ?>&to=shipping" class="bg-blue-600 text-white px-3 py-2 rounded-lg text-[10px] font-bold uppercase hover:bg-blue-700">Kirim</a>
                                <a href="../actions/order/update_status.php?id=<?= $row['id'] ?>&to=success" class="bg-green-600 text-white px-3 py-2 rounded-lg text-[10px] font-bold uppercase hover:bg-green-700">Selesai</a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
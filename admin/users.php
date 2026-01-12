<?php
session_start();
include '../config/db.php';
include '../partials/navbar.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php"); exit;
}

$message = '';
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) { $message = "Pengguna berhasil dihapus."; }
}
$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <title>Kelola Pengguna | Spirit Guide</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { background: #0a0a0a; color: #e0e0e0; font-family: sans-serif; }</style>
</head>
<body class="p-4 md:p-10">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-2xl font-bold uppercase tracking-tighter italic text-amber-400">User <span class="text-white">Database</span></h1>
            </div>
            <a href="index.php" class="text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-white transition">← Kembali ke Dashboard</a>
        </div>

        <?php if($message): ?><div class="mb-6 p-4 bg-green-500/10 text-green-500 text-xs font-bold uppercase rounded-xl"><?= $message ?></div><?php endif; ?>

        <div class="bg-white/5 rounded-2xl overflow-hidden border border-white/10">
            <table class="w-full text-left">
                <thead class="bg-white/5 text-[10px] font-bold uppercase tracking-widest text-amber-400">
                    <tr><th class="p-5">Username</th><th class="p-5">Email</th><th class="p-5">Role</th><th class="p-5 text-right">Aksi</th></tr>
                </thead>
                <tbody class="text-sm divide-y divide-white/5">
                    <?php while ($u = $result->fetch_assoc()): ?>
                    <tr class="hover:bg-white/5 transition">
                        <td class="p-5 font-bold uppercase text-white"><?= htmlspecialchars($u['username']); ?></td>
                        <td class="p-5 text-gray-400"><?= htmlspecialchars($u['email']); ?></td>
                        <td class="p-5"><span class="px-2 py-1 rounded text-[9px] font-bold uppercase <?= $u['role'] == 'admin' ? 'bg-amber-500 text-black' : 'bg-white/10 text-gray-400' ?>"><?= $u['role']; ?></span></td>
                        <td class="p-5 text-right">
                            <?php if ($u['role'] !== 'admin'): ?>
                                <a href="?delete=<?= $u['id']; ?>" onclick="return confirm('Hapus?')" class="text-red-500 text-[10px] font-bold uppercase hover:underline">Hapus</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
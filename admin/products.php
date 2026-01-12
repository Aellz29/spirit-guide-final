<?php
session_start();
include '../config/db.php'; // MUNDUR 1 PATH
include '../partials/navbar.php'; // INCLUDE NAVBAR

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php"); exit;
}

$message = '';

// Handle Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $title = trim($_POST['title']);
    $category = $_POST['category'];
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $original_price = !empty($_POST['original_price']) ? floatval($_POST['original_price']) : NULL;
    $member_price = !empty($_POST['member_price']) ? floatval($_POST['member_price']) : NULL;
    $is_flash_sale = isset($_POST['is_flash_sale']) ? 1 : 0;

    $imgPath = null;
    if (!empty($_FILES['image']['name'])) {
        $name = 'prod_'.time().'.'.pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        
        // PATH FISIK: Mundur dari admin -> root -> assets -> uploads -> products
        $target = __DIR__ . '/../assets/uploads/products/' . $name;
        
        // Buat folder jika belum ada (Opsional, tapi aman)
        if (!is_dir(__DIR__ . '/../assets/uploads/products/')) {
            mkdir(__DIR__ . '/../assets/uploads/products/', 0777, true);
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            // PATH DB: Simpan relatif dari root 'assets/...'
            $imgPath = 'assets/uploads/products/' . $name;
        }
    }

    $stmt = $conn->prepare("INSERT INTO products (title, category, description, price, member_price, stock, image, original_price, is_flash_sale) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssddisdi", $title, $category, $description, $price, $member_price, $stock, $imgPath, $original_price, $is_flash_sale);
    
    if ($stmt->execute()) $message = "Produk Berhasil Disimpan!";
    $stmt->close();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM products WHERE id=$id");
    header("Location: products.php"); exit;
}

$res = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kelola Produk | Spirit Guide</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>body { background-color: #050505; color: white; } .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }</style>
</head>
<body class="p-5 md:p-10">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-10">
            <h1 class="text-3xl font-black italic tracking-tighter uppercase text-white">Input <span class="text-amber-500">Barang</span></h1>
            <a href="index.php" class="text-[10px] font-bold text-gray-500 hover:text-white transition tracking-widest uppercase border border-white/10 px-4 py-2 rounded-lg hover:bg-white/5">
                <i class="fa fa-arrow-left mr-2"></i> Dashboard
            </a>
        </div>

        <?php if($message): ?>
            <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 text-green-500 rounded-xl text-xs font-bold uppercase tracking-widest flex items-center gap-2">
                <i class="fa fa-check-circle"></i> <?= $message ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <div class="glass p-8 rounded-[30px] h-fit relative overflow-hidden">
                <form method="POST" enctype="multipart/form-data" class="space-y-5">
                    <input type="hidden" name="action" value="create">
                    
                    <div>
                        <label class="text-[10px] uppercase text-gray-500 font-bold mb-1 block">Nama Barang</label>
                        <input type="text" name="title" required placeholder="Contoh: Jaket Varsity" class="w-full p-3 rounded-xl bg-white/5 border border-white/10 outline-none text-sm">
                    </div>
                    
                    <div>
                        <label class="text-[10px] uppercase text-gray-500 font-bold mb-1 block">Kategori</label>
                        <select name="category" class="w-full p-3 rounded-xl bg-white/5 border border-white/10 outline-none text-sm">
                            <option value="Fashion" class="bg-black">Fashion</option>
                            <option value="Food" class="bg-black">Food</option>
                            <option value="Aksesoris" class="bg-black">Aksesoris</option>
                            <option value="Other" class="bg-black">Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-[10px] uppercase text-gray-500 font-bold mb-1 block">Deskripsi</label>
                        <textarea name="description" rows="3" class="w-full p-3 rounded-xl bg-white/5 border border-white/10 outline-none text-sm"></textarea>
                    </div>

                    <div class="p-5 rounded-2xl bg-white/5 border border-white/5 space-y-4">
                        <div>
                            <label class="text-[10px] uppercase text-gray-300 font-bold mb-1 block">Harga Normal</label>
                            <input type="number" name="price" required class="w-full p-2 rounded-lg text-sm bg-black/20 border border-white/10">
                        </div>
                        <div>
                            <label class="text-[10px] uppercase text-blue-400 font-bold mb-1 block">Harga Member</label>
                            <input type="number" name="member_price" class="w-full p-2 rounded-lg text-sm bg-black/20 border border-white/10">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] uppercase text-gray-500 font-bold mb-1 block">Stok</label>
                            <input type="number" name="stock" required class="w-full p-3 rounded-xl bg-white/5 border border-white/10 text-center">
                        </div>
                        <div class="flex items-end">
                            <label class="flex items-center gap-3 p-3 w-full bg-red-500/10 border border-red-500/20 rounded-xl cursor-pointer">
                                <input type="checkbox" name="is_flash_sale" value="1" class="accent-red-500">
                                <span class="text-[10px] uppercase text-red-400 font-black">Flash Sale</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] uppercase text-gray-500 font-bold mb-1 block">Foto Produk</label>
                        <input type="file" name="image" class="block w-full text-xs text-gray-400 file:bg-white/10 file:text-white file:border-0 file:rounded-full file:px-4 file:py-2">
                    </div>

                    <button type="submit" class="w-full bg-amber-500 text-black font-black py-4 rounded-2xl hover:bg-amber-400 transition uppercase text-xs tracking-[0.2em] shadow-lg">Upload Produk</button>
                </form>
            </div>

            <div class="lg:col-span-2 glass p-8 rounded-[30px] overflow-hidden flex flex-col h-full">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="text-[10px] uppercase text-gray-500 border-b border-white/10 tracking-widest">
                                <th class="pb-4">Produk</th>
                                <th class="pb-4 text-center">Stok</th>
                                <th class="pb-4 text-center">Harga</th>
                                <th class="pb-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($p = $res->fetch_assoc()): ?>
                            <tr class="border-b border-white/5 hover:bg-white/5 transition">
                                <td class="py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl overflow-hidden border border-white/10">
                                            <img src="../<?= $p['image'] ?>" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <p class="font-bold text-white text-sm"><?= $p['title'] ?></p>
                                            <p class="text-[10px] text-gray-500 uppercase"><?= $p['category'] ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 text-center font-bold text-xs"><?= $p['stock'] ?></td>
                                <td class="py-4 text-center text-xs">Rp <?= number_format($p['price'],0,',','.') ?></td>
                                <td class="py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="products_edit.php?id=<?= $p['id'] ?>" class="w-8 h-8 flex items-center justify-center bg-white/5 rounded text-gray-400 hover:bg-amber-500 hover:text-black"><i class="fa fa-pencil"></i></a>
                                        <a href="products.php?delete=<?= $p['id'] ?>" onclick="return confirm('Hapus?')" class="w-8 h-8 flex items-center justify-center bg-red-500/10 rounded text-red-500 hover:bg-red-500 hover:text-white"><i class="fa fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
session_start();
require "../config/db.php";

// Cek Admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Ambil ID Produk
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: products.php");
    exit;
}

// Ambil Data Produk Lama
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    echo "Produk tidak ditemukan.";
    exit;
}

// PROSES UPDATE DATA
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $original_price = $_POST['original_price']; // Harga Coret
    $member_price = $_POST['member_price'];     // Harga Member (BARU)
    $stock = $_POST['stock'];
    $description = $_POST['description'] ?? ''; 
    $is_flash_sale = isset($_POST['is_flash_sale']) ? 1 : 0;

    // LOGIC UPLOAD GAMBAR
    $imagePath = $product['image']; // Default pake gambar lama

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $targetDir = "../assets/uploads/products/";
        
        // Buat folder jika belum ada
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = "prod_" . time() . "_" . rand(100,999) . "." . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = "assets/uploads/products/" . $fileName;
        } else {
            echo "<script>alert('Gagal upload gambar!');</script>";
        }
    }

    // UPDATE DATABASE (Ditambah member_price)
    // Urutan tipe data (s=string, i=integer): s s i i i i s s i i
    $update = $conn->prepare("UPDATE products SET title=?, category=?, price=?, original_price=?, member_price=?, stock=?, description=?, image=?, is_flash_sale=? WHERE id=?");
    $update->bind_param("ssiiiissii", $title, $category, $price, $original_price, $member_price, $stock, $description, $imagePath, $is_flash_sale, $id);

    if ($update->execute()) {
        echo "<script>alert('Produk berhasil diupdate!'); window.location='products.php';</script>";
    } else {
        echo "<script>alert('Gagal update database: " . $conn->error . "');</script>";
    }
}
?>

<?php include '../partials/header.php'; ?>

<div class="flex h-screen bg-gray-900 text-white">
    <?php include '../partials/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto p-8 ml-0 lg:ml-72 transition-all">
        <div class="max-w-5xl mx-auto">
            <h1 class="text-3xl font-bold mb-8 flex items-center gap-3">
                <a href="products.php" class="text-gray-500 hover:text-white"><i class="fa fa-arrow-left text-xl"></i></a>
                Edit Produk
            </h1>

            <form method="POST" enctype="multipart/form-data" class="bg-gray-800 p-8 rounded-2xl border border-gray-700 space-y-6 shadow-xl">
                
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-300">Nama Produk</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($product['title']) ?>" class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 focus:outline-none focus:border-amber-500 focus:bg-gray-700 transition" required>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-300">Kategori</label>
                        <select name="category" class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 focus:border-amber-500">
                            <option value="Fashion" <?= $product['category'] == 'Fashion' ? 'selected' : '' ?>>Fashion</option>
                            <option value="Food" <?= $product['category'] == 'Food' ? 'selected' : '' ?>>Food</option>
                            <option value="Aksesoris" <?= $product['category'] == 'Aksesoris' ? 'selected' : '' ?>>Aksesoris</option>
                            <option value="Other" <?= $product['category'] == 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-300">Stok</label>
                        <input type="number" name="stock" value="<?= $product['stock'] ?>" class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 focus:border-amber-500" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-300">Harga Jual (Rp)</label>
                        <input type="number" name="price" value="<?= $product['price'] ?>" class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 focus:border-amber-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-400">Harga Coret / Asli (Rp)</label>
                        <input type="number" name="original_price" value="<?= $product['original_price'] ?>" class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 focus:border-amber-500" placeholder="0 jika tidak diskon">
                        <p class="text-xs text-gray-500 mt-1">Isi jika sedang diskon umum.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold mb-2 text-blue-400">
                            <i class="fa fa-crown mr-1"></i> Harga Member (Rp)
                        </label>
                        <input type="number" name="member_price" value="<?= $product['member_price'] ?>" class="w-full bg-gray-700 border border-blue-500/50 rounded-lg p-3 focus:border-blue-500 focus:bg-gray-900/50 transition" placeholder="Opsional">
                        <p class="text-xs text-gray-500 mt-1">Lebih murah khusus user login.</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-300">Deskripsi Produk</label>
                    <textarea name="description" rows="4" class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 focus:border-amber-500"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                </div>

                <div class="flex items-center gap-3 bg-red-900/20 border border-red-900/30 p-4 rounded-lg">
                    <input type="checkbox" name="is_flash_sale" id="flash" class="w-5 h-5 text-red-500 rounded focus:ring-0 cursor-pointer" <?= $product['is_flash_sale'] ? 'checked' : '' ?>>
                    <label for="flash" class="font-bold text-sm cursor-pointer select-none text-red-400">
                        <i class="fa fa-bolt mr-2"></i>Aktifkan Produk Ini Sebagai Flash Sale?
                    </label>
                </div>

                <div class="border-t border-gray-700 pt-6">
                    <label class="block text-sm font-bold mb-2 text-gray-300">Gambar Produk</label>
                    <div class="flex items-center gap-6">
                        <?php if($product['image']): ?>
                            <div class="text-center">
                                <img src="../<?= $product['image'] ?>" class="w-24 h-24 object-cover rounded-lg border border-gray-600 shadow-md">
                                <span class="text-[10px] text-gray-500 block mt-1">Preview Lama</span>
                            </div>
                        <?php endif; ?>
                        <div class="flex-1">
                            <input type="file" name="image" accept="image/*" class="w-full bg-gray-700 border border-gray-600 rounded-lg p-2 text-sm text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-amber-500 file:text-black hover:file:bg-amber-400 cursor-pointer">
                            <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, WEBP. Biarkan kosong jika tidak ingin mengubah gambar.</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 pt-6 border-t border-gray-700">
                    <a href="products.php" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 rounded-lg font-bold transition">Batal</a>
                    <button type="submit" class="px-8 py-3 bg-amber-500 hover:bg-amber-400 text-black rounded-lg font-bold transition flex-1 shadow-lg shadow-amber-500/20">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </main>
</div>
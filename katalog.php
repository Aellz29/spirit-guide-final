<?php
session_start();
// PATH CONFIG SUDAH BENAR (karena file ini di root)
require "config/db.php";

$isLoggedIn = isset($_SESSION['user']);
$category = $_GET['category'] ?? null;
// Tambahkan kategori "Semua" atau default handling
$allowed  = ["Fashion", "Food", "Aksesoris", "Other"];

if (!in_array($category, $allowed)) {
  // Redirect ke home atau tampilkan pesan lebih proper
  echo "<script>alert('Kategori tidak ditemukan!'); window.location='index.php';</script>";
  exit;
}

// LOGIKA MODIFIKASI:
// Jika user klik 'Other', tampilkan SEMUA produk (All items).
// Jika user klik kategori lain (Fashion/Food/dll), tampilkan sesuai kategori saja.

if ($category === 'Other') {
    // Query tanpa "WHERE category = ..." agar semua produk muncul
    $stmt = $conn->prepare("SELECT * FROM products ORDER BY is_flash_sale DESC, id DESC");
    $stmt->execute();
} else {
    // Query standar untuk kategori spesifik
    $stmt = $conn->prepare("SELECT * FROM products WHERE category = ? ORDER BY is_flash_sale DESC, id DESC");
    $stmt->bind_param("s", $category);
    $stmt->execute();
}

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog <?= htmlspecialchars($category) ?> | Spirit Guide</title>
    
    <link rel="stylesheet" href="assets/css/style.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FAFAFA; color: #1a1a1a; }
        .fade-in { animation: fadeIn 0.8s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .product-card { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .product-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px -10px rgba(0,0,0,0.1); }
        .product-card:hover .product-img { transform: scale(1.08); }
        .product-card:hover .quick-view { opacity: 1; transform: translateY(0); }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }
        .pulse-badge { animation: pulse-red 2s infinite; }
        @keyframes pulse-red { 0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); } 70% { box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); } 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); } }
    </style>
</head>
<body class="bg-[#FAFAFA] text-gray-900 antialiased selection:bg-amber-200">

<?php include 'partials/navbar.php'; ?>

<main class="pt-32 pb-20 min-h-screen">
    <section class="max-w-[1400px] mx-auto px-6">
        
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 fade-in">
            <div class="relative pl-6 border-l-4 border-amber-500">
                <h1 class="text-5xl md:text-7xl font-black uppercase tracking-tighter text-gray-900 leading-none mb-2">
                    <?= htmlspecialchars($category) ?>
                </h1>
                <p class="text-sm text-gray-500 font-medium tracking-widest uppercase">Premium Collection • Spirit Guide</p>
            </div>

            <div class="w-full md:w-80 mt-8 md:mt-0 relative group">
                <input type="text" id="productSearch" placeholder="CARI KOLEKSI..." 
                    class="w-full bg-white border border-gray-200 rounded-full py-3 px-6 pl-12 text-xs font-bold tracking-widest uppercase focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none transition-all shadow-sm group-hover:shadow-md">
                <i class="fa fa-search absolute left-5 top-3.5 text-gray-400 group-focus-within:text-amber-500 transition"></i>
            </div>
        </div>

        <div id="productGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 fade-in" style="animation-delay: 0.2s;">
            <?php while($p = $result->fetch_assoc()): 
                $showMemberPrice = $isLoggedIn && !empty($p['member_price']) && $p['member_price'] < $p['price'];
                $finalPrice = $showMemberPrice ? $p['member_price'] : $p['price'];
                $hargaCoret = 0;
                
                // Logic Coret: Kalau ada harga asli > harga jual, ATAU kalau member dapet diskon
                if ($p['original_price'] > $p['price']) $hargaCoret = $p['original_price'];
                elseif ($showMemberPrice) $hargaCoret = $p['price'];

                $priceDisplay = number_format($finalPrice, 0, ',', '.');
                $originalDisplay = ($hargaCoret > 0) ? number_format($hargaCoret, 0, ',', '.') : '';
                $diskonPersen = ($hargaCoret > 0) ? round((($hargaCoret - $finalPrice) / $hargaCoret) * 100) : 0;
                
                // DATA JSON UNTUK MODAL (Biar gak ribet parsing parameter function)
                // Kita encode data produk ke JSON biar aman dipanggil JS
                $productData = htmlspecialchars(json_encode([
                    'id' => $p['id'],
                    'title' => $p['title'],
                    'price' => $priceDisplay, // String
                    'rawPrice' => $finalPrice, // Angka
                    'original' => $originalDisplay,
                    'img' => $p['image'], // Pastikan di DB formatnya 'assets/uploads/products/...'
                    'desc' => $p['description'],
                    'stock' => $p['stock'],
                    'isFlash' => $p['is_flash_sale'],
                    'isMember' => $showMemberPrice,
                    'rawOriginal' => $hargaCoret
                ]), ENT_QUOTES, 'UTF-8');
            ?>
            <div class="product-card group bg-white rounded-3xl overflow-hidden relative border border-gray-100 flex flex-col h-full">
                
                <div class="absolute top-4 left-4 z-20 flex flex-col gap-2 items-start">
                    <?php if($p['is_flash_sale']): ?>
                        <span class="pulse-badge bg-red-600 text-white text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest shadow-lg flex items-center gap-1"><i class="fa fa-bolt"></i> Sale</span>
                    <?php endif; ?>
                    <?php if($showMemberPrice): ?>
                        <span class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest shadow-lg flex items-center gap-1"><i class="fa fa-crown"></i> Member</span>
                    <?php endif; ?>
                    <?php if($diskonPersen > 0): ?>
                        <span class="bg-black text-white text-[10px] font-bold px-2 py-1 rounded-lg shadow-md">-<?= $diskonPersen ?>%</span>
                    <?php endif; ?>
                </div>

                <div class="cursor-pointer relative overflow-hidden bg-gray-50 aspect-[4/5]" onclick='openModal(<?= $productData ?>)'>
                    <img src="<?= htmlspecialchars($p['image']) ?>" class="product-img w-full h-full object-cover transition duration-700">
                    <div class="quick-view absolute inset-x-4 bottom-4 opacity-0 translate-y-4 transition duration-300">
                        <button class="w-full bg-white/90 backdrop-blur-sm text-black py-3 rounded-xl font-bold text-xs uppercase tracking-widest shadow-lg hover:bg-black hover:text-white transition"><i class="fa fa-eye mr-1"></i> Quick View</button>
                    </div>
                </div>

                <div class="p-5 flex flex-col flex-1">
                    <div class="flex-1">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1"><?= htmlspecialchars($p['category']) ?></p>
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-tight leading-snug line-clamp-2 mb-3 group-hover:text-amber-600 transition"><?= htmlspecialchars($p['title']) ?></h3>
                    </div>
                    <div class="pt-4 border-t border-gray-50 mt-auto">
                        <div class="flex justify-between items-end mb-4">
                            <div class="flex flex-col">
                                <?php if($hargaCoret > 0): ?>
                                    <span class="text-[11px] text-gray-400 line-through font-medium mb-0.5">Rp <?= number_format($hargaCoret, 0, ',', '.') ?></span>
                                <?php endif; ?>
                                <span class="text-lg font-black <?= $showMemberPrice ? 'text-blue-600' : ($p['is_flash_sale'] ? 'text-red-600' : 'text-gray-900') ?>">Rp <?= $priceDisplay ?></span>
                            </div>
                            <?php if($p['stock'] < 5 && $p['stock'] > 0): ?>
                                <span class="text-[9px] font-bold text-red-500 bg-red-50 px-2 py-1 rounded">Sisa <?= $p['stock'] ?>!</span>
                            <?php endif; ?>
                        </div>
                        
                        <button type="button" onclick="event.stopPropagation(); window.addToCart({
                            id: '<?= $p['id'] ?>', title: '<?= addslashes($p['title']) ?>', price: '<?= $finalPrice ?>', originalPrice: '<?= $hargaCoret ?>', img: '<?= $p['image'] ?>'
                        })" class="w-full bg-black text-white py-3 rounded-xl text-[10px] font-bold uppercase tracking-[0.15em] hover:bg-amber-500 hover:text-black hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2 group-hover:translate-y-0">
                            <span>Add to Cart</span><i class="fa fa-plus text-[9px]"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>
</main>

<div id="productModal" class="fixed inset-0 bg-black/70 hidden flex items-center justify-center z-[999] p-3 backdrop-blur-sm transition-all opacity-0 pointer-events-none">
    
    <div class="bg-white w-full max-w-5xl h-[85vh] md:h-auto md:max-h-[90vh] overflow-hidden relative shadow-2xl rounded-2xl md:rounded-3xl flex flex-col md:flex-row transform scale-95 transition-all duration-300" id="modalContent">
        
        <button onclick="closeModal()" class="absolute top-3 right-3 z-30 w-8 h-8 md:w-10 md:h-10 bg-white/80 backdrop-blur rounded-full flex items-center justify-center text-gray-500 hover:bg-black hover:text-white transition shadow-sm border border-gray-100">
            <i class="fa fa-times text-sm"></i>
        </button>

        <div class="w-full h-48 md:h-auto md:w-1/2 bg-gray-50 flex items-center justify-center p-4 relative shrink-0">
            <img id="modalImg" class="h-full w-auto max-w-full object-contain drop-shadow-xl mix-blend-multiply">
        </div>
        
        <div class="w-full md:w-1/2 flex flex-col bg-white flex-1 overflow-hidden">
            <div class="p-6 md:p-10 overflow-y-auto custom-scrollbar flex flex-col flex-1">
                <nav class="flex items-center gap-2 text-[9px] font-bold uppercase tracking-widest text-amber-600 mb-3">
                    <span>Spirit Guide</span><span class="w-1 h-1 bg-gray-300 rounded-full"></span><span>Details</span>
                </nav>
                
                <h3 id="modalTitle" class="text-xl md:text-4xl font-black uppercase tracking-tighter text-gray-900 leading-none mb-4"></h3>
                
                <div class="p-4 bg-gray-50 rounded-xl mb-6 border border-gray-100">
                    <div class="flex items-center gap-2 mb-2" id="modalBadges"></div>
                    <div class="flex items-baseline gap-2 flex-wrap">
                        <p id="modalPrice" class="text-2xl md:text-3xl font-black text-gray-900"></p>
                        <p id="modalOriginalPrice" class="text-sm text-gray-400 line-through font-bold decoration-2"></p>
                    </div>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-600">Stok: <span id="statusStock" class="text-green-600"></span></p>
                    </div>
                </div>
                
                <div class="text-sm text-gray-500 leading-relaxed mb-8">
                    <p id="modalDesc"></p>
                </div>

                <div class="border-t border-gray-100 pt-6 mb-4">
                    <h3 class="text-xs font-black uppercase tracking-widest text-gray-900 mb-4">Ulasan & Rating</h3>
                    <div id="review-list" class="space-y-3 max-h-40 overflow-y-auto pr-2 custom-scrollbar mb-4">
                        <p class="text-xs text-gray-400 italic">Memuat ulasan...</p>
                    </div>
                    
                    <?php if($isLoggedIn): ?>
                    <form id="reviewForm" class="flex gap-2">
                        <input type="hidden" name="product_id" id="review_product_id">
                        <select name="rating" class="bg-gray-50 border-0 rounded-lg text-xs font-bold focus:ring-2 focus:ring-amber-500 cursor-pointer h-10 px-2">
                            <option value="5">★ 5</option>
                            <option value="4">★ 4</option>
                            <option value="3">★ 3</option>
                            <option value="2">★ 2</option>
                            <option value="1">★ 1</option>
                        </select>
                        <input name="comment" required placeholder="Tulis ulasan..." class="flex-1 bg-gray-50 border-0 rounded-lg text-xs px-4 h-10 focus:ring-2 focus:ring-amber-500">
                        <button type="submit" class="bg-black text-white w-10 h-10 rounded-lg flex items-center justify-center hover:bg-gray-800 transition"><i class="fa fa-paper-plane text-xs"></i></button>
                    </form>
                    <?php else: ?>
                        <p class="text-[10px] text-gray-400 italic bg-gray-50 p-2 rounded text-center">Login untuk menulis ulasan.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="p-4 border-t border-gray-100 bg-white flex gap-3 shrink-0 z-10 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                <button id="modalAddToCartBtn" class="flex-1 py-3 rounded-xl border-2 border-gray-200 font-bold text-[10px] md:text-xs uppercase tracking-widest hover:border-black hover:bg-black hover:text-white transition-all">Add to Cart</button>
                <button onclick="window.location.href='checkout.php'" class="flex-1 py-3 rounded-xl bg-amber-500 text-black font-bold text-[10px] md:text-xs uppercase tracking-widest hover:bg-amber-400 hover:shadow-lg transition-all">Checkout</button>
            </div>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>

<script>window.USER_ID = "<?= isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 'guest' ?>";</script>
<script src="assets/js/cart.js"></script> 
<script src="assets/js/katalog.js"></script> </body>
</html>
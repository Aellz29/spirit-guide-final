<?php
session_start();
require "config/db.php";

// 1. CEK STATUS LOGIN & DATA USER
$isLoggedIn = isset($_SESSION['user']);
$username = $_SESSION['user']['username'] ?? 'Guest';
$role = $_SESSION['user']['role'] ?? 'customer';

// 2. QUERY PRODUK BARU (Limit 8)
$query_new = "SELECT * FROM products WHERE stock > 0 ORDER BY id DESC LIMIT 8";
$res_new = $conn->query($query_new);

// 3. QUERY FLASH SALE (Limit 4)
$query_flash = "SELECT * FROM products WHERE is_flash_sale = 1 AND stock > 0 ORDER BY id DESC LIMIT 4";
$res_flash = $conn->query($query_flash);
?>

<?php include 'partials/header.php'; ?>

<style>
    /* Styling Video Background */
    .video-docker video {
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    .video-docker::after {
        content: "";
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background: rgba(0, 0, 0, 0.6); /* Overlay Gelap Transparan */
        z-index: 1;
    }
    
    /* Animasi Flash Sale */
    @keyframes pulseRed { 0% { opacity: 1; transform: scale(1); } 50% { opacity: 0.8; transform: scale(1.05); } 100% { opacity: 1; transform: scale(1); } }
    .animate-pulse-red { animation: pulseRed 2s infinite; }
</style>

<body class="bg-white text-gray-900 font-sans antialiased">

    <?php include 'partials/navbar.php'; ?>

    <section class="relative h-screen flex flex-col items-center justify-center text-center text-white overflow-hidden bg-black">
        
        <div class="video-docker absolute top-0 left-0 w-full h-full overflow-hidden z-0">
            <video class="min-w-full min-h-full absolute object-cover" src="assets/video/onlineShopping.mp4" type="video/mp4" autoplay muted loop playsinline></video>
        </div>
        
        <div class="z-10 relative px-6 animate-[fadeInUp_1s] flex flex-col items-center w-full max-w-5xl">
            
            <?php if ($isLoggedIn): ?>
                <div class="mb-8 p-3 rounded-full border border-white/20 bg-white/10 backdrop-blur-md shadow-2xl">
                    <img src="assets/img/SpiritGuide.jpg" onerror="this.src='https://ui-avatars.com/api/?name=Spirit+Guide&background=000&color=fff'" alt="Logo" class="w-24 h-24 object-cover rounded-full border-2 border-amber-500 shadow-lg">
                </div>

                <h1 class="text-5xl md:text-7xl font-black tracking-tighter uppercase drop-shadow-xl mb-4 leading-none">
                    Spirit <span class="text-amber-500">Guide</span>
                </h1>

                <p class="text-lg md:text-2xl font-light text-gray-200 mb-8 tracking-widest">
                    Welcome back, <span class="font-bold text-amber-400 border-b-2 border-amber-500 pb-1"><?= htmlspecialchars($username) ?></span>
                </p>
                
                <div class="flex flex-col md:flex-row gap-4">
                    <a href="#new-arrival" class="bg-white text-black px-8 py-4 rounded-full uppercase text-xs font-bold tracking-[0.2em] hover:bg-amber-500 hover:text-white transition transform hover:-translate-y-1 shadow-xl">
                        Shop Now
                    </a>
                    <a href="#flash-sale" class="bg-transparent border border-white text-white px-8 py-4 rounded-full uppercase text-xs font-bold tracking-[0.2em] hover:bg-white hover:text-black transition transform hover:-translate-y-1 shadow-xl">
                        Lihat Promo
                    </a>
                </div>

            <?php else: ?>
                <div class="mb-8 relative group">
                    <div class="w-32 h-32 md:w-40 md:h-40 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center border-2 border-amber-500/50 shadow-2xl relative z-10 p-2">
                        <img src="assets/img/SpiritGuide.jpg" onerror="this.src='https://ui-avatars.com/api/?name=Spirit+Guide&background=000&color=fff'" alt="Spirit Guide Logo" class="w-full h-full object-cover rounded-full">
                    </div>
                    <div class="absolute inset-0 bg-amber-500 blur-3xl opacity-20 rounded-full group-hover:opacity-40 transition duration-700 animate-pulse"></div>
                </div>

                <h1 class="text-4xl md:text-7xl font-black text-white mb-6 drop-shadow-2xl tracking-tighter leading-tight">
                    Elevate Your <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 to-amber-600">Lifestyle</span>
                </h1>

                <p class="text-gray-300 text-sm md:text-lg max-w-2xl mx-auto mb-10 font-light leading-relaxed tracking-wide">
                    Temukan koleksi eksklusif <span class="text-white font-medium">Fashion</span>, <span class="text-white font-medium">Food</span>, dan <span class="text-white font-medium">Aksesoris</span> yang mendefinisikan gaya hidup modern.
                </p>

                <a href="#katalog-list" class="bg-amber-500 hover:bg-amber-400 text-black font-bold py-4 px-10 rounded-full transition transform hover:scale-105 shadow-[0_0_20px_rgba(245,158,11,0.5)] uppercase tracking-widest text-xs">
                    Jelajahi Sekarang
                </a>

            <?php endif; ?>

        </div>
        
        <div class="absolute bottom-10 animate-bounce">
            <i class="fa fa-chevron-down text-white/50 text-2xl"></i>
        </div>
    </section>

    <section id="katalog-list" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-amber-600 font-bold uppercase tracking-[0.2em] text-xs mb-2 block">Our Collections</span>
                <h2 class="text-3xl md:text-4xl font-black text-gray-900 uppercase tracking-tighter">Pilih Kategori</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <a href="katalog.php?category=Fashion" class="group relative h-96 rounded-2xl overflow-hidden shadow-xl cursor-pointer">
                    <img src="assets/img/Fjaket.jpeg" onerror="this.src='https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=800'" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex flex-col justify-end p-8">
                        <h3 class="text-2xl font-bold text-white mb-1">Fashion</h3>
                        <p class="text-gray-300 text-xs font-light opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition duration-500">Tampil stylish setiap hari.</p>
                    </div>
                </a>

                <a href="katalog.php?category=Food" class="group relative h-96 rounded-2xl overflow-hidden shadow-xl cursor-pointer">
                    <img src="assets/img/bolu.jpg" onerror="this.src='https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=800'" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex flex-col justify-end p-8">
                        <h3 class="text-2xl font-bold text-white mb-1">Food</h3>
                        <p class="text-gray-300 text-xs font-light opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition duration-500">Cita rasa yang menggugah selera.</p>
                    </div>
                </a>

                <a href="katalog.php?category=Accessories" class="group relative h-96 rounded-2xl overflow-hidden shadow-xl cursor-pointer">
                    <img src="assets/img/topi.jpeg" onerror="this.src='https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=800'" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex flex-col justify-end p-8">
                        <h3 class="text-2xl font-bold text-white mb-1">Accessories</h3>
                        <p class="text-gray-300 text-xs font-light opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition duration-500">Lengkapi gaya unikmu.</p>
                    </div>
                </a>
            </div>
            
            <div class="text-center mt-12">
                <a href="katalog.php?category=Other" class="inline-flex items-center gap-2 text-gray-500 hover:text-black font-bold uppercase text-xs tracking-widest transition">
                    Lihat Kategori Lainnya <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <section id="new-arrival" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4">
                <div>
                    <h2 class="text-3xl md:text-4xl font-black text-gray-900 uppercase tracking-tighter">New Arrivals</h2>
                    <p class="text-gray-500 text-sm uppercase tracking-widest mt-2">Koleksi Terbaru Minggu Ini</p>
                </div>
                <a href="katalog.php" class="hidden md:block bg-black text-white px-6 py-3 rounded-full text-xs font-bold uppercase tracking-widest hover:bg-amber-500 hover:text-black transition">Lihat Semua</a>
            </div>

            <?php if($res_new->num_rows > 0): ?>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <?php while($row = $res_new->fetch_assoc()): ?>
                <div class="group bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="aspect-[4/5] bg-gray-100 overflow-hidden relative">
                        <img src="<?= htmlspecialchars($row['image']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        
                        <?php if($row['is_flash_sale']): ?>
                        <div class="absolute top-3 left-3 bg-red-600 text-white text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-wide shadow-md animate-pulse">Flash Sale</div>
                        <?php endif; ?>

                        <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition duration-300">
                            <a href="detail_produk.php?id=<?= $row['id'] ?>" class="bg-white text-black w-10 h-10 rounded-full flex items-center justify-center hover:bg-black hover:text-white shadow-lg transition" title="Lihat Detail">
                                <i class="fa fa-eye"></i>
                            </a>
                            <button onclick="addToCart(<?= $row['id'] ?>)" class="bg-amber-500 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-amber-600 shadow-lg transition" title="Tambah ke Keranjang">
                                <i class="fa fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-5">
                        <p class="text-[10px] text-gray-400 font-bold uppercase mb-1 tracking-wider"><?= htmlspecialchars($row['category']) ?></p>
                        <h3 class="font-bold text-gray-900 truncate text-sm mb-2 group-hover:text-amber-600 transition"><?= htmlspecialchars($row['title']) ?></h3>
                        <p class="text-lg font-black text-gray-900">Rp <?= number_format($row['price'],0,',','.') ?></p>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            
            <div class="mt-8 text-center md:hidden">
                <a href="katalog.php" class="inline-block bg-black text-white px-8 py-3 rounded-full text-xs font-bold uppercase tracking-widest hover:bg-amber-500 hover:text-black transition">Lihat Semua</a>
            </div>

            <?php else: ?>
                <div class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                    <p class="text-gray-400 text-sm font-bold uppercase">Belum ada produk baru.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php if ($res_flash->num_rows > 0): ?>
    <section id="flash-sale" class="py-20 bg-black text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-red-600 rounded-full opacity-20 blur-[100px]"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-amber-500 rounded-full opacity-20 blur-[100px]"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="flex items-center justify-between mb-12">
                <h2 class="text-3xl md:text-5xl font-black italic uppercase tracking-tighter flex items-center gap-3">
                    <i class="fa fa-bolt text-amber-500 animate-pulse"></i> Flash Sale
                </h2>
                <div class="bg-red-600 px-4 py-1 rounded text-xs font-bold uppercase tracking-widest animate-pulse">Live Now</div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <?php while($item = $res_flash->fetch_assoc()): ?>
                <div class="bg-neutral-900 rounded-2xl overflow-hidden border border-neutral-800 hover:border-amber-500/50 transition duration-300 group">
                    <div class="aspect-square bg-neutral-800 overflow-hidden relative">
                        <img src="<?= htmlspecialchars($item['image']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500 opacity-90 group-hover:opacity-100">
                        
                        <?php if($item['original_price'] > $item['price']): ?>
                        <div class="absolute top-3 right-3 bg-amber-500 text-black text-[10px] font-black px-2 py-1 rounded">
                            -<?= round((($item['original_price'] - $item['price']) / $item['original_price']) * 100) ?>%
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="p-5">
                        <h3 class="font-bold text-white truncate text-sm mb-2"><?= htmlspecialchars($item['title']) ?></h3>
                        <div class="flex items-center gap-2 mb-3">
                            <?php if($item['original_price'] > $item['price']): ?>
                                <p class="text-xs text-gray-500 line-through">Rp <?= number_format($item['original_price']/1000,0) ?>k</p>
                            <?php endif; ?>
                            <p class="text-amber-500 font-black text-lg">Rp <?= number_format($item['price'],0,',','.') ?></p>
                        </div>
                        
                        <div class="w-full bg-neutral-800 rounded-full h-1.5 mb-2 overflow-hidden">
                            <div class="bg-gradient-to-r from-red-500 to-amber-500 h-1.5 rounded-full" style="width: <?= rand(60, 90) ?>%"></div>
                        </div>
                        <p class="text-[10px] text-gray-400 text-right">Segera Habis!</p>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <section class="py-24 bg-white border-t border-gray-100">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <img src="assets/img/SpiritGuide.jpg" class="w-20 h-20 rounded-full mx-auto mb-6 border border-gray-200 p-1 object-cover">
            <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tighter mb-6">Tentang Spirit Guide</h2>
            <p class="text-gray-500 leading-relaxed text-sm md:text-base mb-10">
                Spirit Guide lebih dari sekadar toko online. Kami adalah kurator gaya hidup yang menghadirkan produk berkualitas tinggi dengan sentuhan personal. Dari fashion yang <span class="font-bold text-black">trendsetter</span> hingga kuliner yang <span class="font-bold text-black">autentik</span>, semua ada di sini untuk melengkapi hari-harimu.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-6 bg-gray-50 rounded-xl">
                    <i class="fa fa-gem text-2xl text-amber-500 mb-3"></i>
                    <h3 class="font-bold text-sm uppercase mb-1">Kualitas Premium</h3>
                    <p class="text-xs text-gray-400">Produk original pilihan.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-xl">
                    <i class="fa fa-shipping-fast text-2xl text-amber-500 mb-3"></i>
                    <h3 class="font-bold text-sm uppercase mb-1">Pengiriman Cepat</h3>
                    <p class="text-xs text-gray-400">Sampai tepat waktu.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-xl">
                    <i class="fa fa-headset text-2xl text-amber-500 mb-3"></i>
                    <h3 class="font-bold text-sm uppercase mb-1">Layanan 24/7</h3>
                    <p class="text-xs text-gray-400">Selalu siap membantu.</p>
                </div>
            </div>
        </div>
    </section>

    <?php include 'partials/footer.php'; ?>

</body>
</html>
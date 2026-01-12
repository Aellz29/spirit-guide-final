<?php
session_start();
require "config/db.php";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Spirit Guide</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #F9FAFB; }
        .text-gold { color: #f59e0b; }
        .bg-gold { background-color: #f59e0b; }
    </style>
</head>
<body class="antialiased text-gray-900">

    <?php include 'partials/navbar.php'; ?>

    <section class="relative pt-32 pb-20 bg-black text-white overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-amber-500 rounded-full opacity-10 blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-500 rounded-full opacity-10 blur-3xl transform -translate-x-1/2 translate-y-1/2"></div>

        <div class="max-w-6xl mx-auto px-6 relative z-10 text-center">
            <span class="text-amber-500 font-bold tracking-[0.3em] uppercase text-xs mb-2 block animate-pulse">Our Story</span>
            <h1 class="text-4xl md:text-6xl font-black uppercase tracking-tighter mb-6">
                More Than Just <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-200 to-amber-500">A Brand</span>
            </h1>
            <p class="text-gray-400 max-w-2xl mx-auto text-sm md:text-base leading-relaxed">
                Spirit Guide hadir untuk mendefinisikan ulang gaya hidup Anda. Kami menggabungkan estetika modern dengan kualitas premium untuk menciptakan identitas yang kuat bagi setiap pemakainya.
            </p>
        </div>
    </section>

    <section class="py-20 px-6">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            
            <div class="relative group">
                <div class="absolute inset-0 bg-black translate-x-2 translate-y-2 rounded-2xl transition-transform group-hover:translate-x-4 group-hover:translate-y-4"></div>
                <img src="assets/images/about-hero.jpg" onerror="this.src='https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=1000&auto=format&fit=crop'" alt="About Spirit Guide" class="relative z-10 w-full h-96 object-cover rounded-2xl border-2 border-gray-100 grayscale group-hover:grayscale-0 transition-all duration-500">
            </div>

            <div>
                <h2 class="text-3xl font-black uppercase tracking-tight mb-4">The Spirit <span class="text-amber-500">Philosophy</span></h2>
                <p class="text-gray-600 mb-4 leading-relaxed text-sm">
                    Didirikan pada tahun 2026, Spirit Guide bermula dari sebuah ide sederhana: Fashion bukan sekadar pakaian, tapi cara kita berkomunikasi dengan dunia tanpa harus berbicara.
                </p>
                <p class="text-gray-600 mb-6 leading-relaxed text-sm">
                    Kami berdedikasi untuk menyediakan produk Fashion, Food, dan Aksesoris yang tidak hanya berkualitas tinggi, tetapi juga memiliki "jiwa" di setiap detailnya.
                </p>
                
                <div class="grid grid-cols-2 gap-6 mt-8">
                    <div>
                        <h3 class="text-2xl font-black text-black">100%</h3>
                        <p class="text-xs text-gray-500 uppercase font-bold tracking-widest">Original Quality</p>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-black">24/7</h3>
                        <p class="text-xs text-gray-500 uppercase font-bold tracking-widest">Premium Support</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section class="py-20 bg-gray-50 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-2xl font-black uppercase tracking-tight">Kenapa Memilih Kami?</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-12 h-12 bg-black text-white rounded-lg flex items-center justify-center mb-4 text-xl">
                        <i class="fa fa-gem"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Kualitas Premium</h3>
                    <p class="text-sm text-gray-500">Bahan pilihan terbaik yang menjamin kenyamanan dan daya tahan produk jangka panjang.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-12 h-12 bg-amber-500 text-white rounded-lg flex items-center justify-center mb-4 text-xl">
                        <i class="fa fa-shipping-fast"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Pengiriman Cepat</h3>
                    <p class="text-sm text-gray-500">Kerjasama dengan logistik terpercaya untuk memastikan pesanan sampai tepat waktu.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-12 h-12 bg-gray-200 text-black rounded-lg flex items-center justify-center mb-4 text-xl">
                        <i class="fa fa-headset"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Layanan Terbaik</h3>
                    <p class="text-sm text-gray-500">Tim support kami siap membantu segala pertanyaan dan kebutuhan Anda 24 jam.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 px-6 bg-black text-white text-center">
        <div class="max-w-2xl mx-auto">
            <h2 class="text-3xl font-black uppercase mb-4">Visit Our Store</h2>
            <p class="text-gray-400 mb-8">
                Jl. Spirit Guide No. 88, Bandung, Jawa Barat, Indonesia.<br>
                Buka Setiap Hari: 10.00 - 22.00 WIB
            </p>
            <a href="index.php" class="inline-block bg-white text-black px-8 py-3 rounded-full font-bold text-sm uppercase tracking-widest hover:bg-amber-500 hover:text-white transition-all transform hover:scale-105">
                Belanja Sekarang
            </a>
        </div>
    </section>

    <?php include 'partials/footer.php'; ?>

</body>
</html>
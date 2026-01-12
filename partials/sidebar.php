<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 glass border-r border-white/5 lg:translate-x-0 -translate-x-full transition-transform duration-300 flex flex-col bg-[#0a0a0a]">
    <div class="p-8">
        <div class="flex items-center gap-4 mb-12">
            <div class="w-10 h-10 rounded-xl bg-amber-500 flex items-center justify-center text-black font-black text-xl shadow-[0_0_20px_rgba(245,158,11,0.4)]">
                <img src="../assets/img/SpiritGuide.jpg" class="w-full h-full rounded-xl object-cover border-2 border-amber-500">
            </div>
            <div>
                <h2 class="font-bold text-lg tracking-tight text-white leading-none">SPIRIT GUIDE</h2>
                <p class="text-[10px] text-gray-500 uppercase tracking-[0.3em] mt-1">Admin Panel</p>
            </div>
        </div>

        <p class="text-xs font-bold text-gray-600 uppercase tracking-widest mb-4 px-4">Menu Utama</p>
        <nav class="space-y-2">
            <a href="index.php" class="flex items-center gap-4 p-4 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition text-sm font-bold <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'text-amber-400 bg-amber-500/10 border border-amber-500/20' : '' ?>">
                <i class="fa fa-gauge w-5 text-center"></i> Dashboard
            </a>
            <a href="products.php" class="flex items-center gap-4 p-4 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition text-sm font-bold <?= basename($_SERVER['PHP_SELF']) == 'products.php' ? 'text-amber-400 bg-amber-500/10 border border-amber-500/20' : '' ?>">
                <i class="fa fa-box w-5 text-center"></i> Produk
            </a>
            <a href="users.php" class="flex items-center gap-4 p-4 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition text-sm font-bold <?= basename($_SERVER['PHP_SELF']) == 'users.php' ? 'text-amber-400 bg-amber-500/10 border border-amber-500/20' : '' ?>">
                <i class="fa fa-users w-5 text-center"></i> Pengguna
            </a>
            <a href="orders.php" class="flex items-center gap-4 p-4 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition text-sm font-bold <?= basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'text-amber-400 bg-amber-500/10 border border-amber-500/20' : '' ?>">
                <i class="fa fa-cart-shopping w-5 text-center"></i> Pesanan
            </a>
        </nav>
    </div>
    
    <div class="mt-auto p-6 border-t border-white/5">
        <a href="../actions/auth/logout.php" class="flex items-center gap-3 p-4 rounded-xl text-red-400 bg-red-500/5 hover:bg-red-500/10 border border-red-500/10 transition text-sm font-bold justify-center group">
            <i class="fa fa-power-off group-hover:scale-110 transition"></i> Logout
        </a>
    </div>
</aside>
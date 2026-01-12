<?php
// Deteksi Path otomatis
$base_url = (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false) ? '../' : '';
$userIdJS = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 'guest';
?>

<script>
    window.USER_ID = "<?= $userIdJS; ?>";
    window.BASE_URL = "<?= $base_url ?>";
</script>

<header id="navbar" class="fixed top-0 left-0 w-full z-40 transition-all duration-300 bg-white/95 border-b border-gray-100 backdrop-blur-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">

            <div class="flex items-center gap-3 cursor-pointer group" onclick="window.location.href='<?= $base_url ?>index.php'">
                <div class="w-10 h-10 bg-black text-amber-400 rounded-xl flex items-center justify-center font-black text-xl shadow-lg border border-amber-500/20 group-hover:bg-amber-400 group-hover:text-black transition-all duration-500 transform group-hover:rotate-6">
                    S
                </div>
                <div class="flex flex-col leading-none">
                    <span class="text-lg font-black tracking-tighter uppercase text-black group-hover:text-amber-600 transition-colors">Spirit</span>
                    <span class="text-[10px] font-bold tracking-[0.3em] text-amber-500 uppercase group-hover:text-black transition-colors">Guide</span>
                </div>
            </div>

            <nav class="hidden md:flex items-center gap-8">
                <a href="<?= $base_url ?>index.php" class="text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-black py-2 border-b-2 border-transparent hover:border-amber-500 transition-all">Home</a>
                
                <div class="relative group">
                    <button class="flex items-center gap-1 text-xs font-bold uppercase tracking-widest text-gray-500 group-hover:text-black py-2 border-b-2 border-transparent group-hover:border-amber-500 transition-all focus:outline-none">
                        Katalog <i class="fa fa-chevron-down text-[10px] transition-transform group-hover:rotate-180"></i>
                    </button>
                    <div class="absolute top-full left-0 mt-0 w-48 bg-white border border-gray-100 shadow-xl rounded-xl overflow-hidden opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-2 translate-y-4 origin-top-left">
                        <div class="py-2">
                            <a href="<?= $base_url ?>katalog.php?category=Fashion" class="block px-4 py-3 text-xs font-bold text-gray-600 hover:bg-amber-50 hover:text-amber-600 uppercase tracking-wider transition">
                                <i class="fa fa-tshirt w-5 text-center mr-1"></i> Fashion
                            </a>
                            <a href="<?= $base_url ?>katalog.php?category=Food" class="block px-4 py-3 text-xs font-bold text-gray-600 hover:bg-amber-50 hover:text-amber-600 uppercase tracking-wider transition">
                                <i class="fa fa-utensils w-5 text-center mr-1"></i> Food
                            </a>
                            <a href="<?= $base_url ?>katalog.php?category=Aksesoris" class="block px-4 py-3 text-xs font-bold text-gray-600 hover:bg-amber-50 hover:text-amber-600 uppercase tracking-wider transition">
                                <i class="fa fa-gem w-5 text-center mr-1"></i> Aksesoris
                            </a>
                            <a href="<?= $base_url ?>katalog.php?category=Other" class="block px-4 py-3 text-xs font-bold text-gray-600 hover:bg-amber-50 hover:text-amber-600 uppercase tracking-wider transition">
                                <i class="fa fa-box-open w-5 text-center mr-1"></i> Other
                            </a>
                            <div class="border-t border-gray-100 mt-1">
                                <a href="<?= $base_url ?>katalog.php" class="block px-4 py-3 text-[10px] font-bold text-center text-black bg-gray-50 hover:bg-black hover:text-white uppercase tracking-widest transition">
                                    Lihat Semua
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="<?= $base_url ?>about.php" class="text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-black py-2 border-b-2 border-transparent hover:border-amber-500 transition-all">Tentang Kami</a>
                
                <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                    <a href="<?= $base_url ?>admin/index.php" class="px-4 py-1.5 rounded-full bg-black text-amber-400 text-[10px] font-bold uppercase tracking-wide border border-amber-500/30 hover:bg-amber-400 hover:text-black transition-all shadow-sm">
                        Admin
                    </a>
                <?php endif; ?>
            </nav>

            <div class="flex items-center gap-3 sm:gap-5">
                <button onclick="toggleCart()" class="relative p-2 rounded-full hover:bg-gray-100 transition-colors group focus:outline-none">
                    <i class="fa fa-shopping-bag text-xl text-gray-800 group-hover:text-amber-600 transition-transform"></i>
                    <span id="cart-badge-desktop" class="absolute top-0 right-0 bg-amber-500 text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center hidden shadow-md animate-bounce ring-2 ring-white">0</span>
                </button>

                <div class="h-6 w-px bg-gray-200 hidden md:block"></div>

                <?php if (isset($_SESSION['user'])): ?>
                    <div class="hidden md:flex items-center gap-3 pl-1 pr-1 py-1 rounded-full border border-transparent hover:border-gray-200 hover:bg-gray-50 transition-all cursor-pointer group" onclick="window.location.href='<?= $base_url ?>profile.php'">
                        <div class="text-right">
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-0.5">Halo,</p>
                            <p class="text-xs font-black text-gray-900 leading-none group-hover:text-amber-600 transition">
                                <?= htmlspecialchars(explode(' ', $_SESSION['user']['username'])[0]) ?>
                            </p>
                        </div>
                        <div class="w-9 h-9 rounded-full bg-gray-200 overflow-hidden border-2 border-white shadow-sm group-hover:border-amber-400 transition">
                            <img src="https://ui-avatars.com/api/?name=<?= $_SESSION['user']['username'] ?>&background=000&color=fff" class="w-full h-full object-cover">
                        </div>
                    </div>
                <?php else: ?>
                    <div class="hidden md:flex items-center gap-2">
                        <a href="<?= $base_url ?>login.php" class="px-4 py-2 text-xs font-bold uppercase tracking-widest text-gray-900 hover:text-amber-600 transition">Masuk</a>
                        <a href="<?= $base_url ?>register.php" class="px-5 py-2.5 bg-black text-white rounded-xl text-xs font-bold uppercase tracking-widest shadow-lg shadow-amber-500/10 hover:bg-amber-500 hover:text-black hover:shadow-amber-500/30 transition-all hover:-translate-y-0.5">Daftar</a>
                    </div>
                <?php endif; ?>

                <button id="menu-btn" class="md:hidden relative w-10 h-10 flex items-center justify-center focus:outline-none z-50 group">
                    <div id="hamburger-icon" class="flex flex-col justify-between w-6 h-4 transform transition-all duration-300 origin-center overflow-hidden">
                        <span class="bg-black h-[2px] w-7 transform transition-all duration-300 origin-left delay-100"></span>
                        <span class="bg-black h-[2px] w-7 rounded transform transition-all duration-300 delay-75"></span>
                        <span class="bg-black h-[2px] w-7 transform transition-all duration-300 origin-left"></span>
                    </div>
                </button>
            </div>
        </div>
    </div>
</header>

<div id="mobile-menu-overlay" class="fixed inset-0 bg-black/80 z-[90] hidden opacity-0 transition-opacity duration-300 backdrop-blur-sm"></div>

<div id="mobile-menu" class="fixed inset-y-0 right-0 w-[85%] max-w-sm bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out z-[100] flex flex-col h-full border-l border-gray-200">
    
    <?php if (isset($_SESSION['user'])): ?>
        <div class="p-8 bg-black text-white relative overflow-hidden flex-shrink-0">
            <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500 rounded-full opacity-20 blur-2xl"></div>
            
            <div class="relative z-10 flex items-center gap-4">
                <div class="w-16 h-16 rounded-full border-2 border-amber-500 p-1">
                    <img src="https://ui-avatars.com/api/?name=<?= $_SESSION['user']['username'] ?>&background=000&color=fff" class="w-full h-full rounded-full object-cover bg-gray-800">
                </div>
                <div>
                    <p class="text-[10px] uppercase font-bold text-amber-500 tracking-widest mb-1">Welcome</p>
                    <h3 class="text-xl font-black leading-none tracking-tight"><?= htmlspecialchars($_SESSION['user']['username']) ?></h3>
                    <a href="<?= $base_url ?>profile.php" class="text-[10px] font-bold text-gray-400 hover:text-white underline mt-2 inline-block uppercase tracking-wider">Kelola Akun</a>
                </div>
            </div>
            
            <button class="close-menu-action absolute top-4 right-4 text-gray-500 hover:text-white transition p-2 cursor-pointer z-50">
                <i class="fa fa-times text-xl"></i>
            </button>
        </div>
    <?php else: ?>
        <div class="p-8 bg-black text-white flex-shrink-0 relative">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-2xl font-black tracking-tighter uppercase text-white">Spirit<span class="text-amber-500">Guide</span></h3>
                    <p class="text-xs text-gray-400 mt-1">Premium Fashion Store.</p>
                </div>
                <button class="close-menu-action text-gray-500 hover:text-white p-2 cursor-pointer z-50"><i class="fa fa-times text-xl"></i></button>
            </div>
            <div class="flex gap-3">
                <a href="<?= $base_url ?>login.php" class="flex-1 py-3 text-center border border-gray-700 rounded-xl text-xs font-bold uppercase hover:bg-white hover:text-black transition">Masuk</a>
                <a href="<?= $base_url ?>register.php" class="flex-1 py-3 text-center bg-amber-500 text-black rounded-xl text-xs font-bold uppercase hover:bg-amber-400 transition shadow-lg">Daftar</a>
            </div>
        </div>
    <?php endif; ?>

    <div class="flex-1 overflow-y-auto p-6 space-y-2 bg-white">
        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-3 px-3">Menu Utama</p>
        
        <a href="<?= $base_url ?>index.php" class="flex items-center gap-4 p-4 rounded-xl text-gray-800 hover:bg-gray-50 transition group border border-transparent hover:border-gray-100">
            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-amber-500 group-hover:text-black transition">
                <i class="fa fa-home"></i>
            </div>
            <span class="font-bold text-sm uppercase tracking-wider">Home</span>
        </a>
        
        <details class="group border border-transparent hover:border-gray-100 rounded-xl overflow-hidden">
            <summary class="flex items-center justify-between p-4 cursor-pointer list-none bg-white hover:bg-gray-50 transition">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-amber-500 group-hover:text-black transition">
                        <i class="fa fa-th-large"></i>
                    </div>
                    <span class="font-bold text-sm uppercase tracking-wider text-gray-800">Katalog</span>
                </div>
                <i class="fa fa-chevron-down text-xs text-gray-400 transition-transform group-open:rotate-180"></i>
            </summary>
            
            <div class="bg-gray-50 pl-16 pr-4 py-2 space-y-1">
                <a href="<?= $base_url ?>katalog.php?category=Fashion" class="block py-2 text-xs font-bold text-gray-600 hover:text-amber-600 uppercase tracking-wide">
                    • Fashion
                </a>
                <a href="<?= $base_url ?>katalog.php?category=Food" class="block py-2 text-xs font-bold text-gray-600 hover:text-amber-600 uppercase tracking-wide">
                    • Food
                </a>
                <a href="<?= $base_url ?>katalog.php?category=Accessories" class="block py-2 text-xs font-bold text-gray-600 hover:text-amber-600 uppercase tracking-wide">
                    • Aksesoris
                </a>
                <a href="<?= $base_url ?>katalog.php?category=Other" class="block py-2 text-xs font-bold text-gray-600 hover:text-amber-600 uppercase tracking-wide">
                    • Other
                </a>
                <a href="<?= $base_url ?>katalog.php" class="block py-2 text-[10px] font-black text-black hover:text-amber-600 uppercase tracking-widest border-t border-gray-200 mt-2 pt-2">
                    Lihat Semua
                </a>
            </div>
        </details>
        
        <a href="<?= $base_url ?>about.php" class="flex items-center gap-4 p-4 rounded-xl text-gray-800 hover:bg-gray-50 transition group border border-transparent hover:border-gray-100">
            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-amber-500 group-hover:text-black transition">
                <i class="fa fa-info-circle"></i>
            </div>
            <span class="font-bold text-sm uppercase tracking-wider">About Us</span>
        </a>

        <?php if (isset($_SESSION['user'])): ?>
            <div class="my-6 border-t border-gray-100"></div>
            
            <a href="<?= $base_url ?>actions/auth/logout.php" class="flex items-center gap-4 p-4 rounded-xl text-red-500 hover:bg-red-50 transition group">
                <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-500 group-hover:bg-red-500 group-hover:text-white transition">
                    <i class="fa fa-sign-out-alt"></i>
                </div>
                <span class="font-bold text-sm uppercase tracking-wider">Logout</span>
            </a>
        <?php endif; ?>
    </div>
    
    <div class="p-6 border-t border-gray-100 text-center">
        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">© 2026 Spirit Guide</p>
    </div>
</div>

<div id="cart-overlay" onclick="toggleCart()" class="fixed inset-0 bg-black/60 z-[90] hidden opacity-0 transition-opacity duration-300 backdrop-blur-sm"></div>
<div id="cart-sidebar" class="fixed top-0 right-0 h-screen w-full max-w-xs sm:max-w-sm bg-white shadow-2xl translate-x-full transition-transform duration-300 z-[100] flex flex-col border-l border-gray-100">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white sticky top-0 z-10">
        <div class="flex items-center gap-3">
            <i class="fa fa-shopping-bag text-amber-500 text-xl"></i>
            <h3 class="font-black uppercase tracking-tight text-lg">Keranjang</h3>
        </div>
        <button onclick="toggleCart()" class="w-8 h-8 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-400 hover:bg-black hover:text-white transition">
            <i class="fa fa-times"></i>
        </button>
    </div>
    <div id="cart-items-container" class="flex-1 overflow-y-auto p-6 space-y-4 custom-scrollbar bg-gray-50/30"></div>
    <div class="p-6 border-t border-gray-100 bg-white shadow-lg z-10">
        <div class="flex justify-between items-end mb-4">
            <span class="text-xs font-bold uppercase text-gray-400 tracking-widest">Total</span>
            <span id="cart-total" class="text-2xl font-black text-gray-900">Rp 0</span>
        </div>
        <a href="<?= $base_url ?>checkout.php" class="block w-full bg-black text-white text-center py-4 rounded-xl text-xs font-black uppercase tracking-[0.2em] hover:bg-amber-500 hover:text-black hover:shadow-xl hover:shadow-amber-500/20 hover:-translate-y-1 transition-all">Checkout</a>
    </div>
</div>
<div class="h-20"></div>

<script src="<?= $base_url ?>assets/js/cart.js?v=<?= time() ?>"></script>
<script src="<?= $base_url ?>assets/js/navbar.js?v=<?= time() ?>"></script>
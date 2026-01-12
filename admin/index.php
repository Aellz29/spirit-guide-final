<?php
session_start();
require "../config/db.php";

// 1. CEK AKSES ADMIN
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$username = $_SESSION['user']['username'] ?? 'Admin';

// 2. LOGIC STATISTIK (Sesuai file dashboard_admin.php lo)
// Hitung Total Produk
$totalProducts = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];

// Hitung Total User
$totalUsers = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];

// Hitung Total Review (Cek tabel dulu biar aman)
$totalReviews = 0;
$checkRev = $conn->query("SHOW TABLES LIKE 'product_reviews'");
if($checkRev->num_rows > 0) {
    $totalReviews = $conn->query("SELECT COUNT(*) as total FROM product_reviews")->fetch_assoc()['total'];
}

// Hitung Total Order (Cuma COUNT, gak pake SUM duit biar gak error)
$totalOrders = 0;
$checkOrd = $conn->query("SHOW TABLES LIKE 'orders'");
if($checkOrd->num_rows > 0) {
    $totalOrders = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'];
}

// 3. DATA UNTUK GRAFIK (STOK PER KATEGORI)
$catData = $conn->query("SELECT category, SUM(stock) as total_stock FROM products GROUP BY category");
$categories = [];
$stocks = [];
while($row = $catData->fetch_assoc()) {
    $categories[] = $row['category'];
    $stocks[] = $row['total_stock'];
}

// 4. DATA TABEL (5 PRODUK TERBARU)
$recentProducts = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT 5");
?>

<?php include '../partials/header.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="flex h-screen bg-gray-900 text-white font-sans overflow-hidden">
    
    <?php include '../partials/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto p-4 md:p-8 ml-0 lg:ml-72 transition-all duration-300">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-white">Dashboard Overview</h1>
                <p class="text-gray-400 text-sm mt-1">Welcome back, <span class="text-amber-500 font-bold"><?= htmlspecialchars($username) ?></span></p>
            </div>
            <div class="flex items-center gap-3 bg-gray-800 p-2 rounded-xl border border-gray-700">
                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-amber-400 to-orange-500 flex items-center justify-center text-black font-bold text-xs">
                    <?= strtoupper(substr($username, 0, 1)) ?>
                </div>
                <span class="text-xs font-bold pr-2">Administrator</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            
            <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-lg group hover:border-amber-500/50 transition">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Total Produk</p>
                        <h3 class="text-3xl font-black text-white mt-1"><?= $totalProducts ?></h3>
                    </div>
                    <div class="p-3 bg-gray-700 rounded-xl group-hover:bg-amber-500 group-hover:text-black transition">
                        <i class="fa fa-box text-lg"></i>
                    </div>
                </div>
                <div class="w-full bg-gray-700 h-1 rounded-full overflow-hidden">
                    <div class="bg-amber-500 h-full" style="width: 70%"></div>
                </div>
            </div>

            <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-lg group hover:border-blue-500/50 transition">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Total User</p>
                        <h3 class="text-3xl font-black text-white mt-1"><?= $totalUsers ?></h3>
                    </div>
                    <div class="p-3 bg-gray-700 rounded-xl group-hover:bg-blue-500 group-hover:text-white transition">
                        <i class="fa fa-users text-lg"></i>
                    </div>
                </div>
                <div class="w-full bg-gray-700 h-1 rounded-full overflow-hidden">
                    <div class="bg-blue-500 h-full" style="width: 50%"></div>
                </div>
            </div>

            <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-lg group hover:border-green-500/50 transition">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Total Pesanan</p>
                        <h3 class="text-3xl font-black text-white mt-1"><?= $totalOrders ?></h3>
                    </div>
                    <div class="p-3 bg-gray-700 rounded-xl group-hover:bg-green-500 group-hover:text-white transition">
                        <i class="fa fa-shopping-cart text-lg"></i>
                    </div>
                </div>
                <div class="w-full bg-gray-700 h-1 rounded-full overflow-hidden">
                    <div class="bg-green-500 h-full" style="width: 40%"></div>
                </div>
            </div>

            <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-lg group hover:border-purple-500/50 transition">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Total Review</p>
                        <h3 class="text-3xl font-black text-white mt-1"><?= $totalReviews ?></h3>
                    </div>
                    <div class="p-3 bg-gray-700 rounded-xl group-hover:bg-purple-500 group-hover:text-white transition">
                        <i class="fa fa-star text-lg"></i>
                    </div>
                </div>
                <div class="w-full bg-gray-700 h-1 rounded-full overflow-hidden">
                    <div class="bg-purple-500 h-full" style="width: 80%"></div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
            <div class="lg:col-span-2 bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-lg">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6">Stok per Kategori</h3>
                <div class="relative h-64 w-full">
                    <canvas id="stockChart"></canvas>
                </div>
            </div>

            <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-lg">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6">Ratio Data</h3>
                <div class="relative h-64 w-full flex items-center justify-center">
                    <canvas id="ratioChart"></canvas>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-2xl border border-gray-700 shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-700 flex justify-between items-center">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest">Produk Terbaru</h3>
                <a href="products.php" class="text-amber-500 text-xs font-bold hover:underline">Lihat Semua -></a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-400">
                    <thead class="bg-gray-700/50 text-gray-200 uppercase text-xs font-bold">
                        <tr>
                            <th class="px-6 py-4">Produk</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Harga</th>
                            <th class="px-6 py-4">Stok</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        <?php while($p = $recentProducts->fetch_assoc()): ?>
                        <tr class="hover:bg-gray-700/30 transition">
                            <td class="px-6 py-4 flex items-center gap-3">
                                <img src="../<?= $p['image'] ?>" class="w-10 h-10 rounded-lg object-cover bg-gray-700" onerror="this.src='../assets/img/SpiritGuide.jpg'">
                                <span class="font-bold text-white"><?= htmlspecialchars($p['title']) ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase bg-gray-700 border border-gray-600">
                                    <?= $p['category'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-amber-500 font-bold">Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
                            <td class="px-6 py-4"><?= $p['stock'] ?> unit</td>
                            <td class="px-6 py-4 text-center">
                                <?php if($p['is_flash_sale']): ?>
                                    <span class="text-red-500 font-bold text-xs"><i class="fa fa-bolt"></i> Flash</span>
                                <?php else: ?>
                                    <span class="text-green-500 font-bold text-xs">Regular</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

<script>
    const categories = <?= json_encode($categories) ?>;
    const stocks = <?= json_encode($stocks) ?>;

    // Bar Chart
    new Chart(document.getElementById('stockChart'), {
        type: 'bar',
        data: {
            labels: categories,
            datasets: [{
                label: 'Stok',
                data: stocks,
                backgroundColor: '#f59e0b',
                borderRadius: 4,
                barThickness: 30
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#888' }, border: { display: false } },
                x: { grid: { display: false }, ticks: { color: '#888' }, border: { display: false } }
            }
        }
    });

    // Doughnut Chart
    new Chart(document.getElementById('ratioChart'), {
        type: 'doughnut',
        data: {
            labels: ['Produk', 'User', 'Review'],
            datasets: [{ 
                data: [<?= $totalProducts ?>, <?= $totalUsers ?>, <?= $totalReviews ?>], 
                backgroundColor: ['#fbbf24', '#3b82f6', '#a855f7'], 
                borderColor: '#1f2937', borderWidth: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: { legend: { position: 'bottom', labels: { color: '#aaa', padding: 20, usePointStyle: true } } }
        }
    });
</script>
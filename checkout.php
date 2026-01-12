<?php
session_start();
require "config/db.php";

// 1. CEK LOGIN (OPTIONAL: Guest Boleh Masuk)
$isLoggedIn = isset($_SESSION['user']);
$userID = $isLoggedIn ? $_SESSION['user']['id'] : null;

// 2. AMBIL DATA USER (Untuk Auto-Fill Form Member)
$fillName = ''; 
$fillPhone = ''; 
$fillAddress = '';

if ($isLoggedIn && $userID) {
    // Sesuaikan query ini dengan nama kolom di database kamu
    $stmt = $conn->prepare("SELECT username, full_name, phone, address_full FROM users WHERE id = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user) {
        $fillName = !empty($user['full_name']) ? $user['full_name'] : $user['username'];
        $fillPhone = !empty($user['phone']) ? $user['phone'] : '';
        $fillAddress = !empty($user['address_full']) ? $user['address_full'] : '';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Spirit Guide</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #F9FAFB; }
        
        /* Custom Input Style */
        .input-field {
            width: 100%; 
            background-color: #fff; 
            border: 1px solid #E5E7EB;
            border-radius: 0.75rem; 
            padding: 0.75rem 1rem; 
            font-size: 0.875rem;
            color: #111827; 
            outline: none; 
            transition: all 0.2s;
        }
        .input-field:focus { 
            border-color: #F59E0B; 
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1); 
        }
        .input-field:disabled {
            background-color: #F3F4F6;
            color: #9CA3AF;
            cursor: not-allowed;
        }
        
        input[type="radio"]:checked + div { border-color: #f59e0b; background-color: #fffbeb; }
        input[type="radio"]:checked + div .check-icon { opacity: 1; transform: scale(1); }
        
        .btn-disabled { opacity: 0.5; cursor: not-allowed; background-color: #4B5563 !important; }
        
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 5px; }
    </style>
</head>
<body class="antialiased text-gray-900">

    <?php include 'partials/navbar.php'; ?>

    <main class="pt-32 pb-20 px-4 md:px-6 min-h-screen">
        <div class="max-w-6xl mx-auto">
            
            <div class="mb-10 flex flex-col md:flex-row justify-between items-end border-b border-gray-200 pb-6 gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-black uppercase tracking-tighter text-gray-900">Checkout</h1>
                    <p class="text-sm text-gray-500 font-bold tracking-widest uppercase mt-1">Selesaikan pesanan Anda</p>
                </div>
                
                <?php if($isLoggedIn): ?>
                <div class="flex items-center gap-2 bg-green-50 px-4 py-2 rounded-full border border-green-100">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-xs font-bold text-green-700 uppercase tracking-wider">Member Verified</span>
                </div>
                <?php else: ?>
                <div class="flex items-center gap-2 bg-gray-100 px-4 py-2 rounded-full border border-gray-200">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Guest Mode</span>
                </div>
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12 relative">
                
                <div class="lg:col-span-2 space-y-8">
                    
                    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center font-bold text-xs">1</div>
                            <h2 class="text-lg font-bold uppercase tracking-widest">Alamat Pengiriman</h2>
                        </div>

                        <form id="checkoutForm" class="space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Nama Penerima</label>
                                    <input type="text" name="nama" id="nama" class="input-field" value="<?= htmlspecialchars($fillName) ?>" required placeholder="Nama Lengkap">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Nomor WhatsApp</label>
                                    <input type="tel" name="whatsapp" id="whatsapp" class="input-field" value="<?= htmlspecialchars($fillPhone) ?>" required placeholder="08..." pattern="[0-9]+">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Alamat Lengkap</label>
                                <textarea name="address" id="address" rows="2" class="input-field resize-none" placeholder="Jalan, No Rumah, RT/RW" required><?= htmlspecialchars($fillAddress) ?></textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Provinsi Tujuan</label>
                                    <select id="provinceSelect" class="input-field cursor-pointer">
                                        <option value="">-- Pilih Provinsi --</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Kota / Kabupaten</label>
                                    <select id="citySelect" name="city" class="input-field cursor-pointer" disabled>
                                        <option value="">-- Pilih Kota --</option>
                                    </select>
                                </div>
                            </div>

                            <input type="hidden" name="full_province" id="full_province">
                            <input type="hidden" name="full_city" id="full_city">
                            <input type="hidden" name="shipping_cost" id="shipping_cost" value="0">
                        </form>
                    </div>

                    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center font-bold text-xs">2</div>
                            <h2 class="text-lg font-bold uppercase tracking-widest">Metode Pembayaran</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="cursor-pointer relative group">
                                <input type="radio" name="payment" value="QRIS" class="peer sr-only" checked>
                                <div class="p-5 rounded-2xl border border-gray-200 hover:border-amber-500/50 transition-all bg-white h-full flex flex-col justify-between group-hover:shadow-md">
                                    <div class="flex justify-between items-start mb-2">
                                        <i class="fa fa-qrcode text-2xl text-gray-400 peer-checked:text-amber-500 transition-colors"></i>
                                        <div class="check-icon opacity-0 transition-all text-amber-500"><i class="fa fa-check-circle text-lg"></i></div>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 text-sm uppercase">QRIS Instant</h3>
                                        <p class="text-[10px] text-gray-500 mt-1">Gopay, OVO, Dana</p>
                                    </div>
                                </div>
                            </label>

                            <label class="cursor-pointer relative group">
                                <input type="radio" name="payment" value="Bank Transfer" class="peer sr-only">
                                <div class="p-5 rounded-2xl border border-gray-200 hover:border-amber-500/50 transition-all bg-white h-full flex flex-col justify-between group-hover:shadow-md">
                                    <div class="flex justify-between items-start mb-2">
                                        <i class="fa fa-building-columns text-2xl text-gray-400 peer-checked:text-amber-500 transition-colors"></i>
                                        <div class="check-icon opacity-0 transition-all text-amber-500"><i class="fa fa-check-circle text-lg"></i></div>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 text-sm uppercase">Transfer Bank</h3>
                                        <p class="text-[10px] text-gray-500 mt-1">BCA / Mandiri Manual</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="sticky top-28 bg-white p-6 md:p-8 rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100">
                        <h2 class="text-lg font-bold uppercase tracking-widest mb-6 pb-4 border-b border-gray-100">Ringkasan</h2>
                        
                        <div id="checkout-items" class="space-y-4 mb-6 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                            </div>

                        <div class="space-y-3 pt-4 border-t border-gray-100 text-sm">
                            <div class="flex justify-between text-gray-500">
                                <span>Subtotal</span>
                                <span id="subtotal-display" class="font-bold text-gray-900">Rp 0</span>
                            </div>
                            <div class="flex justify-between text-gray-500">
                                <span>Ongkos Kirim</span>
                                <span id="ongkir-display" class="font-bold text-amber-600">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center pt-4 mt-2 border-t border-gray-100">
                                <span class="font-bold text-gray-900 text-base">Total Bayar</span>
                                <span id="total-display" class="font-black text-2xl text-gray-900 tracking-tight">Rp 0</span>
                            </div>
                        </div>

                        <button type="submit" form="checkoutForm" id="submit-btn" disabled class="w-full mt-8 bg-black hover:bg-gray-800 text-white font-bold py-4 rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 btn-disabled">
                            <span id="btn-text">Buat Pesanan</span>
                            <i class="fa fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <div id="qris-modal" class="fixed inset-0 z-[999] hidden flex items-center justify-center px-4 bg-black/80 backdrop-blur-sm transition-all p-4">
        <div class="bg-white w-full max-w-md max-h-[90vh] overflow-y-auto rounded-3xl p-6 relative shadow-2xl animate-[fadeIn_0.3s_ease-out]">
            <button onclick="closeModal('qris-modal')" class="absolute top-4 right-4 text-gray-300 hover:text-black transition"><i class="fa fa-times text-xl"></i></button>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl"><i class="fa fa-qrcode"></i></div>
                <h3 class="text-xl font-black uppercase tracking-tight mb-2">Scan QRIS</h3>
                <p class="text-xs text-gray-500 mb-6">Upload bukti bayar untuk lanjut.</p>
                
                <div class="bg-gray-50 p-4 rounded-2xl mb-6 border-2 border-dashed border-gray-200">
                    <img src="assets/img/Qris-Spiritguide.jpeg" class="w-full h-auto rounded-lg mix-blend-multiply opacity-90" onerror="this.src='https://placehold.co/300x300?text=QRIS+IMAGE'">
                </div>
                
                <div class="text-left mb-4">
                    <label class="block text-[10px] font-bold uppercase mb-2 text-gray-400">Upload Bukti</label>
                    <input type="file" id="proof_qris" accept="image/*" class="w-full text-xs file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-amber-50 file:text-amber-600 hover:file:bg-amber-100 cursor-pointer">
                </div>
                <button onclick="finalSubmitWhatsApp('proof_qris')" class="w-full bg-black text-white py-3 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-amber-500 hover:text-black transition">Konfirmasi WA</button>
            </div>
        </div>
    </div>

    <div id="bank-modal" class="fixed inset-0 z-[999] hidden flex items-center justify-center px-4 bg-black/80 backdrop-blur-sm transition-all p-4">
        <div class="bg-white w-full max-w-md max-h-[90vh] overflow-y-auto rounded-3xl p-6 relative shadow-2xl animate-[fadeIn_0.3s_ease-out]">
            <button onclick="closeModal('bank-modal')" class="absolute top-4 right-4 text-gray-300 hover:text-black transition"><i class="fa fa-times text-xl"></i></button>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl"><i class="fa fa-building-columns"></i></div>
                <h3 class="text-xl font-black uppercase tracking-tight mb-2">Transfer Manual</h3>
                
                <div class="bg-gray-50 p-5 rounded-2xl mb-6 border border-gray-100">
                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Silakan transfer ke:</p>
                    <p id="modal-bank-detail" class="text-sm font-bold text-gray-900">Seabank - 123456789 (Spirit Guide)</p>
                    <p class="text-[10px] text-gray-500 mt-1">A/N Admin Spirit Guide</p>
                </div>

                <div class="text-left mb-4">
                    <label class="block text-[10px] font-bold uppercase mb-2 text-gray-400">Upload Bukti</label>
                    <input type="file" id="proof_bank" accept="image/*" class="w-full text-xs file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-black file:text-white hover:file:bg-gray-800 cursor-pointer">
                </div>
                <button onclick="finalSubmitWhatsApp('proof_bank')" class="w-full bg-black text-white py-3 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-amber-500 hover:text-black transition">Konfirmasi WA</button>
            </div>
        </div>
    </div>

    <?php include 'partials/footer.php'; ?>
    
    <script>window.USER_ID = "<?= $userID ?: 'guest' ?>";</script>
    <script src="assets/js/cart.js"></script>
    <script src="assets/js/checkout.js?v=<?= time() ?>"></script>

    <script>
        const userID = "<?= $_SESSION['user']['id'] ?>";
        let cart = JSON.parse(localStorage.getItem('cart_' + userID)) || [];
        let subtotal = 0;
        let ongkir = 0;
        let namaKota = "";

        // 1. INISIALISASI
        $(document).ready(function() {
            renderCart();
            
            // Aktifkan Select2 pada dropdown kota
            $('#kota_id').select2({
                placeholder: "Ketik nama kota...",
                width: '100%'
            });

            // Load data kota saat halaman dibuka
            loadCities();
        });

        // 2. FUNGSI LOAD KOTA (AJAX ke RajaOngkir)
        function loadCities() {
            $.ajax({
                url: 'actions/api/get_cities.php', // Pastikan file ini ada & API Key benar
                type: 'GET',
                success: function(response) {
                    // Cek struktur response RajaOngkir
                    if(response.rajaongkir && response.rajaongkir.results) {
                        let data = response.rajaongkir.results;
                        let options = '<option value="" disabled selected>-- Pilih Kota --</option>';
                        data.forEach(city => {
                            options += `<option value="${city.city_id}" data-nama="${city.type} ${city.city_name}">${city.type} ${city.city_name}</option>`;
                        });
                        $('#kota_id').html(options);
                    } else {
                        console.error("Format Data Salah:", response);
                    }
                },
                error: function() {
                    alert("Gagal memuat data kota. Pastikan internet lancar.");
                }
            });
        }

        // 3. FUNGSI HITUNG ONGKIR (AJAX JNE)
        function hitungOngkirAPI() {
            let cityID = $('#kota_id').val();
            namaKota = $('#kota_id').find(':selected').data('nama');
            
            if(!cityID) return;

            // UI Loading
            $('#loading-ongkir').removeClass('hidden');
            $('#btn-wa').prop('disabled', true);
            $('#ongkir-display').text('Menghitung...');
            
            $.ajax({
                url: 'actions/api/get_cost.php', // Pastikan file ini ada
                type: 'POST',
                data: { destination: cityID }, // Default berat 1kg di PHP
                success: function(response) {
                    $('#loading-ongkir').addClass('hidden');
                    
                    if(response.rajaongkir && response.rajaongkir.results) {
                        let costs = response.rajaongkir.results[0].costs;
                        
                        // Cari layanan REG (Reguler) atau ambil yg pertama jika tidak ada
                        let layanan = costs.find(c => c.service === "REG") || costs[0];
                        
                        if(layanan) {
                            ongkir = layanan.cost[0].value;
                            let etd = layanan.cost[0].etd;
                            
                            $('#ongkir-display').html(`Rp ${new Intl.NumberFormat('id-ID').format(ongkir)} <span class="text-[10px] text-gray-500">(${etd} Hari)</span>`);
                            updateTotal();
                            $('#btn-wa').prop('disabled', false); // Aktifkan tombol pesan
                        } else {
                            alert("Maaf, layanan pengiriman tidak tersedia untuk kota ini.");
                            $('#ongkir-display').text('Tidak tersedia');
                        }
                    } else {
                        alert("Gagal mengambil biaya ongkir.");
                    }
                },
                error: function() {
                    $('#loading-ongkir').addClass('hidden');
                    alert("Error koneksi server ongkir.");
                }
            });
        }

        // 4. RENDER KERANJANG BELANJA
        function renderCart() {
            const container = document.getElementById('cart-summary');
            if (cart.length === 0) {
                container.innerHTML = '<div class="text-center py-6 bg-gray-50 rounded-xl border border-dashed border-gray-300"><p class="text-xs font-bold text-gray-400">Keranjang Kosong</p></div>';
                return;
            }

            let html = '';
            subtotal = 0;
            cart.forEach(item => {
                let t = item.price * item.qty;
                subtotal += t;
                html += `
                    <div class="flex gap-3 items-center text-sm border-b border-gray-50 pb-2 last:border-0">
                        <div class="w-8 h-8 rounded-md bg-gray-100 flex items-center justify-center font-bold text-xs text-gray-500">${item.qty}x</div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-gray-900 truncate">${item.title}</p>
                            <p class="text-[10px] text-gray-400">@ Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</p>
                        </div>
                        <div class="font-bold text-gray-900">Rp ${new Intl.NumberFormat('id-ID').format(t)}</div>
                    </div>`;
            });
            container.innerHTML = html;
            updateTotal();
        }

        // 5. UPDATE TOTAL HARGA
        function updateTotal() {
            document.getElementById('subtotal-display').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
            document.getElementById('total-display').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal + ongkir);
        }

        // 6. PROSES KE WHATSAPP
        function prosesKeWA() {
            const nama = $('#nama').val();
            const nohp = $('#nohp').val();
            const alamat = $('#alamat').val();
            const catatan = $('#catatan').val();
            // Ambil metode pembayaran yang dipilih
            const metodeBayar = document.querySelector('input[name="payment"]:checked').value;
            
            // GANTI NOMOR ADMIN DI SINI
            const nomorAdmin = "6289656499186"; 

            // Validasi Input
            if(!nama || !nohp || !alamat || !namaKota || ongkir === 0) {
                alert("Mohon lengkapi data pengiriman dan pilih kota tujuan!");
                return;
            }

            // Susun Pesan WhatsApp
            let pesan = `Halo Admin *Spirit Guide*, saya mau pesan:%0a%0a`;
            pesan += `👤 *DATA PENERIMA*%0a`;
            pesan += `Nama: ${nama}%0a`;
            pesan += `HP: ${nohp}%0a`;
            pesan += `Alamat: ${alamat}, ${namaKota}%0a%0a`;
            
            pesan += `📦 *DETAIL PESANAN*%0a`;
            cart.forEach(item => {
                pesan += `- ${item.title} (${item.qty}x) @ Rp ${new Intl.NumberFormat('id-ID').format(item.price)}%0a`;
            });
            
            pesan += `%0a💳 *PEMBAYARAN*: ${metodeBayar}%0a`;
            if(catatan) pesan += `📝 Catatan: ${catatan}%0a`;
            
            pesan += `%0a💰 *TOTAL TAGIHAN*%0a`;
            pesan += `Subtotal: Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}%0a`;
            pesan += `Ongkir (JNE): Rp ${new Intl.NumberFormat('id-ID').format(ongkir)}%0a`;
            pesan += `*GRAND TOTAL: Rp ${new Intl.NumberFormat('id-ID').format(subtotal + ongkir)}*%0a`;
            
            pesan += `%0aMohon info nomor rekening/QRIS untuk pembayaran. Terima kasih!`;

            // Buka Link WA
            window.open(`https://wa.me/${nomorAdmin}?text=${pesan}`, '_blank');
        }
    </script>

</body>
</html>
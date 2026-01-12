/**
 * CHECKOUT JS FINAL - FIX 500 ERROR & PATH
 */

const shippingData = {
    "DKI Jakarta": { "Jakarta Pusat": 10000, "Jakarta Barat": 10000, "Jakarta Selatan": 12000, "Jakarta Timur": 12000, "Jakarta Utara": 12000 },
    "Jawa Barat": { "Bandung": 15000, "Bogor": 15000, "Depok": 12000, "Bekasi": 12000, "Cimahi": 16000, "Cirebon": 20000, "Sukabumi": 18000 },
    "Banten": { "Tangerang": 12000, "Tangerang Selatan": 12000, "Serang": 18000 },
    "Jawa Tengah": { "Semarang": 22000, "Solo": 22000, "Yogyakarta": 22000 },
    "Jawa Timur": { "Surabaya": 25000, "Malang": 27000 },
    "Bali": { "Denpasar": 35000, "Badung": 37000 }
};

let pendingOrderData = null;
let currentShippingCost = 0;

function getCartKey() {
    const uid = (typeof window.USER_ID !== 'undefined' && window.USER_ID !== '') ? window.USER_ID : 'guest';
    return 'sg_cart_' + uid;
}

document.addEventListener('DOMContentLoaded', () => {
    initStaticShipping();
    loadCheckout();
});

function initStaticShipping() {
    const provSelect = document.getElementById('provinceSelect');
    const citySelect = document.getElementById('citySelect');
    if(!provSelect || !citySelect) return;

    for (let prov in shippingData) {
        let opt = document.createElement('option');
        opt.value = prov;
        opt.textContent = prov;
        provSelect.appendChild(opt);
    }

    provSelect.addEventListener('change', function() {
        citySelect.innerHTML = '<option value="">-- Pilih Kota --</option>';
        citySelect.disabled = true;
        currentShippingCost = 0;
        
        document.getElementById('shipping_cost').value = 0;
        document.getElementById('full_province').value = this.value; 
        document.getElementById('full_city').value = '';
        
        updateTotalSummary();
        toggleSubmitButton(false);

        if (this.value && shippingData[this.value]) {
            const cities = shippingData[this.value];
            for (let city in cities) {
                let opt = document.createElement('option');
                opt.value = cities[city]; 
                opt.textContent = `${city} - Rp ${cities[city].toLocaleString('id-ID')}`;
                opt.setAttribute('data-name', city);
                citySelect.appendChild(opt);
            }
            citySelect.disabled = false;
        }
    });

    citySelect.addEventListener('change', function() {
        const cost = parseInt(this.value) || 0;
        const cityName = this.options[this.selectedIndex].getAttribute('data-name');
        
        currentShippingCost = cost;
        document.getElementById('shipping_cost').value = cost; 
        document.getElementById('full_city').value = cityName; 
        
        updateTotalSummary();
        toggleSubmitButton(true);
    });
}

function toggleSubmitButton(enable) {
    const btn = document.getElementById('submit-btn');
    if(!btn) return;
    if(enable) { btn.disabled = false; btn.classList.remove('btn-disabled'); } 
    else { btn.disabled = true; btn.classList.add('btn-disabled'); }
}

function loadCheckout() {
    const cart = JSON.parse(localStorage.getItem(getCartKey()) || '[]');
    const container = document.getElementById('checkout-items');
    if (!container) return;
    
    // Cek jika kosong
    if (cart.length === 0) {
        container.innerHTML = `<div class="text-center py-6 bg-gray-50 rounded-xl border border-dashed border-gray-200"><p class="text-xs font-bold text-gray-400">Keranjang Kosong</p></div>`;
        updateTotalSummary();
        toggleSubmitButton(false);
        return;
    }

    let html = '';
    
    // Looping barang
    cart.forEach((item) => {
        let price = parseFloat(item.price);
        let originalPrice = parseFloat(item.originalPrice) || 0; // Ambil harga asli
        let totalItemPrice = price * item.qty;
        
        // --- LOGIKA TAMPILAN HARGA CORET ---
        let displayHarga = '';

        // Jika harga asli LEBIH BESAR dari harga jual, berarti DISKON
        if (originalPrice > price) {
            displayHarga = `
                <div class="flex flex-col mt-1">
                    <span class="text-[10px] text-gray-400 line-through decoration-red-400 mb-0.5">
                        Rp ${originalPrice.toLocaleString('id-ID')}
                    </span>
                    <span class="text-[10px] font-bold text-gray-900">
                        ${item.qty} x Rp ${price.toLocaleString('id-ID')}
                    </span>
                </div>
            `;
        } else {
            // Tampilan Normal (Tidak Diskon)
            displayHarga = `
                <p class="text-[10px] text-gray-500 mt-1">
                    ${item.qty} x Rp ${price.toLocaleString('id-ID')}
                </p>
            `;
        }
        // ------------------------------------

        html += `
            <div class="flex items-start gap-4 border-b border-gray-100 pb-4 last:border-0 p-2 mb-2">
                <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden shrink-0 border border-gray-200">
                    <img src="${item.img}" class="w-full h-full object-cover" onerror="this.src='assets/img/no-image.jpg'">
                </div>
                
                <div class="flex-1 min-w-0 pt-0.5">
                    <h4 class="text-[11px] font-bold uppercase text-gray-800 line-clamp-2 leading-tight">${item.title}</h4>
                    ${displayHarga}
                </div>
                
                <div class="text-right pt-0.5">
                    <p class="text-[11px] font-bold text-gray-900">Rp ${totalItemPrice.toLocaleString('id-ID')}</p>
                </div>
            </div>`;
    });
    
    container.innerHTML = html;
    updateTotalSummary();
}

function updateTotalSummary() {
    const cart = JSON.parse(localStorage.getItem(getCartKey()) || '[]');
    let subtotal = 0;
    cart.forEach(item => { subtotal += parseFloat(item.price) * item.qty; });
    const grandTotal = subtotal + currentShippingCost;

    if(document.getElementById('subtotal-display')) document.getElementById('subtotal-display').innerText = `Rp ${subtotal.toLocaleString('id-ID')}`;
    if(document.getElementById('ongkir-display')) document.getElementById('ongkir-display').innerText = `Rp ${currentShippingCost.toLocaleString('id-ID')}`;
    if(document.getElementById('total-display')) document.getElementById('total-display').innerText = `Rp ${grandTotal.toLocaleString('id-ID')}`;
}

// === SUBMIT FORM ===
const checkoutForm = document.getElementById('checkoutForm');
if (checkoutForm) {
    checkoutForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const cartData = localStorage.getItem(getCartKey());
        if (!cartData || JSON.parse(cartData).length === 0) return alert("Keranjang kosong!");
        
        const inName = document.getElementById('nama').value;
        const inWa = document.getElementById('whatsapp').value;
        const inAddr = document.getElementById('address').value;
        const inCity = document.getElementById('full_city').value;
        const inShip = document.getElementById('shipping_cost').value;
        
        if(!inName || !inWa || !inAddr || !inCity || inShip == 0) {
            return alert("Mohon lengkapi alamat dan pilih kota tujuan!");
        }

        const submitBtn = document.getElementById('submit-btn');
        submitBtn.disabled = true;
        document.getElementById('btn-text').innerText = 'Memproses...';

        const paymentRadio = document.querySelector('input[name="payment"]:checked');
        const paymentType = paymentRadio ? paymentRadio.value : 'QRIS';
        
        const formData = new FormData(this);
        formData.append('cart_data', cartData); 
        formData.append('payment', paymentType);

        // FETCH KE FOLDER ACTIONS/ORDER/
        fetch('actions/order/checkout_process.php', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                // Simpan Data
                pendingOrderData = { 
                    result: result, 
                    cart: JSON.parse(cartData), 
                    finalPayment: paymentType,
                    customer: { 
                        name: inName, wa: inWa, 
                        address: inAddr, city: inCity, 
                        prov: document.getElementById('full_province').value 
                    }
                };
                
                // Buka Modal
                const modalId = (paymentType === 'QRIS') ? 'qris-modal' : 'bank-modal';
                const modal = document.getElementById(modalId);
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } else {
                alert("Gagal: " + result.message);
                submitBtn.disabled = false;
                document.getElementById('btn-text').innerText = 'Buat Pesanan';
            }
        })
        .catch(err => { 
            console.error(err);
            // Pesan Error Lebih Detail
            alert("Error koneksi server. Cek folder actions/order/ dan file checkout_process.php"); 
            submitBtn.disabled = false;
            document.getElementById('btn-text').innerText = 'Buat Pesanan';
        });
    });
}

// === UPLOAD & WA ===
window.finalSubmitWhatsApp = async function(inputId) {
    const fileInput = document.getElementById(inputId);
    const btn = event.target;
    
    if (!pendingOrderData) return alert("Data hilang. Refresh halaman.");

    btn.disabled = true;
    btn.innerText = "Mengirim...";

    const { result, cart, finalPayment, customer } = pendingOrderData;
    const formData = new FormData();
    formData.append('order_id', result.order_id);
    if(fileInput.files[0]) formData.append('bukti', fileInput.files[0]);

    try {
        await fetch('actions/order/checkout_process.php?action=upload', { method: 'POST', body: formData });
        
        const totalBayar = parseInt(result.total) || 0; 
        let listBarang = cart.map(item => `- ${item.title} (${item.qty}x)`).join('\n');
        
        // PAKE ID ASLI DARI DATABASE
        let rawText = `*ORDER BARU #${result.order_id}*\n` +
                      `------------------\n` +
                      `👤 *Penerima:* ${customer.name}\n` +
                      `📞 *WA:* ${customer.wa}\n` +
                      `📍 *Alamat:* ${customer.address}, ${customer.city}, ${customer.prov}\n` +
                      `------------------\n` +
                      `📦 *Pesanan:*\n${listBarang}\n` +
                      `------------------\n` +
                      `🚚 *Ongkir:* Rp ${currentShippingCost.toLocaleString('id-ID')}\n` +
                      `💰 *TOTAL TRANSFER:* Rp ${(totalBayar).toLocaleString('id-ID')}\n` +
                      `💳 *Metode:* ${finalPayment}\n\n` +
                      `*Status:* Bukti bayar terlampir. Mohon diproses!`;

        localStorage.removeItem(getCartKey());
        if (window.updateCartBadge) window.updateCartBadge();
        
        // GANTI NOMOR ADMIN
        window.location.href = `https://wa.me/628971566371?text=${encodeURIComponent(rawText)}`;

    } catch (err) {
        console.error(err);
        alert("Gagal kirim data.");
        btn.disabled = false;
        btn.innerText = "Konfirmasi WA";
    }
}

window.closeModal = function(id) {
    const modal = document.getElementById(id);
    if(modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); }
}
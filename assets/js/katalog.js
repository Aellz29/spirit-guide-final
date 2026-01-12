// File: assets/js/katalog.js

// 1. FUNGSI BUKA MODAL DETAIL
function openModal(data) {
    // Support kalau data dikirim sebagai object langsung (dari PHP json_encode)
    // Atau kalau dikirim parameter terpisah (legacy support)
    let img, title, priceFormatted, originalPriceFormatted, desc, stock, id, rawPrice, isFlash, isMember;

    if (typeof data === 'object') {
        ({img, title, price: priceFormatted, original: originalPriceFormatted, desc, stock, id, rawPrice, isFlash, isMember} = data);
    } else {
        // Fallback kalau pake cara lama
        [img, title, priceFormatted, originalPriceFormatted, desc, stock, id, rawPrice, isFlash, isMember] = arguments;
    }

    const modal = document.getElementById('productModal');
    if (!modal) return;

    // Isi Konten Modal
    document.getElementById('modalImg').src = img;
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalPrice').textContent = 'Rp ' + priceFormatted;
    document.getElementById('modalDesc').textContent = desc || "-";

    // Logic Harga Coret
    const originalEl = document.getElementById('modalOriginalPrice');
    if (originalEl) {
        if (originalPriceFormatted && originalPriceFormatted !== '' && originalPriceFormatted !== '0') {
            originalEl.textContent = 'Rp ' + originalPriceFormatted;
            originalEl.classList.remove('hidden');
        } else {
            originalEl.classList.add('hidden');
        }
    }

    // Logic Badges (Flash Sale / Member)
    const badgeContainer = document.getElementById('modalBadges');
    if (badgeContainer) {
        badgeContainer.innerHTML = '';
        if (isFlash) badgeContainer.innerHTML += `<span class="bg-red-600 text-white text-[9px] px-2 py-1 rounded-full font-bold mr-1 uppercase">Flash Sale</span>`;
        if (isMember) badgeContainer.innerHTML += `<span class="bg-blue-600 text-white text-[9px] px-2 py-1 rounded-full font-bold uppercase">Member Price</span>`;
    }

    // Status Stok
    const statusEl = document.getElementById('statusStock');
    if (statusEl) {
        const isReady = parseInt(stock) > 0;
        statusEl.innerText = isReady ? "Ready Stock" : "Habis";
        statusEl.className = isReady ? "text-green-600 font-bold ml-1" : "text-red-600 font-bold ml-1";
    }

    // Update Tombol Add to Cart di Modal
    const modalBtn = document.getElementById('modalAddToCartBtn');
    if (modalBtn) {
        // Clone node buat hilangin event listener lama
        const newBtn = modalBtn.cloneNode(true);
        modalBtn.parentNode.replaceChild(newBtn, modalBtn);
        
        newBtn.onclick = function() {
            let rawOriginal = 0;
            if (originalPriceFormatted) {
                // Bersihkan format Rp dan titik
                rawOriginal = parseFloat(String(originalPriceFormatted).replace(/\./g, '').replace(/,/g, '')) || 0;
            }
            window.addToCart({
                id: id, 
                title: title, 
                price: rawPrice, 
                originalPrice: rawOriginal, 
                img: img
            });
        };
    }

    // Load Review dari Server
    fetchReviews(id);

    // Tampilkan Modal
    modal.classList.remove('hidden');
    // Animasi masuk
    setTimeout(() => {
        modal.classList.remove('opacity-0', 'pointer-events-none');
        const content = document.getElementById('modalContent');
        if(content) {
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }
    }, 10);
}

// 2. FUNGSI LOAD REVIEW
async function fetchReviews(productId) {
    const container = document.getElementById('review-list');
    const idInput = document.getElementById('review_product_id');
    
    // Set ID Produk ke input hidden form review
    if (idInput) idInput.value = productId;
    
    if (!container) return;
    container.innerHTML = '<p class="text-[10px] text-gray-400 italic">Memuat ulasan...</p>';

    try {
        // PATH FIX: actions/review/get.php
        const response = await fetch(`actions/review/get.php?id=${productId}`);
        const reviews = await response.json();

        if (reviews.length === 0) {
            container.innerHTML = '<p class="text-[10px] text-gray-400 italic">Belum ada ulasan.</p>';
            return;
        }

        container.innerHTML = reviews.map(r => `
            <div class="border-b border-gray-100 pb-3 mb-3 last:border-0">
                <div class="flex justify-between items-center mb-1">
                    <span class="font-bold text-xs uppercase text-gray-900">${r.username}</span>
                    <span class="text-amber-500 text-[10px] font-bold">★ ${r.rating}</span>
                </div>
                <p class="text-[11px] text-gray-600 leading-snug">"${r.comment}"</p>
                <p class="text-[9px] text-gray-300 mt-1">${r.created_at}</p>
            </div>
        `).join('');
    } catch (err) {
        console.error("Gagal load review", err);
        container.innerHTML = '<p class="text-[10px] text-red-400">Gagal memuat ulasan.</p>';
    }
}

// 3. FUNGSI KIRIM REVIEW
const reviewForm = document.getElementById('reviewForm');
if (reviewForm) {
    reviewForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const submitBtn = this.querySelector('button[type="submit"]');
        const productId = document.getElementById('review_product_id').value;

        if(!productId) return alert("Terjadi kesalahan sistem (ID Produk hilang).");

        submitBtn.disabled = true;
        
        try {
            const formData = new FormData(this);
            // PATH FIX: actions/review/submit.php
            const res = await fetch('actions/review/submit.php', { method: 'POST', body: formData });
            const result = await res.json();
            
            if (result.status === 'success') {
                // Reset form
                const inputKomen = this.querySelector('[name="comment"]');
                if(inputKomen) inputKomen.value = ''; 
                
                // Refresh list review
                fetchReviews(productId);
            } else {
                alert('Gagal: ' + (result.message || 'Terjadi kesalahan'));
            }
        } catch (error) {
            alert('Error koneksi ke server.');
        } finally {
            submitBtn.disabled = false;
        }
    });
}

function closeModal() {
    const modal = document.getElementById('productModal');
    const content = document.getElementById('modalContent');
    
    if (modal && content) {
        modal.classList.add('opacity-0', 'pointer-events-none');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
}

// Tutup modal kalau klik background gelap
window.onclick = function(event) {
    const modal = document.getElementById('productModal');
    if (event.target == modal) closeModal();
}
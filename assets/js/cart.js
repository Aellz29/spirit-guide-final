// File: assets/js/cart.js

// Ambil Key User
function getCartKey() {
    const currentUID = window.USER_ID || 'guest';
    return 'sg_cart_' + currentUID;
}

// 1. ADD TO CART
function addToCart(item) {
    const cartKey = getCartKey();
    try {
        let cart = JSON.parse(localStorage.getItem(cartKey) || '[]');
        const idx = cart.findIndex(c => c.id == item.id);
        
        // Pastikan harga float
        let finalPrice = parseFloat(item.price);
        let oriPrice = parseFloat(item.originalPrice) || 0;

        if (idx > -1) {
            cart[idx].qty += 1;
        } else {
            cart.push({
                id: item.id,
                title: item.title,
                price: finalPrice,
                originalPrice: oriPrice,
                img: item.img,
                qty: 1
            });
        }

        localStorage.setItem(cartKey, JSON.stringify(cart));
        
        // Update UI
        updateCartBadge();
        if(typeof renderCartSidebar === 'function') renderCartSidebar();
        if(typeof openCart === 'function') openCart(); 
        
    } catch (e) {
        console.error("Gagal simpan keranjang:", e);
    }
}

// 2. UPDATE BADGE
function updateCartBadge() {
    const cartKey = getCartKey(); 
    const cart = JSON.parse(localStorage.getItem(cartKey) || '[]');
    const totalQty = cart.reduce((total, item) => total + (item.qty || 0), 0);
    
    const badgeDesktop = document.getElementById('cart-badge-desktop');
    const badgeMobile = document.getElementById('cart-badge-mobile');
    
    if (badgeDesktop) {
        if (totalQty > 0) {
            badgeDesktop.textContent = totalQty;
            badgeDesktop.classList.remove('hidden');
            badgeDesktop.classList.add('animate-bounce');
        } else {
            badgeDesktop.classList.add('hidden');
        }
    }
    if (badgeMobile) {
        if (totalQty > 0) {
            badgeMobile.textContent = totalQty;
            badgeMobile.classList.remove('hidden');
        } else {
            badgeMobile.classList.add('hidden');
        }
    }
}

// 3. SIDEBAR LOGIC
function toggleCart() {
    const sidebar = document.getElementById('cart-sidebar');
    if (!sidebar) return;
    if (sidebar.classList.contains('translate-x-full')) openCart();
    else closeCart();
}

function openCart() {
    const sidebar = document.getElementById('cart-sidebar');
    const overlay = document.getElementById('cart-overlay');
    if (!sidebar) return;
    sidebar.classList.remove('translate-x-full');
    if(overlay) {
        overlay.classList.remove('hidden');
        setTimeout(() => overlay.classList.remove('opacity-0'), 10);
    }
    renderCartSidebar();
}

function closeCart() {
    const sidebar = document.getElementById('cart-sidebar');
    const overlay = document.getElementById('cart-overlay');
    if (!sidebar) return;
    sidebar.classList.add('translate-x-full');
    if(overlay) {
        overlay.classList.add('opacity-0');
        setTimeout(() => overlay.classList.add('hidden'), 300);
    }
}

// 4. RENDER SIDEBAR
function renderCartSidebar() {
    const container = document.getElementById('cart-items-container');
    if (!container) return; 

    const totalEl = document.getElementById('cart-total');
    const cartKey = getCartKey();
    const cart = JSON.parse(localStorage.getItem(cartKey) || '[]');
    
    if (cart.length === 0) {
        container.innerHTML = `<div class="flex flex-col items-center justify-center h-full text-center opacity-50"><i class="fa fa-shopping-basket text-4xl mb-3 text-gray-300"></i><p class="text-sm font-bold text-gray-400">Keranjang Kosong</p></div>`;
        if(totalEl) totalEl.innerText = 'Rp 0';
        return;
    }

    let html = '';
    let grandTotal = 0;

    cart.forEach((item, index) => {
        let subtotal = item.price * item.qty;
        grandTotal += subtotal;
        html += `
        <div class="flex gap-4 items-start border-b border-gray-50 pb-4 last:border-0 relative">
            <div class="w-16 h-16 rounded-lg bg-gray-100 overflow-hidden shrink-0 border border-gray-200">
                <img src="${item.img}" class="w-full h-full object-cover">
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="text-xs font-bold text-gray-900 uppercase truncate pr-4">${item.title}</h4>
                <p class="text-[10px] text-gray-500 mb-2">@ Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</p>
                <div class="flex items-center gap-3">
                    <button onclick="changeQty(${index}, -1)" class="w-6 h-6 rounded-md bg-gray-100 flex items-center justify-center text-[10px] hover:bg-black hover:text-white"><i class="fa fa-minus"></i></button>
                    <span class="text-xs font-bold w-4 text-center">${item.qty}</span>
                    <button onclick="changeQty(${index}, 1)" class="w-6 h-6 rounded-md bg-gray-100 flex items-center justify-center text-[10px] hover:bg-black hover:text-white"><i class="fa fa-plus"></i></button>
                </div>
            </div>
            <div class="flex flex-col items-end gap-1">
                <span class="text-xs font-bold text-gray-900">Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}</span>
                <button onclick="removeItem(${index})" class="text-[10px] text-red-400 hover:text-red-600 underline">Hapus</button>
            </div>
        </div>`;
    });
    container.innerHTML = html;
    if(totalEl) totalEl.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(grandTotal);
}

// 5. HELPER ACTIONS
function changeQty(index, delta) {
    const cartKey = getCartKey();
    let cart = JSON.parse(localStorage.getItem(cartKey) || '[]');
    if (cart[index]) {
        cart[index].qty += delta;
        if (cart[index].qty <= 0) cart.splice(index, 1);
    }
    localStorage.setItem(cartKey, JSON.stringify(cart));
    updateCartBadge();
    renderCartSidebar();
}

function removeItem(index) {
    const cartKey = getCartKey();
    let cart = JSON.parse(localStorage.getItem(cartKey) || '[]');
    cart.splice(index, 1);
    localStorage.setItem(cartKey, JSON.stringify(cart));
    updateCartBadge();
    renderCartSidebar();
}

// 6. TRANSFER GUEST CART (Dipanggil saat Login)
function transferGuestCart() {
    const currentUID = window.USER_ID;
    
    // Pastikan user LOGIN dan bukan guest
    if (currentUID && currentUID !== 'guest') {
        const guestKey = 'sg_cart_guest';
        const userKey = 'sg_cart_' + currentUID;
        
        // Ambil cart guest
        const guestCart = JSON.parse(localStorage.getItem(guestKey) || '[]');
        
        if (guestCart.length > 0) {
            let userCart = JSON.parse(localStorage.getItem(userKey) || '[]');
            
            // Gabungkan
            guestCart.forEach(gItem => {
                const existingIdx = userCart.findIndex(uItem => uItem.id == gItem.id);
                if (existingIdx > -1) {
                    userCart[existingIdx].qty += gItem.qty;
                } else {
                    userCart.push(gItem);
                }
            });
            
            // Simpan ke user & Hapus guest
            localStorage.setItem(userKey, JSON.stringify(userCart));
            localStorage.removeItem(guestKey);
            console.log("Cart transferred to member.");
        }
    }
}

// Init
document.addEventListener('DOMContentLoaded', () => {
    transferGuestCart();
    updateCartBadge();
});
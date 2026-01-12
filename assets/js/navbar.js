/**
 * NAVBAR INTERACTION LOGIC
 * Handles: Mobile Menu Toggle, Close Button, Hamburger Animation
 */

document.addEventListener('DOMContentLoaded', () => {
    // Selector
    const mobileBtn = document.getElementById('menu-btn');
    const hamburgerIcon = document.getElementById('hamburger-icon'); // Icon garis 3
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileOverlay = document.getElementById('mobile-menu-overlay');
    
    // Selector Tombol Close (Bisa lebih dari satu)
    const closeBtns = document.querySelectorAll('.close-menu-action');

    // Fungsi Toggle (Buka/Tutup)
    function toggleMobileMenu(forceClose = false) {
        if (!mobileMenu || !mobileOverlay) return;
        
        const isClosed = mobileMenu.classList.contains('translate-x-full');
        
        if (forceClose || !isClosed) {
            // --- ACTION: TUTUP MENU ---
            mobileMenu.classList.add('translate-x-full');
            mobileOverlay.classList.add('opacity-0');
            
            // Animasi Icon Balik ke Garis 3
            if(hamburgerIcon) {
                const bars = hamburgerIcon.children;
                bars[0].classList.remove('rotate-45', 'translate-y-2');
                bars[1].classList.remove('opacity-0');
                bars[2].classList.remove('-rotate-45', '-translate-y-2');
            }

            setTimeout(() => {
                mobileOverlay.classList.add('hidden');
            }, 300);

        } else {
            // --- ACTION: BUKA MENU ---
            mobileOverlay.classList.remove('hidden');
            
            // Animasi Icon Jadi X
            if(hamburgerIcon) {
                const bars = hamburgerIcon.children;
                bars[0].classList.add('rotate-45', 'translate-y-2');
                bars[1].classList.add('opacity-0');
                bars[2].classList.add('-rotate-45', '-translate-y-2');
            }

            requestAnimationFrame(() => {
                mobileOverlay.classList.remove('opacity-0');
                mobileMenu.classList.remove('translate-x-full');
            });
        }
    }

    // 1. Klik Tombol Hamburger
    if(mobileBtn) {
        mobileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleMobileMenu();
        });
    }

    // 2. Klik Tombol Close (X) - WAJIB ADA INI BIAR TOMBOL X JALAN
    closeBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            toggleMobileMenu(true); // Paksa tutup
        });
    });
    
    // 3. Klik Overlay (Area Gelap)
    if(mobileOverlay) {
        mobileOverlay.addEventListener('click', () => toggleMobileMenu(true));
    }
});
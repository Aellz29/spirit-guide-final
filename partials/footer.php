<?php
$base_url = (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false) ? '../' : '';
?>

<footer class="bg-gray-900 text-white pt-16 pb-8 mt-auto border-t border-gray-800">
  <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-4 gap-12">

    <div class="space-y-4">
      <h2 class="text-2xl font-black text-amber-500 tracking-tighter uppercase italic">Spirit Guide</h2>
      <p class="text-gray-400 text-xs leading-relaxed font-medium">
        Destinasi utama fashion, kuliner, dan gaya hidup modern. Temukan koleksi eksklusif yang mendefinisikan jati dirimu.
      </p>
    </div>

    <div>
      <h3 class="text-xs font-bold uppercase tracking-widest text-gray-500 mb-6">Menu Cepat</h3>
      <ul class="space-y-3 text-sm text-gray-300">
        <li><a href="<?= $base_url ?>index.php" class="hover:text-amber-500 transition">Beranda</a></li>
        <li><a href="<?= $base_url ?>katalog.php?category=Fashion" class="hover:text-amber-500 transition">Katalog</a></li>
        <li><a href="<?= $base_url ?>checkout.php" class="hover:text-amber-500 transition">Keranjang Belanja</a></li>
      </ul>
    </div>

    <div>
      <h3 class="text-xs font-bold uppercase tracking-widest text-gray-500 mb-6">Hubungi Kami</h3>
      <ul class="space-y-4 text-sm text-gray-300">
        <li class="flex items-start gap-3">
            <i class="fa fa-map-marker-alt text-amber-500 mt-1"></i>
            <span>Jl. Cibogo No. Indah 3, Bandung<br>Jawa Barat, Indonesia</span>
        </li>
        <li class="flex items-center gap-3">
            <i class="fa fa-whatsapp text-amber-500"></i>
            <a href="https://wa.me/628971566371" target="_blank" class="hover:text-amber-500 transition">+62 897-1566-371</a>
        </li>
      </ul>
    </div>

    <div>
      <h3 class="text-xs font-bold uppercase tracking-widest text-gray-500 mb-6">Pembayaran</h3>
      <div class="grid grid-cols-4 gap-2 mb-6">
         <img src="<?= $base_url ?>assets/img/payment/bca.png" class="h-8 w-auto bg-white p-1 rounded opacity-70 hover:opacity-100 transition">
         <img src="<?= $base_url ?>assets/img/payment/bni.png" class="h-8 w-auto bg-white p-1 rounded opacity-70 hover:opacity-100 transition">
         <img src="<?= $base_url ?>assets/img/payment/bri.png" class="h-8 w-auto bg-white p-1 rounded opacity-70 hover:opacity-100 transition">
      </div>
      
      <h3 class="text-xs font-bold uppercase tracking-widest text-gray-500 mb-4">Sosial Media</h3>
      <div class="flex gap-4">
        <a href="#" class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center hover:bg-amber-500 hover:text-black transition"><i class="fa-brands fa-instagram"></i></a>
        <a href="#" class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center hover:bg-amber-500 hover:text-black transition"><i class="fa-brands fa-tiktok"></i></a>
      </div>
    </div>

  </div>

  <div class="border-t border-white/5 mt-12 pt-8 text-center">
    <p class="text-[10px] text-gray-600 font-bold uppercase tracking-widest">
      &copy; <?= date('Y') ?> Spirit Guide. Made with <i class="fa fa-heart text-red-900"></i> in Bandung.
    </p>
  </div>
</footer>
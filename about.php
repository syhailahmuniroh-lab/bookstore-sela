<?php
include 'header.php';
?>

<main class="flex-1">
    <!-- Hero Banner -->
    <section class="relative py-20 bg-darkPanel border-b border-[rgba(212,168,83,0.15)] overflow-hidden">
        <div class="absolute -right-20 -top-20 w-80 h-80 rounded-full bg-emas/5 blur-3xl"></div>
        <div class="container mx-auto px-5 max-w-4xl text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emas/10 border border-emas/20 text-emas text-xs uppercase tracking-widest font-semibold mb-4">
                <i data-lucide="book-open" class="w-3.5 h-3.5"></i> Profil Perusahaan
            </div>
            <h1 class="font-serif text-4xl lg:text-5xl font-bold text-white mb-4">Tentang Cell’s Bookshelf</h1>
            <p class="text-sm text-[#f0ebe0]/70 max-w-2xl mx-auto leading-relaxed">
                Penyedia pustaka & karya literasi kurasi terbaik yang berdedikasi menghadirkan bacaan berkualitas tinggi dari penulis ternama lokal maupun internasional.
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="container mx-auto px-5 py-16 max-w-4xl">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
            <div class="p-8 rounded-3xl bg-white/[0.03] border border-white/[0.08] shadow-xl">
                <div class="w-12 h-12 rounded-2xl bg-emas/10 border border-emas/30 flex items-center justify-center text-emas mb-5">
                    <i data-lucide="compass" class="w-6 h-6"></i>
                </div>
                <h3 class="font-serif font-bold text-xl text-white mb-3">Visi Kami</h3>
                <p class="text-xs leading-relaxed text-[#f0ebe0]/60">
                    Menjadi jaringan pustaka terpercaya yang menginspirasi masyarakat melalui kemudahan akses ke buku-buku kurasi asli, berkualitas tinggi, dan bernilai seni tinggi.
                </p>
            </div>

            <div class="p-8 rounded-3xl bg-white/[0.03] border border-white/[0.08] shadow-xl">
                <div class="w-12 h-12 rounded-2xl bg-emas/10 border border-emas/30 flex items-center justify-center text-emas mb-5">
                    <i data-lucide="target" class="w-6 h-6"></i>
                </div>
                <h3 class="font-serif font-bold text-xl text-white mb-3">Misi Utama</h3>
                <p class="text-xs leading-relaxed text-[#f0ebe0]/60">
                    Menjamin keaslian 100% setiap cetakan buku, menyediakan layanan pengiriman cepat ke seluruh wilayah Indonesia, serta memberikan pengalaman belanja yang eksklusif.
                </p>
            </div>
        </div>

        <!-- Pillars -->
        <div class="rounded-3xl p-8 bg-white/[0.02] border border-white/[0.06] mb-12">
            <h3 class="font-serif font-bold text-2xl text-white mb-6 text-center">Komitmen Keunggulan Cell’s Bookshelf</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-4">
                    <div class="text-3xl mb-3">🛡️</div>
                    <h5 class="font-serif font-bold text-sm text-white mb-1">100% Asli &amp; Resmi</h5>
                    <p class="text-[11px] text-[#f0ebe0]/50">Semua pustaka didapatkan langsung dari penerbit utama tanpa cetakan bajakan.</p>
                </div>
                <div class="text-center p-4">
                    <div class="text-3xl mb-3">🚚</div>
                    <h5 class="font-serif font-bold text-sm text-white mb-1">Pengiriman COD</h5>
                    <p class="text-[11px] text-[#f0ebe0]/50">Kemudahan pembayaran tunai di tempat saat barang sampai dengan aman.</p>
                </div>
                <div class="text-center p-4">
                    <div class="text-3xl mb-3">⭐</div>
                    <h5 class="font-serif font-bold text-sm text-white mb-1">Layanan Konsierse</h5>
                    <p class="text-[11px] text-[#f0ebe0]/50">Dukungan pelanggan responsif yang siap membantu kebutuhan pustaka Anda.</p>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
include 'footer.php';
?>

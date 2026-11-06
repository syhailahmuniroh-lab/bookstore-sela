<?php
// Calculate cart count
$cart_count = 0;
if (isset($_SESSION['keranjang'])) {
    $cart_count = array_sum($_SESSION['keranjang']);
}

// Get active file name
$current_page = basename($_SERVER['PHP_SELF']);
?>

    <!-- Responsive 5-Column Footer -->
    <footer class="py-12 mt-auto border-t border-[rgba(212,168,83,0.15)] bg-[#07050e] text-[#f0ebe0]">
        <div class="container mx-auto px-5">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 mb-10">
                <!-- Kolom 1: Info Kontak & Sosmed -->
                <div class="space-y-4">
                    <a href="index.php" class="group flex items-center gap-3">
                        <div class="w-8 h-8 rounded-2xl bg-gradient-to-tr from-rose-400/20 via-amber-200/20 to-purple-400/20 border border-rose-200/30 flex items-center justify-center text-rose-200 shadow-sm">
                            <i data-lucide="sparkles" class="w-4 h-4 text-rose-200"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-serif text-lg tracking-wide font-normal text-transparent bg-clip-text bg-gradient-to-r from-amber-100 via-rose-200 to-amber-200 leading-tight">
                                Cell’s Bookshelf
                            </span>
                            <span class="text-[8px] tracking-[0.2em] uppercase text-rose-200/60 font-light font-sans">
                                Curated Literary Boutique
                            </span>
                        </div>
                    </a>
                    <p class="text-xs text-[#f0ebe0]/55 leading-relaxed">
                        Penyedia pustaka & karya literasi kurasi terbaik dengan layanan pengiriman terpercaya ke seluruh Indonesia.
                    </p>
                    <div class="space-y-2.5 text-xs text-[#f0ebe0]/65 pt-1">
                        <p class="flex items-start gap-2.5">
                            <i data-lucide="map-pin" class="w-4 h-4 text-emas shrink-0 mt-0.5"></i>
                            <span>Jl. Pustaka Utama No. 45, Jakarta Selatan</span>
                        </p>
                        <p class="flex items-center gap-2.5">
                            <i data-lucide="phone" class="w-4 h-4 text-emas shrink-0"></i>
                            <span>+62 812-3456-7890</span>
                        </p>
                        <p class="flex items-center gap-2.5">
                            <i data-lucide="mail" class="w-4 h-4 text-emas shrink-0"></i>
                            <span>concierge@cellsbookshelf.co.id</span>
                        </p>
                    </div>
                    <div class="pt-2">
                        <span class="text-[10px] uppercase tracking-widest text-[#f0ebe0]/40 font-semibold block mb-2.5">Ikuti Kami</span>
                        <div class="flex items-center gap-2">
                            <!-- Facebook -->
                            <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" title="Facebook Cell’s Bookshelf" class="w-8 h-8 rounded-xl bg-white/[0.05] border border-white/10 flex items-center justify-center text-emas hover:bg-emas/20 hover:border-emas/40 transition-all">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <!-- Twitter / X -->
                            <a href="https://twitter.com" target="_blank" rel="noopener noreferrer" title="Twitter/X Cell’s Bookshelf" class="w-8 h-8 rounded-xl bg-white/[0.05] border border-white/10 flex items-center justify-center text-emas hover:bg-emas/20 hover:border-emas/40 transition-all">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                            <!-- Instagram -->
                            <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" title="Instagram Cell’s Bookshelf" class="w-8 h-8 rounded-xl bg-white/[0.05] border border-white/10 flex items-center justify-center text-emas hover:bg-emas/20 hover:border-emas/40 transition-all">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                            <!-- YouTube -->
                            <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" title="YouTube Cell’s Bookshelf" class="w-8 h-8 rounded-xl bg-white/[0.05] border border-white/10 flex items-center justify-center text-emas hover:bg-emas/20 hover:border-emas/40 transition-all">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Kolom 2: Informasi Perusahaan -->
                <div>
                    <h5 class="font-serif font-bold text-sm text-white mb-4 border-l-2 border-emas pl-2.5">Perusahaan</h5>
                    <ul class="space-y-2.5 text-xs text-[#f0ebe0]/60">
                        <li><a href="about.php" class="hover:text-emas transition-colors">Tentang Kami</a></li>
                        <li><a href="javascript:void(0)" onclick="openContactModal()" class="hover:text-emas transition-colors">Hubungi Admin</a></li>
                        <li><a href="privacy.php" class="hover:text-emas transition-colors">Kebijakan Privasi</a></li>
                        <li><a href="privacy.php#disclaimer" class="hover:text-emas transition-colors">Penafian</a></li>
                    </ul>
                </div>

                <!-- Kolom 3: Akun Pelanggan -->
                <div>
                    <h5 class="font-serif font-bold text-sm text-white mb-4 border-l-2 border-emas pl-2.5">Akun Saya</h5>
                    <ul class="space-y-2.5 text-xs text-[#f0ebe0]/60">
                        <li><a href="orders.php" class="hover:text-emas transition-colors">Pesanan Saya</a></li>
                        <li><a href="cart.php" class="hover:text-emas transition-colors">Keranjang Belanja</a></li>
                        <li><a href="profile.php" class="hover:text-emas transition-colors">Profil Anggota</a></li>
                        <li><a href="search.php" class="hover:text-emas transition-colors">Pencarian Pustaka</a></li>
                    </ul>
                </div>

                <!-- Kolom 4: Layanan & Bantuan -->
                <div>
                    <h5 class="font-serif font-bold text-sm text-white mb-4 border-l-2 border-emas pl-2.5">Layanan & Bantuan</h5>
                    <ul class="space-y-2.5 text-xs text-[#f0ebe0]/60">
                        <li><a href="faq.php" class="hover:text-emas transition-colors">Panduan Pemesanan</a></li>
                        <li><a href="faq.php#cod" class="hover:text-emas transition-colors">Layanan COD</a></li>
                        <li><a href="terms.php" class="hover:text-emas transition-colors">Garansi Keaslian</a></li>
                        <li><a href="faq.php" class="hover:text-emas transition-colors">Pertanyaan Umum (FAQ)</a></li>
                    </ul>
                </div>

                <!-- Kolom 5: Kategori Pustaka -->
                <div>
                    <h5 class="font-serif font-bold text-sm text-white mb-4 border-l-2 border-emas pl-2.5">Kategori Pustaka</h5>
                    <ul class="space-y-2.5 text-xs text-[#f0ebe0]/60">
                        <li><a href="search.php?kategori=Action" class="hover:text-emas transition-colors">Sastra & Novel</a></li>
                        <li><a href="search.php?kategori=Self-Help" class="hover:text-emas transition-colors">Pengembangan Diri</a></li>
                        <li><a href="search.php?kategori=History" class="hover:text-emas transition-colors">Sejarah & Budaya</a></li>
                        <li><a href="search.php?kategori=Science%20Fiction" class="hover:text-emas transition-colors">Sains & Teknologi</a></li>
                        <li><a href="search.php?kategori=Business" class="hover:text-emas transition-colors">Bisnis & Keuangan</a></li>
                    </ul>
                </div>
            </div>

            <!-- Copyright Bottom Bar -->
            <div class="pt-6 border-t border-white/[0.06] flex flex-col md:flex-row justify-between items-center text-xs text-[#f0ebe0]/40 gap-3 text-center md:text-left">
                <p>© 2026 Cell’s Bookshelf. Seluruh Hak Cipta Dilindungi.</p>
                <p>Layanan Pelanggan: admin@bookstore.com · Jam Operasional: 08.00 - 21.00 WIB</p>
            </div>
        </div>
    </footer>

    <!-- Bottom TabBar (Sticky at bottom for mobile) -->
    <?php if (isset($_SESSION['id_user'])): ?>
    <nav class="fixed bottom-0 left-0 right-0 z-50 flex md:hidden bg-[#0e0b1c] border-t border-[rgba(212,168,83,0.15)] shadow-[0_-8px_24px_rgba(0,0,0,0.6)]" style="padding-bottom: calc(4px + env(safe-area-inset-bottom, 12px));">
        <!-- Beranda -->
        <a href="index.php" class="flex-1 flex flex-col items-center py-2.5 gap-1 relative transition-colors <?php echo ($current_page == 'index.php') ? 'text-emas' : 'text-[#f0ebe0]/35'; ?>">
            <?php if ($current_page == 'index.php'): ?>
                <div class="absolute top-0 left-1/2 -translate-x-1/2 h-0.5 w-8 rounded-full bg-emas"></div>
            <?php endif; ?>
            <i data-lucide="home" class="w-5 h-5"></i>
            <span class="text-[10px] font-medium">Beranda</span>
        </a>
        <!-- Cari -->
        <a href="search.php" class="flex-1 flex flex-col items-center py-2.5 gap-1 relative transition-colors <?php echo ($current_page == 'search.php') ? 'text-emas' : 'text-[#f0ebe0]/35'; ?>">
            <?php if ($current_page == 'search.php'): ?>
                <div class="absolute top-0 left-1/2 -translate-x-1/2 h-0.5 w-8 rounded-full bg-emas"></div>
            <?php endif; ?>
            <i data-lucide="search" class="w-5 h-5"></i>
            <span class="text-[10px] font-medium">Cari</span>
        </a>
        <!-- Keranjang -->
        <a href="cart.php" class="flex-1 flex flex-col items-center py-2.5 gap-1 relative transition-colors <?php echo ($current_page == 'cart.php' || $current_page == 'checkout.php') ? 'text-emas' : 'text-[#f0ebe0]/35'; ?>">
            <?php if ($current_page == 'cart.php' || $current_page == 'checkout.php'): ?>
                <div class="absolute top-0 left-1/2 -translate-x-1/2 h-0.5 w-8 rounded-full bg-emas"></div>
            <?php endif; ?>
            <i data-lucide="shopping-cart" class="w-5 h-5"></i>
            <span class="text-[10px] font-medium">Keranjang</span>
            <?php if ($cart_count > 0): ?>
                <span class="absolute top-1 right-[22%] w-4 h-4 rounded-full text-[9px] flex items-center justify-center font-bold bg-emas text-darkBg">
                    <?php echo ($cart_count > 9) ? '9+' : $cart_count; ?>
                </span>
            <?php endif; ?>
        </a>
        <!-- Pesanan -->
        <a href="orders.php" class="flex-1 flex flex-col items-center py-2.5 gap-1 relative transition-colors <?php echo ($current_page == 'orders.php') ? 'text-emas' : 'text-[#f0ebe0]/35'; ?>">
            <?php if ($current_page == 'orders.php'): ?>
                <div class="absolute top-0 left-1/2 -translate-x-1/2 h-0.5 w-8 rounded-full bg-emas"></div>
            <?php endif; ?>
            <i data-lucide="package" class="w-5 h-5"></i>
            <span class="text-[10px] font-medium">Pesanan</span>
        </a>
        <!-- Profil -->
        <a href="profile.php" class="flex-1 flex flex-col items-center py-2.5 gap-1 relative transition-colors <?php echo ($current_page == 'profile.php') ? 'text-emas' : 'text-[#f0ebe0]/35'; ?>">
            <?php if ($current_page == 'profile.php'): ?>
                <div class="absolute top-0 left-1/2 -translate-x-1/2 h-0.5 w-8 rounded-full bg-emas"></div>
            <?php endif; ?>
            <i data-lucide="user" class="w-5 h-5"></i>
            <span class="text-[10px] font-medium">Profil</span>
        </a>
    </nav>
    <?php endif; ?>

    <!-- Contact Modal -->
    <div id="contactModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden bg-[rgba(10,8,20,0.85)] backdrop-blur-sm transition-opacity duration-300">
        <div class="w-full max-w-sm rounded-3xl p-6 bg-darkPanel border border-[rgba(212,168,83,0.2)] animate-fade-up">
            <div id="contactFormContainer">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="font-serif font-bold text-lg text-white">Hubungi Admin</h4>
                    <button onclick="closeContactModal()" class="text-white/40 hover:text-white">&times;</button>
                </div>
                <div class="mb-4">
                    <label class="block text-[10px] uppercase tracking-widest font-medium mb-2 text-emas">Pesan Anda</label>
                    <textarea id="contactMessage" rows="4" class="w-full rounded-xl px-4 py-3 text-xs outline-none bg-white/[0.04] border border-white/[0.08] text-white focus:border-emas resize-none transition-all" placeholder="Tulis pesan Anda untuk admin..."></textarea>
                </div>
                <button onclick="submitContactMessage()" class="w-full py-3 rounded-xl font-bold text-xs btn-gold text-darkBg">
                    Kirim Pesan
                </button>
            </div>
            <div id="contactSuccessContainer" class="hidden text-center py-8">
                <div class="text-5xl mb-4">✉️</div>
                <h4 class="font-serif font-bold text-lg text-white mb-2">Terkirim!</h4>
                <p class="text-xs text-[#f0ebe0]/50">Pesan Anda telah berhasil disampaikan ke admin.</p>
            </div>
        </div>
    </div>

    <!-- JS helper functions -->
    <script>
        function escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function openBookModalFromData(el) {
            try {
                const target = el.closest('[data-buku]') || el;
                const rawData = target.dataset.buku || target.getAttribute('data-buku');
                if (!rawData) return;
                let data;
                if (rawData.startsWith('{') || rawData.startsWith('[')) {
                    data = JSON.parse(rawData);
                } else {
                    try {
                        data = JSON.parse(decodeURIComponent(escape(atob(rawData))));
                    } catch (e1) {
                        data = JSON.parse(atob(rawData));
                    }
                }
                const priceFmt = 'Rp ' + Number(data.harga).toLocaleString('id-ID');
                const stokVal = data.stok !== undefined ? parseInt(data.stok) : 25;
                openBookModal(data.id_buku, data.judul, data.penulis, data.nama_kategori || 'Pustaka', priceFmt, data.gambar, data.deskripsi || 'Tidak ada deskripsi.', stokVal);
            } catch (err) {
                console.error('Modal Parse Error:', err);
            }
        }

        function openBookModal(id, title, author, category, price, cover, description, stok) {
            const stokNum = (stok !== undefined && stok !== null) ? parseInt(stok) : 25;
            const isOutOfStock = stokNum <= 0;
            const stockBadge = isOutOfStock 
                ? `<span class="inline-block px-2.5 py-0.5 rounded-lg text-[10px] bg-red-500/15 text-red-400 border border-red-500/20 font-medium">Stok Habis</span>`
                : `<span class="inline-block px-2.5 py-0.5 rounded-lg text-[10px] bg-emerald-500/15 text-emerald-400 border border-emerald-500/20 font-medium">Tersedia: ${stokNum} buku</span>`;

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: `<div class="font-serif font-bold text-lg text-white">${escapeHtml(title)}</div>`,
                    html: `
                        <div class="text-left space-y-4 pt-2">
                            <div class="flex gap-4 items-start">
                                <img src="${escapeHtml(cover)}" alt="Cover" class="w-24 h-36 object-cover rounded-2xl bg-[#1a1528] border border-white/10 shadow-2xl flex-shrink-0">
                                <div class="flex-1 min-w-0">
                                    <span class="inline-block px-2.5 py-0.5 rounded-lg text-[10px] bg-emas/15 text-emas border border-emas/20 mb-2 font-medium">${escapeHtml(category)}</span>
                                    <div class="text-xs text-[#f0ebe0]/50 mb-1">Penulis: <b class="text-white">${escapeHtml(author)}</b></div>
                                    <div class="mb-2">${stockBadge}</div>
                                    <div class="text-xl font-bold text-emas">${escapeHtml(price)}</div>
                                </div>
                            </div>
                            <div class="text-xs text-[#f0ebe0]/70 leading-relaxed max-h-36 overflow-y-auto pr-1 bg-white/[0.03] p-3 rounded-2xl border border-white/[0.06]">
                                ${escapeHtml(description)}
                            </div>
                        </div>
                    `,
                    showCancelButton: true,
                    showConfirmButton: !isOutOfStock,
                    confirmButtonText: '+ Tambah ke Keranjang',
                    cancelButtonText: 'Tutup',
                    confirmButtonColor: '#d4a853',
                    cancelButtonColor: 'rgba(255,255,255,0.08)',
                    background: '#14112a',
                    color: '#f0ebe0',
                    width: '90%',
                    customClass: {
                        popup: 'border border-[rgba(212,168,83,0.3)] rounded-3xl font-sans max-w-lg shadow-2xl text-darkBg'
                    }
                }).then((result) => {
                    if (result.isConfirmed && !isOutOfStock) {
                        window.location = 'cart.php?id=' + id;
                    }
                });
            } else {
                // Fallback to DOM modal
                document.getElementById('modalCover').src = cover;
                document.getElementById('modalTitle').innerText = title;
                document.getElementById('modalAuthor').innerText = author;
                document.getElementById('modalCategory').innerText = category;
                document.getElementById('modalPrice').innerText = price;
                document.getElementById('modalDescription').innerText = description;
                const buyBtn = document.getElementById('modalBuyBtn');
                if (buyBtn) {
                    if (isOutOfStock) {
                        buyBtn.innerText = 'Stok Habis';
                        buyBtn.href = '#';
                        buyBtn.classList.add('pointer-events-none', 'opacity-50');
                    } else {
                        buyBtn.innerText = '+ Tambah ke Keranjang';
                        buyBtn.href = 'cart.php?id=' + id;
                        buyBtn.classList.remove('pointer-events-none', 'opacity-50');
                    }
                }
                
                const modal = document.getElementById('bookModal');
                if (modal) {
                    modal.style.display = 'flex';
                    modal.classList.remove('hidden');
                }
            }
        }

        function closeBookModal() {
            const modal = document.getElementById('bookModal');
            if (modal) {
                modal.style.display = 'none';
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        // Close modal when clicking outside content
        const domBookModal = document.getElementById('bookModal');
        if (domBookModal) {
            domBookModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeBookModal();
                }
            });
        }

        function openContactModal() {
            <?php if (!isset($_SESSION['id_user'])): ?>
                Swal.fire({
                    title: 'Akses Kontak',
                    text: 'Silakan Masuk (Login) terlebih dahulu untuk menghubungi admin!',
                    icon: 'info',
                    background: '#14112a',
                    color: '#f0ebe0',
                    confirmButtonColor: '#d4a853',
                    confirmButtonText: 'Masuk Sekarang',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    customClass: { popup: 'border border-[rgba(212,168,83,0.2)] rounded-3xl font-sans' }
                }).then((res) => {
                    if (res.isConfirmed) {
                        window.location = 'login.php';
                    }
                });
                return;
            <?php endif; ?>
            const cModal = document.getElementById('contactModal');
            if (cModal) {
                cModal.classList.remove('hidden');
                cModal.classList.add('flex');
            }
            const cForm = document.getElementById('contactFormContainer');
            if (cForm) cForm.classList.remove('hidden');
            const cSuccess = document.getElementById('contactSuccessContainer');
            if (cSuccess) cSuccess.classList.add('hidden');
        }

        function closeContactModal() {
            const cModal = document.getElementById('contactModal');
            if (cModal) {
                cModal.classList.add('hidden');
                cModal.classList.remove('flex');
            }
        }

        const domContactModal = document.getElementById('contactModal');
        if (domContactModal) {
            domContactModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeContactModal();
                }
            });
        }

        function submitContactMessage() {
            const msgEl = document.getElementById('contactMessage');
            if (!msgEl) return;
            const msg = msgEl.value;
            if (!msg.trim()) return;

            // Submit using fetch
            fetch('submit_message.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'message=' + encodeURIComponent(msg)
            })
            .then(response => response.text())
            .then(data => {
                const resText = data.trim();
                if (resText === 'success') {
                    document.getElementById('contactFormContainer').classList.add('hidden');
                    document.getElementById('contactSuccessContainer').classList.remove('hidden');
                    msgEl.value = '';
                    setTimeout(closeContactModal, 2000);
                } else if (resText === 'unauthorized') {
                    Swal.fire({
                        title: 'Akses Terbatas',
                        text: 'Silakan login terlebih dahulu untuk mengirim pesan ke admin.',
                        icon: 'warning',
                        background: '#14112a',
                        color: '#f0ebe0',
                        confirmButtonColor: '#d4a853'
                    }).then(() => { window.location = 'login.php'; });
                } else {
                    Swal.fire({
                        title: 'Gagal Kirim',
                        text: 'Terjadi kesalahan sistem, silakan coba lagi.',
                        icon: 'error',
                        background: '#14112a',
                        color: '#f0ebe0',
                        confirmButtonColor: '#d4a853'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Gagal Kirim',
                    text: 'Gagal mengirim pesan ke server.',
                    icon: 'error',
                    background: '#14112a',
                    color: '#f0ebe0',
                    confirmButtonColor: '#d4a853',
                    customClass: {
                        popup: 'border border-[rgba(212,168,83,0.2)] rounded-3xl font-sans'
                    }
                });
            });
        }

        function openInfoModal(title, text) {
            Swal.fire({
                title: title,
                text: text,
                icon: 'info',
                background: '#14112a',
                color: '#f0ebe0',
                confirmButtonColor: '#d4a853',
                confirmButtonText: 'Tutup',
                width: '90%',
                customClass: {
                    popup: 'border border-[rgba(212,168,83,0.2)] rounded-3xl font-sans'
                }
            });
        }
    </script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
</body>
</html>

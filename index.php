<?php
include 'header.php';

// Check if user is logged in
$is_logged_in = isset($_SESSION['id_user']);
?>

<main class="flex-1">
    <?php if (!$is_logged_in): ?>
        <!-- ================= LANDING PAGE (GUEST) ================= -->
        <!-- Hero section with background photo and overlays -->
        <section class="relative min-h-screen flex items-center overflow-hidden -mt-[73px]">
            <!-- BG photo -->
            <div class="absolute inset-0">
                <img src="https://images.unsplash.com/photo-1764923753986-c3f564e295d6?w=1400&h=900&fit=crop&auto=format&q=80" alt="Bookstore" class="w-full h-full object-cover">
                <div class="absolute inset-0" style="background: linear-gradient(135deg, rgba(10,8,20,0.98) 0%, rgba(10,8,20,0.92) 50%, rgba(10,8,20,0.96) 100%);"></div>
            </div>

            <!-- Decorative book spine lines -->
            <div class="absolute right-0 top-0 bottom-0 w-px opacity-20 bg-gradient-to-b from-transparent via-[#d4a853] to-transparent"></div>

            <div class="relative max-w-6xl mx-auto px-6 pt-24 pb-16 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center w-full">
                <!-- Left Text column -->
                <div class="animate-fade-up">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-px w-10 bg-emas"></div>
                        <span class="text-xs uppercase tracking-[0.3em] font-medium text-emas">Pustaka Kurasi Terbaik</span>
                    </div>
                    <h1 class="font-serif text-5xl lg:text-7xl font-bold leading-[1.08] mb-6">
                        Temukan Pustaka &<br>
                        <span class="gold-text">Karya</span><br>
                        Pilihan
                    </h1>
                    <p class="text-base leading-relaxed mb-10 max-w-md text-[#f0ebe0]/65">
                        Kurasi buku berkualitas dari penulis ternama. Nikmati kemudahan pengiriman langsung ke kediaman Anda.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="register.php" class="btn-gold px-8 py-4 rounded-xl font-semibold text-sm text-darkBg shadow-lg">
                            Mulai Berbelanja →
                        </a>
                        <a href="javascript:void(0)" onclick="openInfoModal('Tentang Kami', 'Cell’s Bookshelf adalah platform pustaka kurasi yang berdedikasi menyediakan karya literasi berkualitas tinggi dari penulis lokal dan internasional.')" class="px-8 py-4 rounded-xl text-sm font-semibold transition-all border border-[#f0ebe0]/20 text-[#f0ebe0]/80 hover:border-emas/50 hover:text-emas text-center">
                            Tentang Kami
                        </a>
                    </div>

                    <!-- Stats row -->
                    <div class="flex gap-8 mt-12 pt-8 border-t border-white/10">
                        <div>
                            <div class="font-serif text-2xl font-bold gold-text">2.400+</div>
                            <div class="text-xs mt-0.5 text-[#f0ebe0]/45">Koleksi Pustaka</div>
                        </div>
                        <div>
                            <div class="font-serif text-2xl font-bold gold-text">4.800+</div>
                            <div class="text-xs mt-0.5 text-[#f0ebe0]/45">Anggota Terdaftar</div>
                        </div>
                        <div>
                            <div class="font-serif text-2xl font-bold gold-text">COD</div>
                            <div class="text-xs mt-0.5 text-[#f0ebe0]/45">Bayar di Tempat</div>
                        </div>
                    </div>
                </div>

                <!-- Right floating book stack layout -->
                <div class="hidden lg:flex items-end justify-center gap-5 h-96">
                    <div class="float-1">
                        <div class="rounded-xl overflow-hidden shadow-2xl w-[130px] border border-emas/20 bg-[#100814]/90">
                            <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?w=300&h=420&fit=crop&auto=format" alt="Book 1" class="w-full h-48 object-cover">
                            <div class="p-3 bg-[#0a0814]/90">
                                <div class="text-xs font-serif font-bold truncate text-[#f0ebe0]">Midnight Library</div>
                                <div class="text-xs mt-0.5 text-emas">Rp 85.000</div>
                            </div>
                        </div>
                    </div>
                    <div class="float-2 mb-8">
                        <div class="rounded-xl overflow-hidden shadow-2xl w-[150px] border border-emas/25 bg-[#100814]/95">
                            <img src="https://images.unsplash.com/photo-1529589941132-43606325dfb4?w=500&h=700&fit=crop&auto=format" alt="book" class="w-full h-56 object-cover">
                            <div class="p-3 bg-[#0a0814]/95">
                                <div class="text-xs font-serif font-bold text-[#f0ebe0]">Koleksi Pilihan</div>
                                <div class="text-xs mt-0.5 text-emas">Edisi Terbatas</div>
                            </div>
                        </div>
                    </div>
                    <div class="float-3">
                        <div class="rounded-xl overflow-hidden shadow-2xl w-[125px] border border-emas/20 bg-[#100814]/90">
                            <img src="https://images.unsplash.com/photo-1614544048536-0d28caf77f41?w=300&h=420&fit=crop&auto=format" alt="Book 2" class="w-full h-44 object-cover">
                            <div class="p-3 bg-[#0a0814]/90">
                                <div class="text-xs font-serif font-bold truncate text-[#f0ebe0]">Dune</div>
                                <div class="text-xs mt-0.5 text-emas">Rp 120.000</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scroll indicator -->
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 opacity-50">
                <div class="text-[10px] tracking-widest uppercase text-emas">Scroll</div>
                <div class="w-px h-10 bg-gradient-to-b from-[#d4a853] to-transparent"></div>
            </div>
        </section>

        <!-- Marquee Strip banner -->
        <div class="bg-gradient-to-r from-emas-hover to-emas py-3.5 overflow-hidden">
            <div class="flex gap-12 text-xs font-semibold uppercase tracking-widest whitespace-nowrap text-darkBg animate-marquee">
                <span>✦ Koleksi Edisi Pilihan</span><span>✦ Bebas Biaya Kirim</span><span>✦ Layanan COD Terjamin</span><span>✦ Pengemasan Aman</span><span>✦ Jaminan Keaslian Pustaka</span>
                <span>✦ Koleksi Edisi Pilihan</span><span>✦ Bebas Biaya Kirim</span><span>✦ Layanan COD Terjamin</span><span>✦ Pengemasan Aman</span><span>✦ Jaminan Keaslian Pustaka</span>
                <span>✦ Koleksi Edisi Pilihan</span><span>✦ Bebas Biaya Kirim</span><span>✦ Layanan COD Terjamin</span><span>✦ Pengemasan Aman</span><span>✦ Jaminan Keaslian Pustaka</span>
                <span>✦ Koleksi Edisi Pilihan</span><span>✦ Bebas Biaya Kirim</span><span>✦ Layanan COD Terjamin</span><span>✦ Pengemasan Aman</span><span>✦ Jaminan Keaslian Pustaka</span>
            </div>
        </div>

        <!-- Best Seller Section -->
        <section class="max-w-6xl mx-auto px-6 py-24">
            <div class="flex items-end justify-between mb-12">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="h-px w-8 bg-emas"></div>
                        <span class="text-xs uppercase tracking-[0.25em] text-emas">Rekomendasi Utama</span>
                    </div>
                    <h2 class="font-serif text-4xl font-bold text-white">Koleksi Pilihan Minggu Ini</h2>
                </div>
                <a href="login.php" class="text-sm text-emas hover:underline hidden md:block">Lihat Semua Katalog →</a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <?php
                $books_q = "SELECT b.*, c.nama_kategori FROM books b LEFT JOIN categories c ON b.id_kategori = c.id_kategori LIMIT 6";
                $books_res = mysqli_query($conn, $books_q);
                while ($b = mysqli_fetch_assoc($books_res)):
                    $price_fmt = 'Rp ' . number_format($b['harga'], 0, ',', '.');
                ?>
                    <div class="book-card card-hover cursor-pointer rounded-2xl overflow-hidden bg-white/[0.04] border border-white/[0.08]"
                         data-id="<?php echo $b['id_buku']; ?>"
                         data-judul="<?php echo htmlspecialchars($b['judul'], ENT_QUOTES, 'UTF-8'); ?>"
                         data-penulis="<?php echo htmlspecialchars($b['penulis'], ENT_QUOTES, 'UTF-8'); ?>"
                         data-kategori="<?php echo htmlspecialchars($b['nama_kategori'], ENT_QUOTES, 'UTF-8'); ?>"
                         data-harga="<?php echo $b['harga']; ?>"
                         data-stok="<?php echo $b['stok']; ?>"
                         data-gambar="<?php echo htmlspecialchars($b['gambar'], ENT_QUOTES, 'UTF-8'); ?>"
                         data-deskripsi="<?php echo htmlspecialchars($b['deskripsi'], ENT_QUOTES, 'UTF-8'); ?>">
                        <div class="relative overflow-hidden bg-[#1a1528]">
                            <img src="<?php echo htmlspecialchars($b['gambar']); ?>" alt="Cover" class="w-full h-44 object-cover transition-transform duration-500 hover:scale-105">
                            <span class="absolute top-2 left-2 text-[9px] px-2 py-0.5 rounded-full font-medium bg-emas text-darkBg">
                                <?php echo htmlspecialchars($b['nama_kategori']); ?>
                            </span>
                            <span class="absolute top-2 right-2 text-[9px] px-2 py-0.5 rounded-full font-semibold <?php echo $b['stok'] > 0 ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-red-500/20 text-red-300 border border-red-500/30'; ?>">
                                <?php echo $b['stok'] > 0 ? 'Stok: ' . $b['stok'] : 'Stok Habis'; ?>
                            </span>
                        </div>
                        <div class="p-3">
                            <h6 class="font-serif font-bold text-xs leading-snug text-white line-clamp-2 mb-0.5"><?php echo htmlspecialchars($b['judul']); ?></h6>
                            <p class="text-[10px] text-[#f0ebe0]/40 mb-2"><?php echo htmlspecialchars($b['penulis']); ?></p>
                            <div class="font-bold text-xs gold-text"><?php echo $price_fmt; ?></div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

        <!-- Category Section -->
        <section class="bg-white/[0.02] border-y border-white/[0.05] py-24">
            <div class="max-w-6xl mx-auto px-6">
                <div class="flex items-center gap-3 mb-3">
                    <div class="h-px w-8 bg-emas"></div>
                    <span class="text-xs uppercase tracking-[0.25em] text-emas">Eksplorasi Katalog</span>
                </div>
                <h2 class="font-serif text-4xl font-bold mb-12 text-white">Kategori Pustaka</h2>

                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
                    <?php
                    $cat_icons = [
                        'Fiction' => '📖',
                        'Self-Help' => '🌱',
                        'History' => '🏛️',
                        'Science Fiction' => '🚀',
                        'Finance' => '💰',
                        'Psychology' => '🧠',
                        'Classic' => '🎭'
                    ];
                    $res_cats = mysqli_query($conn, "SELECT c.*, COUNT(b.id_buku) as count_books FROM categories c LEFT JOIN books b ON c.id_kategori = b.id_kategori GROUP BY c.id_kategori");
                    while ($cat = mysqli_fetch_assoc($res_cats)):
                        $icon = isset($cat_icons[$cat['nama_kategori']]) ? $cat_icons[$cat['nama_kategori']] : '📚';
                    ?>
                        <a href="login.php" class="cursor-pointer rounded-2xl p-5 text-left bg-white/[0.04] border border-white/[0.08] hover:bg-[rgba(212,168,83,0.1)] hover:border-emas/40 hover:-translate-y-1 transition-all duration-300 group">
                            <div class="text-2xl mb-3"><?php echo $icon; ?></div>
                            <h6 class="text-xs font-semibold text-white mb-0.5"><?php echo htmlspecialchars($cat['nama_kategori']); ?></h6>
                            <small class="text-[10px] text-[#f0ebe0]/35"><?php echo $cat['count_books']; ?> pustaka</small>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>

        <!-- Steps Section (Cara Belanja) -->
        <section class="max-w-6xl mx-auto px-6 py-24">
            <div class="text-center mb-16">
                <div class="flex items-center justify-center gap-3 mb-3">
                    <div class="h-px w-8 bg-emas"></div>
                    <span class="text-xs uppercase tracking-[0.25em] text-emas">Layanan Konsierse</span>
                    <div class="h-px w-8 bg-emas"></div>
                </div>
                <h2 class="font-serif text-4xl font-bold text-white">Komitmen Layanan Kami</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative">
                <div class="absolute top-8 left-[12.5%] right-[12.5%] h-px hidden md:block bg-gradient-to-r from-transparent via-[#d4a853]/30 to-transparent"></div>
                <div class="relative text-center">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-5 relative bg-emas/10 border border-emas/30">
                        <i data-lucide="user-plus" class="w-6 h-6 text-emas"></i>
                        <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold bg-emas text-darkBg">01</span>
                    </div>
                    <div class="font-serif font-bold mb-2 text-white">Keanggotaan</div>
                    <div class="text-xs leading-relaxed text-[#f0ebe0]/45">Buat akun anggota untuk pengalaman berbelanja personal.</div>
                </div>
                <div class="relative text-center">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-5 relative bg-emas/10 border border-emas/30">
                        <i data-lucide="search" class="w-6 h-6 text-emas"></i>
                        <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold bg-emas text-darkBg">02</span>
                    </div>
                    <div class="font-serif font-bold mb-2 text-white">Eksplorasi Karya</div>
                    <div class="text-xs leading-relaxed text-[#f0ebe0]/45">Jelajahi karya kurasi terbaik sesuai minat dan inspirasi Anda.</div>
                </div>
                <div class="relative text-center">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-5 relative bg-emas/10 border border-emas/30">
                        <i data-lucide="shopping-bag" class="w-6 h-6 text-emas"></i>
                        <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold bg-emas text-darkBg">03</span>
                    </div>
                    <div class="font-serif font-bold mb-2 text-white">Penyimpanan Koleksi</div>
                    <div class="text-xs leading-relaxed text-[#f0ebe0]/45">Pilih karya favorit Anda dan simpan ke keranjang belanja.</div>
                </div>
                <div class="relative text-center">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-5 relative bg-emas/10 border border-emas/30">
                        <i data-lucide="truck" class="w-6 h-6 text-emas"></i>
                        <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold bg-emas text-darkBg">04</span>
                    </div>
                    <div class="font-serif font-bold mb-2 text-white">Pembayaran di Tempat</div>
                    <div class="text-xs leading-relaxed text-[#f0ebe0]/45">Fleksibilitas pembayaran tunai di tempat saat pesanan tiba.</div>
                </div>
            </div>
        </section>

        <!-- CTA Banner Banner -->
        <section class="relative overflow-hidden mx-6 mb-24 rounded-3xl h-64">
            <img src="https://images.unsplash.com/photo-1768401124242-09556bcfa0f6?w=1200&h=400&fit=crop&auto=format" alt="Library" class="w-full h-full object-cover">
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center bg-black/75 p-6">
                <h2 class="font-serif text-3xl lg:text-4xl font-bold mb-4 text-white">
                    Jelajahi <span class="gold-text">Pustaka Lengkap</span> Kami
                </h2>
                <p class="text-sm mb-6 text-[#f0ebe0]/60 max-w-md">Daftar menjadi anggota dan mulai lengkapi koleksi pustaka pribadi Anda.</p>
                <a href="register.php" class="btn-gold px-10 py-4 rounded-xl font-semibold text-sm text-darkBg shadow-lg">
                    Bergabung Sekarang
                </a>
            </div>
        </section>

    <?php else: ?>
        <!-- ================= LOGGED IN USER DASHBOARD ================= -->
        <div class="container mx-auto px-5 py-6">
            
            <!-- Mobile Search Bar Link -->
            <div class="relative mb-6">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-[#f0ebe0]/30">🔍</span>
                <input type="text" placeholder="Cari judul atau penulis..." class="w-full pl-10 pr-4 py-3.5 rounded-2xl text-xs outline-none bg-white/[0.05] border border-white/[0.08] text-[#f0ebe0] focus:border-emas transition-all cursor-pointer" onclick="window.location='search.php'">
            </div>

            <!-- Hero Banner -->
            <div class="relative rounded-3xl overflow-hidden mb-8 h-40 sm:h-52">
                <img src="https://images.unsplash.com/photo-1768401124242-09556bcfa0f6?w=1000&h=400&fit=crop&auto=format" alt="Books Hero" class="w-full h-full object-cover">
                <div class="absolute inset-0 flex flex-col justify-center px-6 sm:px-12 bg-gradient-to-r from-[#0a0814] via-[#0a0814]/80 to-transparent">
                    <h2 class="font-serif text-lg sm:text-2xl font-bold mb-1 text-white">Koleksi Terbaru</h2>
                    <p class="text-[10px] sm:text-xs text-[#f0ebe0]/60 mb-3">Buku-buku pilihan minggu ini</p>
                    <a href="search.php" class="text-[10px] sm:text-xs px-4 py-2 rounded-xl w-fit font-semibold btn-gold text-darkBg shadow-md">
                        Jelajahi →
                    </a>
                </div>
            </div>

            <!-- Category Chips -->
            <div class="mb-6">
                <p class="text-[10px] uppercase tracking-widest font-semibold text-emas mb-3">Kategori</p>
                <div class="flex gap-2 overflow-x-auto pb-1" style="scrollbar-width: none;">
                    <a href="search.php" class="flex-shrink-0 px-4 py-2 rounded-xl text-xs font-semibold bg-gradient-to-r from-emas-hover to-emas text-darkBg">
                        Semua
                    </a>
                    <?php
                    $res_cats = mysqli_query($conn, "SELECT * FROM categories");
                    while ($cat = mysqli_fetch_assoc($res_cats)):
                    ?>
                        <a href="search.php?kategori=<?php echo urlencode($cat['nama_kategori']); ?>" class="flex-shrink-0 px-4 py-2 rounded-xl text-xs font-semibold bg-white/[0.05] text-[#f0ebe0]/50 border border-white/[0.08] hover:border-emas/50 hover:text-white transition-all">
                            <?php echo htmlspecialchars($cat['nama_kategori']); ?>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Book Grid (Buku Terbaru) -->
            <p class="text-[10px] uppercase tracking-widest font-semibold text-emas mb-3">Buku Terbaru</p>
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-5 gap-4">
                <?php
                // Fetch books from database
                $query = "SELECT b.*, c.nama_kategori FROM books b LEFT JOIN categories c ON b.id_kategori = c.id_kategori";
                $result = mysqli_query($conn, $query);
                while($buku = mysqli_fetch_assoc($result)):
                    $price_fmt = 'Rp ' . number_format($buku['harga'], 0, ',', '.');
                ?>
                    <?php
                    $is_habis = intval($buku['stok']) <= 0;
                    ?>
                    <div class="book-card cursor-pointer rounded-2xl overflow-hidden bg-white/[0.04] border border-white/[0.07] card-hover p-3 flex flex-col justify-between"
                         data-id="<?php echo $buku['id_buku']; ?>"
                         data-judul="<?php echo htmlspecialchars($buku['judul'], ENT_QUOTES, 'UTF-8'); ?>"
                         data-penulis="<?php echo htmlspecialchars($buku['penulis'], ENT_QUOTES, 'UTF-8'); ?>"
                         data-kategori="<?php echo htmlspecialchars($buku['nama_kategori'], ENT_QUOTES, 'UTF-8'); ?>"
                         data-harga="<?php echo $buku['harga']; ?>"
                         data-stok="<?php echo $buku['stok']; ?>"
                         data-gambar="<?php echo htmlspecialchars($buku['gambar'], ENT_QUOTES, 'UTF-8'); ?>"
                         data-deskripsi="<?php echo htmlspecialchars($buku['deskripsi'], ENT_QUOTES, 'UTF-8'); ?>">
                        <div class="relative aspect-[3/4] rounded-xl overflow-hidden bg-[#1a1528] mb-3">
                            <img src="<?php echo htmlspecialchars($buku['gambar']); ?>" alt="Cover" class="w-full h-full object-cover">
                            <span class="absolute top-2 left-2 text-[8px] font-bold px-2 py-0.5 rounded bg-emas text-darkBg">
                                <?php echo htmlspecialchars($buku['nama_kategori']); ?>
                            </span>
                            <span class="absolute top-2 right-2 text-[8px] font-bold px-2 py-0.5 rounded <?php echo !$is_habis ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-red-500/20 text-red-300 border border-red-500/30'; ?>">
                                <?php echo !$is_habis ? 'Stok: ' . $buku['stok'] : 'Stok Habis'; ?>
                            </span>
                        </div>
                        <div>
                            <h6 class="font-serif font-bold text-xs leading-snug text-white line-clamp-1 mb-0.5"><?php echo htmlspecialchars($buku['judul']); ?></h6>
                            <p class="text-[10px] text-[#f0ebe0]/40 mb-3"><?php echo htmlspecialchars($buku['penulis']); ?></p>
                            <div class="flex items-center justify-between mt-auto">
                                <span class="text-xs font-bold text-emas"><?php echo $price_fmt; ?></span>
                                <?php if (!$is_habis): ?>
                                    <button onclick="event.stopPropagation(); window.location='cart.php?id=<?php echo $buku['id_buku']; ?>'" class="btn-direct-buy text-[10px] px-2.5 py-1 rounded-lg bg-emas/10 text-emas border border-emas/20 hover:bg-emas hover:text-darkBg transition-all font-semibold">+ Beli</button>
                                <?php else: ?>
                                    <button disabled onclick="event.stopPropagation();" class="text-[10px] px-2.5 py-1 rounded-lg bg-white/5 text-[#f0ebe0]/30 border border-white/10 cursor-not-allowed font-semibold">Habis</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php
include 'footer.php';
?>
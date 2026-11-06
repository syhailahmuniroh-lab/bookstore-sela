<?php
include 'header.php';

// Fetch keyword and category filters safely (initial load)
$keyword = isset($_GET['keyword']) ? mysqli_real_escape_string($conn, $_GET['keyword']) : '';
$kategori = isset($_GET['kategori']) ? mysqli_real_escape_string($conn, $_GET['kategori']) : '';

// Build initial search query
$query = "SELECT b.*, c.nama_kategori FROM books b LEFT JOIN categories c ON b.id_kategori = c.id_kategori WHERE 1=1";

if (!empty($keyword)) {
    $query .= " AND (b.judul LIKE '%$keyword%' OR b.penulis LIKE '%$keyword%')";
}

if (!empty($kategori) && $kategori != 'Semua') {
    $query .= " AND c.nama_kategori = '$kategori'";
}

$query .= " ORDER BY b.id_buku DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}

$jumlah_buku = mysqli_num_rows($result);
?>

<main class="flex-1">
    <div class="container mx-auto px-5 py-6">
        <h3 class="font-serif font-bold text-xl mb-4 text-white">Cari Buku</h3>
        
        <!-- Search Input -->
        <div class="relative mb-4">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-[#f0ebe0]/30">🔍</span>
            <input type="text" id="searchInput" placeholder="Cari judul atau penulis..." 
                   class="w-full pl-10 pr-4 py-3.5 rounded-2xl text-xs outline-none bg-white/[0.05] border border-white/[0.08] text-[#f0ebe0] focus:border-emas transition-all" 
                   value="<?php echo htmlspecialchars($keyword); ?>">
        </div>

        <!-- Category Filter Chips (Dynamic Buttons) -->
        <div class="flex gap-2 overflow-x-auto pb-3 mb-4" style="scrollbar-width: none;">
            <button type="button" onclick="filterCategory(this, 'Semua')" 
                    class="category-chip flex-shrink-0 px-4 py-2 rounded-xl text-xs font-semibold transition-all <?php echo (empty($kategori) || $kategori == 'Semua') ? 'category-chip-active bg-gradient-to-r from-emas-hover to-emas text-darkBg' : 'bg-white/[0.05] text-[#f0ebe0]/50 border border-white/[0.08]'; ?>"
                    data-category="Semua">
                Semua
            </button>
            <?php
            $res_cats = mysqli_query($conn, "SELECT * FROM categories");
            while ($cat = mysqli_fetch_assoc($res_cats)):
                $cat_name = $cat['nama_kategori'];
                $is_active = ($kategori === $cat_name);
            ?>
                <button type="button" onclick="filterCategory(this, '<?php echo addslashes($cat_name); ?>')" 
                        class="category-chip flex-shrink-0 px-4 py-2 rounded-xl text-xs font-semibold transition-all <?php echo $is_active ? 'category-chip-active bg-gradient-to-r from-emas-hover to-emas text-darkBg' : 'bg-white/[0.05] text-[#f0ebe0]/50 border border-white/[0.08]'; ?>"
                        data-category="<?php echo htmlspecialchars($cat_name); ?>">
                    <?php echo htmlspecialchars($cat_name); ?>
                </button>
            <?php endwhile; ?>
        </div>

        <!-- Dynamic Results Container -->
        <div id="searchResultsContainer">
            <p class="text-xs text-[#f0ebe0]/40 mb-4"><span id="bookCount"><?php echo $jumlah_buku; ?></span> buku ditemukan</p>

            <div class="space-y-3" id="resultsContainer">
                <?php if ($jumlah_buku > 0): ?>
                    <?php 
                    while ($buku = mysqli_fetch_assoc($result)): 
                        $price_fmt = 'Rp ' . number_format($buku['harga'], 0, ',', '.');
                        $stok_val = intval($buku['stok']);
                        $is_habis = $stok_val <= 0;
                    ?>
                        <div class="book-card cursor-pointer flex gap-4 rounded-2xl p-4 bg-white/[0.04] border border-white/[0.07] hover:border-emas/30 transition-all"
                             data-id="<?php echo $buku['id_buku']; ?>"
                             data-judul="<?php echo htmlspecialchars($buku['judul'], ENT_QUOTES, 'UTF-8'); ?>"
                             data-penulis="<?php echo htmlspecialchars($buku['penulis'], ENT_QUOTES, 'UTF-8'); ?>"
                             data-kategori="<?php echo htmlspecialchars($buku['nama_kategori'], ENT_QUOTES, 'UTF-8'); ?>"
                             data-harga="<?php echo $buku['harga']; ?>"
                             data-stok="<?php echo $buku['stok']; ?>"
                             data-gambar="<?php echo htmlspecialchars($buku['gambar'], ENT_QUOTES, 'UTF-8'); ?>"
                             data-deskripsi="<?php echo htmlspecialchars($buku['deskripsi'], ENT_QUOTES, 'UTF-8'); ?>">
                            <img src="<?php echo htmlspecialchars($buku['gambar']); ?>" alt="Cover" class="w-14 h-20 object-cover rounded-xl flex-shrink-0 bg-[#1a1528]">
                            <div class="flex-1 min-w-0 flex flex-col justify-between">
                                <div>
                                    <h6 class="font-serif font-bold text-sm leading-snug text-white truncate"><?php echo htmlspecialchars($buku['judul']); ?></h6>
                                    <p class="text-[11px] text-[#f0ebe0]/40 mt-0.5"><?php echo htmlspecialchars($buku['penulis']); ?></p>
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="inline-block mt-2 text-[9px] px-2 py-0.5 rounded-lg bg-emas/10 text-emas border border-emas/15"><?php echo htmlspecialchars($buku['nama_kategori']); ?></span>
                                        <?php if (!$is_habis): ?>
                                            <span class="inline-block mt-2 text-[9px] px-2 py-0.5 rounded-lg bg-emerald-500/15 text-emerald-400 border border-emerald-500/20 font-medium">Stok: <?php echo $stok_val; ?></span>
                                        <?php else: ?>
                                            <span class="inline-block mt-2 text-[9px] px-2 py-0.5 rounded-lg bg-red-500/15 text-red-400 border border-red-500/20 font-medium">Stok Habis</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="font-bold text-sm text-emas"><?php echo $price_fmt; ?></span>
                                    <?php if (!$is_habis): ?>
                                        <button onclick="event.stopPropagation(); window.location='cart.php?id=<?php echo $buku['id_buku']; ?>'" 
                                                class="btn-direct-buy text-xs px-4 py-1.5 rounded-xl btn-gold text-darkBg font-semibold shadow-md">
                                            + Beli
                                        </button>
                                    <?php else: ?>
                                        <button disabled onclick="event.stopPropagation();" 
                                                class="text-xs px-4 py-1.5 rounded-xl bg-white/5 text-[#f0ebe0]/30 border border-white/10 cursor-not-allowed font-semibold">
                                            Habis
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="text-center py-20 text-[#f0ebe0]/30">
                        <div class="text-5xl mb-3">🔍</div>
                        <div class="text-sm">Buku tidak ditemukan</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script>
    document.getElementById('searchInput').addEventListener('input', function() {
        const keyword = this.value;
        const activeChip = document.querySelector('.category-chip-active');
        const category = activeChip ? activeChip.getAttribute('data-category') : 'Semua';
        performSearch(keyword, category);
    });

    function filterCategory(element, category) {
        // Reset active state of all chips
        document.querySelectorAll('.category-chip').forEach(btn => {
            btn.classList.remove('category-chip-active', 'bg-gradient-to-r', 'from-emas-hover', 'to-emas', 'text-darkBg');
            btn.classList.add('bg-white/[0.05]', 'text-[#f0ebe0]/50', 'border', 'border-white/[0.08]');
        });

        // Set active state on clicked chip
        element.classList.add('category-chip-active', 'bg-gradient-to-r', 'from-emas-hover', 'to-emas', 'text-darkBg');
        element.classList.remove('bg-white/[0.05]', 'text-[#f0ebe0]/50', 'border', 'border-white/[0.08]');

        const keyword = document.getElementById('searchInput').value;
        performSearch(keyword, category);
    }

    function performSearch(keyword, category) {
        const resultsContainer = document.getElementById('resultsContainer');
        const bookCountSpan = document.getElementById('bookCount');

        // Show a subtle shimmer loading effect in results
        resultsContainer.style.opacity = '0.5';

        fetch('search_ajax.php?keyword=' + encodeURIComponent(keyword) + '&kategori=' + encodeURIComponent(category))
            .then(response => response.json())
            .then(data => {
                resultsContainer.style.opacity = '1';
                bookCountSpan.innerText = data.count;
                resultsContainer.innerHTML = data.html;
            })
            .catch(err => {
                console.error('AJAX Search error:', err);
                resultsContainer.style.opacity = '1';
            });
    }
</script>

<?php
include 'footer.php';
?>
<!-- Dedicated Book Detail Modal Component -->
<div id="bookModal" class="fixed inset-0 z-[9999] flex items-end sm:items-center justify-center hidden bg-black/80 backdrop-blur-md transition-opacity duration-300 p-0 sm:p-4">
    <div class="relative w-full max-w-lg rounded-t-3xl sm:rounded-3xl p-6 bg-[#14112a] border border-[rgba(212,168,83,0.3)] shadow-2xl animate-fade-up">
        <!-- Close Button -->
        <button onclick="closeGlobalBookModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white/50 hover:text-white hover:border-emas/40 transition-all text-lg">&times;</button>
        <!-- Mobile Handle -->
        <div class="w-10 h-1 rounded-full mx-auto mb-5 bg-white/20 sm:hidden" onclick="closeGlobalBookModal()"></div>

        <!-- Book Main Header Info -->
        <div class="flex gap-5 mb-4 pr-6">
            <img id="bmCover" src="" alt="Cover Buku" class="w-24 h-36 object-cover rounded-2xl flex-shrink-0 bg-[#1a1528] border border-white/10 shadow-2xl">
            <div class="flex flex-col justify-between flex-1 min-w-0">
                <div>
                    <div class="flex items-center gap-2 mb-2 flex-wrap">
                        <span id="bmCategory" class="text-[10px] px-2.5 py-0.5 rounded-lg inline-block bg-emas/15 text-emas border border-emas/20 font-medium">Kategori</span>
                        <span id="bmStock" class="text-[10px] px-2.5 py-0.5 rounded-lg inline-block font-medium">Stok: 25</span>
                    </div>
                    <h4 id="bmTitle" class="font-serif font-bold text-lg leading-snug mb-1 text-white truncate">Judul Buku</h4>
                    <p id="bmAuthor" class="text-xs text-[#f0ebe0]/45 mb-2 truncate">Penulis</p>
                </div>
                <div>
                    <div id="bmPrice" class="font-bold text-xl gold-text">Rp 0</div>
                </div>
            </div>
        </div>

        <!-- Description Box -->
        <p id="bmDescription" class="text-xs leading-relaxed mb-6 text-[#f0ebe0]/70 max-h-32 overflow-y-auto pr-1 bg-white/[0.03] p-3.5 rounded-2xl border border-white/[0.06]">
            Deskripsi buku...
        </p>

        <!-- Action Button -->
        <a id="bmBuyBtn" href="#" class="block w-full py-3.5 text-center rounded-2xl font-bold text-sm btn-gold text-darkBg shadow-lg transition-all">
            + Tambah ke Keranjang
        </a>
    </div>
</div>

<!-- Global Event Delegation & Control Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Listen for clicks on the entire document
    document.body.addEventListener('click', function(e) {
        // Find if a book-card was clicked
        const card = e.target.closest('.book-card');
        
        // Ensure card exists and click was NOT inside direct "+ Beli" button
        if (card && !e.target.closest('.btn-direct-buy')) {
            const id = card.getAttribute('data-id');
            const judul = card.getAttribute('data-judul');
            const penulis = card.getAttribute('data-penulis');
            const kategori = card.getAttribute('data-kategori');
            const harga = card.getAttribute('data-harga');
            const gambar = card.getAttribute('data-gambar');
            const deskripsi = card.getAttribute('data-deskripsi');
            const stok = card.getAttribute('data-stok');

            if (judul) {
                openGlobalBookModal(id, judul, penulis, kategori, harga, gambar, deskripsi, stok);
            }
        }
    });

    // Close on clicking backdrop
    const modal = document.getElementById('bookModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeGlobalBookModal();
            }
        });
    }
});

function openGlobalBookModal(id, judul, penulis, kategori, harga, gambar, deskripsi, stok) {
    const coverEl = document.getElementById('bmCover');
    const titleEl = document.getElementById('bmTitle');
    const authorEl = document.getElementById('bmAuthor');
    const catEl = document.getElementById('bmCategory');
    const stockEl = document.getElementById('bmStock');
    const priceEl = document.getElementById('bmPrice');
    const descEl = document.getElementById('bmDescription');
    const buyBtn = document.getElementById('bmBuyBtn');

    const stokNum = (stok !== undefined && stok !== null && stok !== '') ? parseInt(stok) : 25;
    const isOutOfStock = stokNum <= 0;

    if (coverEl) coverEl.src = gambar || '';
    if (titleEl) titleEl.textContent = judul || 'Judul Buku';
    if (authorEl) authorEl.textContent = penulis || 'Penulis';
    if (catEl) catEl.textContent = kategori || 'Pustaka';
    if (priceEl) priceEl.textContent = 'Rp ' + Number(harga || 0).toLocaleString('id-ID');
    if (descEl) descEl.textContent = deskripsi || 'Tidak ada deskripsi.';

    if (stockEl) {
        if (isOutOfStock) {
            stockEl.className = 'text-[10px] px-2.5 py-0.5 rounded-lg inline-block font-medium bg-red-500/15 text-red-400 border border-red-500/20';
            stockEl.textContent = 'Stok Habis';
        } else {
            stockEl.className = 'text-[10px] px-2.5 py-0.5 rounded-lg inline-block font-medium bg-emerald-500/15 text-emerald-400 border border-emerald-500/20';
            stockEl.textContent = 'Tersedia: ' + stokNum + ' buku';
        }
    }

    if (buyBtn) {
        if (isOutOfStock) {
            buyBtn.textContent = 'Stok Habis';
            buyBtn.href = '#';
            buyBtn.className = 'block w-full py-3.5 text-center rounded-2xl font-bold text-sm bg-white/10 text-[#f0ebe0]/30 cursor-not-allowed pointer-events-none shadow-none';
        } else {
            buyBtn.textContent = '+ Tambah ke Keranjang';
            buyBtn.href = 'cart.php?id=' + id;
            buyBtn.className = 'block w-full py-3.5 text-center rounded-2xl font-bold text-sm btn-gold text-darkBg shadow-lg transition-all';
        }
    }

    const modal = document.getElementById('bookModal');
    if (modal) {
        modal.style.display = 'flex';
        modal.classList.remove('hidden');
    }
}

function closeGlobalBookModal() {
    const modal = document.getElementById('bookModal');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
    }
}
</script>

<?php
include 'koneksi.php';

$keyword = isset($_GET['keyword']) ? mysqli_real_escape_string($conn, $_GET['keyword']) : '';
$kategori = isset($_GET['kategori']) ? mysqli_real_escape_string($conn, $_GET['kategori']) : '';

// Build search query
$query = "SELECT b.*, c.nama_kategori FROM books b LEFT JOIN categories c ON b.id_kategori = c.id_kategori WHERE 1=1";

if (!empty($keyword)) {
    $query .= " AND (b.judul LIKE '%$keyword%' OR b.penulis LIKE '%$keyword%')";
}

if (!empty($kategori) && $kategori != 'Semua' && $kategori != 'all') {
    $query .= " AND c.nama_kategori = '$kategori'";
}

$query .= " ORDER BY b.id_buku DESC";
$result = mysqli_query($conn, $query);

$html = '';
$count = 0;

if ($result) {
    $count = mysqli_num_rows($result);
    if ($count > 0) {
        while ($buku = mysqli_fetch_assoc($result)) {
            $price_fmt = 'Rp ' . number_format($buku['harga'], 0, ',', '.');
            $stok_val = intval($buku['stok']);
            $is_habis = $stok_val <= 0;
            
            $stock_badge = !$is_habis
                ? '<span class="inline-block mt-2 ml-1 text-[9px] px-2 py-0.5 rounded-lg bg-emerald-500/15 text-emerald-400 border border-emerald-500/20 font-medium">Stok: ' . $stok_val . '</span>'
                : '<span class="inline-block mt-2 ml-1 text-[9px] px-2 py-0.5 rounded-lg bg-red-500/15 text-red-400 border border-red-500/20 font-medium">Stok Habis</span>';

            $btn_action = !$is_habis
                ? '<button onclick="event.stopPropagation(); window.location=\'cart.php?id=' . $buku['id_buku'] . '\'" class="btn-direct-buy text-xs px-4 py-1.5 rounded-xl btn-gold text-darkBg font-semibold shadow-md">+ Beli</button>'
                : '<button disabled onclick="event.stopPropagation();" class="text-xs px-4 py-1.5 rounded-xl bg-white/5 text-[#f0ebe0]/30 border border-white/10 cursor-not-allowed font-semibold">Habis</button>';

            $html .= '
            <div class="book-card cursor-pointer flex gap-4 rounded-2xl p-4 bg-white/[0.04] border border-white/[0.07] hover:border-emas/30 transition-all"
                 data-id="' . $buku['id_buku'] . '"
                 data-judul="' . htmlspecialchars($buku['judul'], ENT_QUOTES, 'UTF-8') . '"
                 data-penulis="' . htmlspecialchars($buku['penulis'], ENT_QUOTES, 'UTF-8') . '"
                 data-kategori="' . htmlspecialchars($buku['nama_kategori'], ENT_QUOTES, 'UTF-8') . '"
                 data-harga="' . $buku['harga'] . '"
                 data-stok="' . $buku['stok'] . '"
                 data-gambar="' . htmlspecialchars($buku['gambar'], ENT_QUOTES, 'UTF-8') . '"
                 data-deskripsi="' . htmlspecialchars($buku['deskripsi'], ENT_QUOTES, 'UTF-8') . '">
                <img src="' . htmlspecialchars($buku['gambar']) . '" alt="Cover" class="w-14 h-20 object-cover rounded-xl flex-shrink-0 bg-[#1a1528]">
                <div class="flex-1 min-w-0 flex flex-col justify-between">
                    <div>
                        <h6 class="font-serif font-bold text-sm leading-snug text-white truncate">' . htmlspecialchars($buku['judul']) . '</h6>
                        <p class="text-[11px] text-[#f0ebe0]/40 mt-0.5">' . htmlspecialchars($buku['penulis']) . '</p>
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="inline-block mt-2 text-[9px] px-2 py-0.5 rounded-lg bg-emas/10 text-emas border border-emas/15">' . htmlspecialchars($buku['nama_kategori']) . '</span>
                            ' . $stock_badge . '
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-2">
                        <span class="font-bold text-sm text-emas">' . $price_fmt . '</span>
                        ' . $btn_action . '
                    </div>
                </div>
            </div>';
        }
    } else {
        $html = '
        <div class="text-center py-20 text-[#f0ebe0]/40">
            <div class="text-5xl mb-3">🔍</div>
            <div class="text-sm font-serif font-bold text-white mb-1">Karya Tidak Ditemukan</div>
            <div class="text-xs text-[#f0ebe0]/50">Coba gunakan kata kunci atau kategori pustaka yang berbeda.</div>
        </div>';
    }
} else {
    $html = '<div class="text-center py-10 text-red-400">Database Query Error</div>';
}

header('Content-Type: application/json');
echo json_encode([
    'count' => $count,
    'html' => $html
]);
?>

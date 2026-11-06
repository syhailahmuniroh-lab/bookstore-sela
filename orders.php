<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php';

// Check if user is logged in
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

include 'header.php';
$id_user = $_SESSION['id_user'];

// Query user orders
$q = "SELECT * FROM orders WHERE id_user = '$id_user' ORDER BY id_order DESC";
$res = mysqli_query($conn, $q);
$jumlah_orders = mysqli_num_rows($res);

// Status styling mapping
$status_style = [
    'pending' => [
        'bg' => 'bg-yellow-500/10',
        'color' => 'text-yellow-400 border-yellow-500/25',
        'label' => 'Diproses'
    ],
    'dikirim' => [
        'bg' => 'bg-blue-500/10',
        'color' => 'text-blue-400 border-blue-500/25',
        'label' => 'Sedang Dikirim'
    ],
    'selesai' => [
        'bg' => 'bg-green-500/10',
        'color' => 'text-green-400 border-green-500/25',
        'label' => 'Pesanan Selesai'
    ]
];
?>

<main class="flex-1">
    <div class="container mx-auto px-4 sm:px-6 py-8 max-w-5xl">
        <!-- Header Page Title -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-8 border-b border-white/[0.08] pb-5">
            <div>
                <h2 class="font-serif font-bold text-2xl sm:text-3xl text-white">Pesanan Saya</h2>
                <p class="text-xs text-[#f0ebe0]/50 mt-1">Pantau status transaksi belanja pustaka dan cetak struk pembelian resmi Anda.</p>
            </div>
            <a href="index.php" class="text-xs px-4 py-2 rounded-xl border border-white/10 hover:border-emas/40 bg-white/[0.03] text-[#f0ebe0]/70 hover:text-white transition-all font-semibold">
                + Belanja Lagi
            </a>
        </div>
        
        <?php if ($jumlah_orders === 0): ?>
            <div class="text-center py-28 bg-white/[0.02] border border-white/[0.05] rounded-3xl">
                <div class="text-5xl mb-4">📦</div>
                <h4 class="font-serif font-bold text-base text-white mb-1">Belum Ada Transaksi Pembelian</h4>
                <p class="text-xs text-[#f0ebe0]/40 mb-6">Pilihan buku kurasi terbaik siap menemani hari-hari Anda.</p>
                <a href="index.php" class="inline-block px-7 py-3 rounded-2xl btn-gold text-darkBg font-bold text-xs shadow-md">
                    Mulai Eksplorasi Katalog
                </a>
            </div>
        <?php else: ?>
            <div class="space-y-6">
                <?php 
                while ($order = mysqli_fetch_assoc($res)): 
                    $status = $order['status'];
                    $style = isset($status_style[$status]) ? $status_style[$status] : $status_style['pending'];
                    $total_fmt = 'Rp ' . number_format($order['total_harga'], 0, ',', '.');
                    $date_fmt = date('d M Y, H:i', strtotime($order['tanggal_order']));
                    $metode = isset($order['metode_pembayaran']) ? $order['metode_pembayaran'] : 'Bayar di Tempat (COD)';
                    $status_bayar = isset($order['status_pembayaran']) ? $order['status_pembayaran'] : ($status == 'dikirim' || $status == 'selesai' ? 'Lunas' : 'Menunggu Konfirmasi');
                    $bukti = isset($order['bukti_pembayaran']) ? $order['bukti_pembayaran'] : null;
                    $alamat = isset($order['alamat']) ? $order['alamat'] : '';
                    $oid = $order['id_order'];
                    
                    // Fetch order items for this order
                    $items_q = "SELECT oi.jumlah, oi.harga, b.judul, b.penulis, b.gambar FROM order_items oi JOIN books b ON oi.id_buku = b.id_buku WHERE oi.id_order = $oid";
                    $items_res = mysqli_query($conn, $items_q);
                    $total_items_count = 0;
                    $items_list = [];
                    while ($it = mysqli_fetch_assoc($items_res)) {
                        $items_list[] = $it;
                        $total_items_count += intval($it['jumlah']);
                    }
                ?>
                    <div class="rounded-3xl p-5 sm:p-7 bg-white/[0.03] border border-white/[0.08] hover:border-[rgba(212,168,83,0.3)] transition-all flex flex-col gap-5 shadow-xl">
                        <!-- Top Bar: Order Meta & Status Badges -->
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border-b border-white/[0.06] pb-4">
                            <div>
                                <div class="flex items-center gap-2.5 flex-wrap">
                                    <span class="font-mono text-xs font-bold text-white tracking-wider">ORDER #<?php echo $oid; ?></span>
                                    <span class="px-2 py-0.5 rounded-lg text-[10px] bg-emas/15 text-emas border border-emas/20 font-sans font-semibold">
                                        <?php echo htmlspecialchars($metode); ?>
                                    </span>
                                    <span class="text-[11px] text-[#f0ebe0]/45">· <?php echo $date_fmt; ?> WIB</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-3 py-1 rounded-xl font-semibold border <?php echo $style['bg'] . ' ' . $style['color']; ?>">
                                    <?php echo $style['label']; ?> (<?php echo htmlspecialchars($status_bayar); ?>)
                                </span>
                            </div>
                        </div>
                        
                        <!-- Shipping Address Box -->
                        <?php if (!empty($alamat)): ?>
                            <div class="text-xs text-[#f0ebe0]/70 bg-white/[0.02] p-3.5 rounded-2xl border border-white/[0.05] flex items-start gap-2.5">
                                <i data-lucide="map-pin" class="w-4 h-4 text-emas shrink-0 mt-0.5"></i>
                                <div>
                                    <span class="text-[10px] uppercase tracking-wider text-emas/90 block font-bold mb-0.5">Alamat Pengiriman:</span>
                                    <span class="leading-relaxed"><?php echo nl2br(htmlspecialchars($alamat)); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Ordered Books Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            <?php foreach ($items_list as $item): 
                                $subtotal_item = intval($item['harga']) * intval($item['jumlah']);
                            ?>
                                <div class="flex items-center gap-3 bg-white/[0.02] border border-white/[0.05] p-3 rounded-2xl">
                                    <img src="<?php echo htmlspecialchars($item['gambar']); ?>" alt="Cover" class="w-12 h-16 object-cover rounded-xl bg-[#1a1528] border border-white/10 shrink-0">
                                    <div class="min-w-0 flex-1">
                                        <div class="text-xs font-serif font-bold text-white truncate" title="<?php echo htmlspecialchars($item['judul']); ?>">
                                            <?php echo htmlspecialchars($item['judul']); ?>
                                        </div>
                                        <div class="text-[10px] text-[#f0ebe0]/45 truncate mb-1">
                                            <?php echo htmlspecialchars($item['penulis']); ?>
                                        </div>
                                        <div class="flex items-center justify-between text-[11px]">
                                            <span class="text-emas font-bold">x<?php echo $item['jumlah']; ?></span>
                                            <span class="text-[#f0ebe0]/80 font-semibold">Rp <?php echo number_format($subtotal_item, 0, ',', '.'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Card Action Footer -->
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pt-4 border-t border-white/[0.06]">
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="text-xs text-[#f0ebe0]/50">Total: <b><?php echo $total_items_count; ?> eksemplar</b></span>
                                <?php if (!empty($bukti)): ?>
                                    <a href="uploads/bukti/<?php echo urlencode($bukti); ?>" target="_blank" 
                                       class="text-[11px] px-3 py-1 rounded-xl bg-white/[0.05] text-[#f0ebe0]/70 border border-white/10 hover:border-emas/40 hover:text-emas transition-all font-semibold inline-flex items-center gap-1">
                                        Lihat Bukti Transfer ↗
                                    </a>
                                <?php endif; ?>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto gap-4">
                                <div class="text-left sm:text-right">
                                    <div class="text-[10px] text-[#f0ebe0]/40 uppercase tracking-widest font-semibold">Total Tagihan</div>
                                    <div class="font-bold text-lg text-emas font-serif"><?php echo $total_fmt; ?></div>
                                </div>
                                <a href="cetak_struk.php?id=<?php echo $oid; ?>" target="_blank" 
                                   class="px-4 py-2.5 rounded-xl border border-emas/40 bg-emas/15 text-emas hover:bg-emas hover:text-darkBg transition-all font-semibold text-xs inline-flex items-center gap-1.5 shadow-md">
                                    🧾 Struk Resmi (PDF / Foto)
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
include 'footer.php';
?>

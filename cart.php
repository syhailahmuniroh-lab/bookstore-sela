<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php';

// Check if user is logged in
if (!isset($_SESSION['id_user'])) {
    echo "
    <!DOCTYPE html>
    <html lang='id'>
    <head>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap' rel='stylesheet'>
        <style>
            body { background-color: #0a0814; font-family: 'Inter', sans-serif; margin: 0; }
        </style>
    </head>
    <body>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Akses Terbatas',
                text: 'Silakan Masuk (Login) terlebih dahulu untuk mulai berbelanja!',
                icon: 'warning',
                background: '#14112a',
                color: '#f0ebe0',
                confirmButtonColor: '#d4a853',
                width: '90%',
                customClass: {
                    popup: 'border border-[rgba(212,168,83,0.2)] rounded-3xl font-sans'
                }
            }).then(function() {
                window.location = 'login.php';
            });
        });
        </script>
    </body>
    </html>
    ";
    exit;
}

// 1. Add item to cart
if (isset($_GET['id'])) {
    $id_buku = intval($_GET['id']);
    $check_stock = mysqli_query($conn, "SELECT judul, stok FROM books WHERE id_buku = $id_buku");
    $buku_data = mysqli_fetch_assoc($check_stock);
    
    if (!$buku_data || intval($buku_data['stok']) <= 0) {
        echo "
        <!DOCTYPE html>
        <html lang='id'>
        <head><meta name='viewport' content='width=device-width, initial-scale=1.0'><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><style>body{background:#0a0814;font-family:sans-serif;}</style></head>
        <body><script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Stok Habis',
                text: 'Maaf, stok karya buku ini sedang habis!',
                icon: 'error',
                background: '#14112a',
                color: '#f0ebe0',
                confirmButtonColor: '#d4a853'
            }).then(() => { window.location = 'index.php'; });
        });
        </script></body></html>";
        exit;
    }

    if (!isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = [];
    }

    $current_qty = isset($_SESSION['keranjang'][$id_buku]) ? intval($_SESSION['keranjang'][$id_buku]) : 0;
    if ($current_qty + 1 > intval($buku_data['stok'])) {
        echo "
        <!DOCTYPE html>
        <html lang='id'>
        <head><meta name='viewport' content='width=device-width, initial-scale=1.0'><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><style>body{background:#0a0814;font-family:sans-serif;}</style></head>
        <body><script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Batas Stok Tercapai',
                text: 'Kuantitas di keranjang sudah mencapai sisa stok maksimal (" . $buku_data['stok'] . " buku)!',
                icon: 'warning',
                background: '#14112a',
                color: '#f0ebe0',
                confirmButtonColor: '#d4a853'
            }).then(() => { window.location = 'cart.php'; });
        });
        </script></body></html>";
        exit;
    }

    $_SESSION['keranjang'][$id_buku] = $current_qty + 1;
    header("Location: cart.php");
    exit;
}

// 2. Increase Qty
if (isset($_GET['add_qty'])) {
    $id_buku = intval($_GET['add_qty']);
    $check_stock = mysqli_query($conn, "SELECT stok FROM books WHERE id_buku = $id_buku");
    $buku_data = mysqli_fetch_assoc($check_stock);
    if (isset($_SESSION['keranjang'][$id_buku]) && $buku_data) {
        if ($_SESSION['keranjang'][$id_buku] + 1 <= intval($buku_data['stok'])) {
            $_SESSION['keranjang'][$id_buku] += 1;
        }
    }
    header("Location: cart.php");
    exit;
}

// 3. Decrease Qty
if (isset($_GET['dec_qty'])) {
    $id_buku = intval($_GET['dec_qty']);
    if (isset($_SESSION['keranjang'][$id_buku])) {
        $_SESSION['keranjang'][$id_buku] -= 1;
        if ($_SESSION['keranjang'][$id_buku] <= 0) {
            unset($_SESSION['keranjang'][$id_buku]);
        }
    }
    header("Location: cart.php");
    exit;
}

// 4. Remove item
if (isset($_GET['hapus'])) {
    $id_hapus = intval($_GET['hapus']);
    unset($_SESSION['keranjang'][$id_hapus]);
    header("Location: cart.php");
    exit;
}

include 'header.php';
?>

<main class="flex-1">
    <div class="container mx-auto px-5 py-6">
        <h3 class="font-serif font-bold text-xl mb-6 text-white">Keranjang Belanja</h3>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Items list -->
            <div class="lg:col-span-2 space-y-4">
                <?php
                $total_belanja = 0;
                $total_items = 0;
                
                if (empty($_SESSION['keranjang'])):
                ?>
                    <div class="text-center py-24 bg-white/[0.02] border border-white/[0.05] rounded-3xl">
                        <div class="text-5xl mb-4">🛒</div>
                        <p class="text-sm text-[#f0ebe0]/40 mb-6">Belum Ada Koleksi di Keranjang Belanja</p>
                        <a href="index.php" class="inline-block px-6 py-3 rounded-2xl btn-gold text-darkBg font-bold text-xs shadow-md">
                            Eksplorasi Katalog
                        </a>
                    </div>
                <?php 
                else:
                    $total_items = array_sum($_SESSION['keranjang']);
                    foreach ($_SESSION['keranjang'] as $id_buku => $jumlah):
                        $query = "SELECT * FROM books WHERE id_buku = '$id_buku'";
                        $result = mysqli_query($conn, $query);
                        $buku = mysqli_fetch_assoc($result);
                        
                        $subtotal = $buku['harga'] * $jumlah;
                        $total_belanja += $subtotal;
                        $price_fmt = 'Rp ' . number_format($buku['harga'], 0, ',', '.');
                        $subtotal_fmt = 'Rp ' . number_format($subtotal, 0, ',', '.');
                ?>
                        <div class="flex gap-4 p-4 rounded-2xl bg-white/[0.04] border border-white/[0.07]">
                            <img src="<?php echo htmlspecialchars($buku['gambar']); ?>" alt="Cover" class="w-14 h-20 object-cover rounded-xl flex-shrink-0 bg-[#1a1528]">
                            <div class="flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-start">
                                        <h6 class="font-serif font-bold text-sm text-white line-clamp-1"><?php echo htmlspecialchars($buku['judul']); ?></h6>
                                        <a href="cart.php?hapus=<?php echo $id_buku; ?>" class="text-[#f87171] hover:text-[#f87171]/80 text-xs pl-2">&times; Hapus</a>
                                    </div>
                                    <p class="text-[11px] text-[#f0ebe0]/40"><?php echo htmlspecialchars($buku['penulis']); ?></p>
                                    <div class="mt-1">
                                        <span class="text-[9px] px-2 py-0.5 rounded-full font-medium <?php echo intval($buku['stok']) > 0 ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/20' : 'bg-red-500/15 text-red-400 border border-red-500/20'; ?>">
                                            Tersedia: <?php echo $buku['stok']; ?> buku
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between mt-3">
                                    <div class="flex items-center gap-3 bg-white/[0.05] border border-white/[0.08] px-2 py-1 rounded-xl">
                                        <a href="cart.php?dec_qty=<?php echo $id_buku; ?>" class="w-6 h-6 flex items-center justify-center font-bold text-xs hover:text-emas text-[#f0ebe0]/60">-</a>
                                        <span class="text-xs font-bold w-4 text-center"><?php echo $jumlah; ?></span>
                                        <?php if ($jumlah < intval($buku['stok'])): ?>
                                            <a href="cart.php?add_qty=<?php echo $id_buku; ?>" class="w-6 h-6 flex items-center justify-center font-bold text-xs hover:text-emas text-emas">+</a>
                                        <?php else: ?>
                                            <span class="w-6 h-6 flex items-center justify-center font-bold text-xs text-white/20 cursor-not-allowed" title="Mencapai batas stok">+</span>
                                        <?php endif; ?>
                                    </div>
                                    <span class="font-bold text-sm text-emas"><?php echo $subtotal_fmt; ?></span>
                                </div>
                            </div>
                        </div>
                <?php
                    endforeach;
                endif;
                ?>
            </div>

            <!-- Right: Summary Card -->
            <?php if (!empty($_SESSION['keranjang'])): ?>
                <div class="h-fit rounded-2xl p-5 bg-[rgba(212,168,83,0.05)] border border-[rgba(212,168,83,0.15)] shadow-lg">
                    <h5 class="font-serif font-bold text-sm mb-4 text-white uppercase tracking-wider">Ringkasan Pesanan</h5>
                    <div class="flex justify-between text-xs mb-3 text-[#f0ebe0]/60">
                        <span>Subtotal (<?php echo $total_items; ?> item)</span>
                        <span>Rp <?php echo number_format($total_belanja, 0, ',', '.'); ?></span>
                    </div>
                    <div class="flex justify-between text-xs mb-4 text-[#f0ebe0]/60">
                        <span>Ongkos Kirim</span>
                        <span class="text-[#4ade80]">Gratis</span>
                    </div>
                    <div class="border-t border-[rgba(212,168,83,0.15)] pt-4 flex justify-between font-bold text-sm mb-6">
                        <span>Total Akhir</span>
                        <span class="gold-text fs-5">Rp <?php echo number_format($total_belanja, 0, ',', '.'); ?></span>
                    </div>
                    
                    <a href="checkout.php" class="block w-full py-4 text-center rounded-2xl font-bold text-sm btn-gold text-darkBg shadow-lg">
                        Lanjut Checkout →
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
include 'footer.php';
?>
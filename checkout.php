<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php';

// 1. Check login & empty cart
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

if (empty($_SESSION['keranjang']) && !isset($_POST['pesan'])) {
    echo "
    <!DOCTYPE html>
    <html lang='id'>
    <head>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap' rel='stylesheet'>
        <style>body { background-color: #0a0814; font-family: 'Inter', sans-serif; margin: 0; }</style>
    </head>
    <body>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Keranjang Kosong',
                text: 'Keranjang Anda masih kosong. Yuk pilih karya buku pilihan Anda!',
                icon: 'warning',
                background: '#14112a',
                color: '#f0ebe0',
                confirmButtonColor: '#d4a853',
                width: '90%',
                customClass: { popup: 'border border-[rgba(212,168,83,0.2)] rounded-3xl font-sans' }
            }).then(function() {
                window.location = 'index.php';
            });
        });
        </script>
    </body>
    </html>
    ";
    exit;
}

$total_belanja = 0;
$checkout_items = [];

// Fetch items details for checkout summary
if (!empty($_SESSION['keranjang'])) {
    foreach ($_SESSION['keranjang'] as $id_buku => $jumlah) {
        $id_buku_safe = intval($id_buku);
        $query = "SELECT * FROM books WHERE id_buku = $id_buku_safe";
        $result = mysqli_query($conn, $query);
        $buku = mysqli_fetch_assoc($result);
        if ($buku) {
            $subtotal = $buku['harga'] * $jumlah;
            $total_belanja += $subtotal;
            
            $checkout_items[] = [
                'id_buku' => $buku['id_buku'],
                'judul' => $buku['judul'],
                'jumlah' => $jumlah,
                'harga' => $buku['harga'],
                'stok' => $buku['stok'],
                'subtotal' => $subtotal
            ];
        }
    }
}

$order_success = false;
$inserted_id = 0;
$error = null;

// 2. Process checkout submission
if (isset($_POST['pesan'])) {
    $id_user = $_SESSION['id_user'];
    $alamat = isset($_POST['alamat']) ? trim(mysqli_real_escape_string($conn, $_POST['alamat'])) : '';
    $payment_choice = isset($_POST['payment_choice']) ? trim($_POST['payment_choice']) : 'COD';
    
    // Check address
    if (empty($alamat)) {
        $error = "Alamat pengiriman wajib diisi dengan lengkap!";
    }

    // Check stock for all items
    if (!$error && !empty($_SESSION['keranjang'])) {
        foreach ($_SESSION['keranjang'] as $id_buku => $jumlah) {
            $id_buku_safe = intval($id_buku);
            $q_stock = mysqli_query($conn, "SELECT judul, stok FROM books WHERE id_buku = $id_buku_safe");
            $b_stock = mysqli_fetch_assoc($q_stock);
            if (!$b_stock || intval($b_stock['stok']) < intval($jumlah)) {
                $sisa = $b_stock ? $b_stock['stok'] : 0;
                $error = "Stok untuk buku <b>" . htmlspecialchars($b_stock['judul']) . "</b> tidak mencukupi (sisa: $sisa, dipesan: $jumlah). Silakan sesuaikan jumlah di keranjang!";
                break;
            }
        }
    }

    // Determine payment details
    $metode_pembayaran = 'Bayar di Tempat (COD)';
    $status_pembayaran = 'Menunggu Konfirmasi COD';
    $bukti_filename = NULL;

    if ($payment_choice === 'BCA') {
        $metode_pembayaran = 'Transfer Bank BCA';
        $status_pembayaran = 'Menunggu Verifikasi Admin';
    } elseif ($payment_choice === 'QRIS') {
        $metode_pembayaran = 'QRIS';
        $status_pembayaran = 'Menunggu Verifikasi Admin';
    }

    // Handle payment proof upload for non-COD methods
    if (!$error && ($payment_choice === 'BCA' || $payment_choice === 'QRIS')) {
        if (!isset($_FILES['bukti_transfer']) || $_FILES['bukti_transfer']['error'] !== UPLOAD_ERR_OK) {
            $error = "Harap unggah berkas bukti pembayaran (foto JPG/PNG atau dokumen PDF) untuk metode transfer!";
        } else {
            $file_tmp = $_FILES['bukti_transfer']['tmp_name'];
            $file_name = $_FILES['bukti_transfer']['name'];
            $file_size = $_FILES['bukti_transfer']['size'];
            $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_exts = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];

            if (!in_array($ext, $allowed_exts)) {
                $error = "Format berkas tidak valid! Hanya diperbolehkan berkas foto (JPG, PNG) atau dokumen PDF.";
            } elseif ($file_size > 5 * 1024 * 1024) {
                $error = "Ukuran berkas terlalu besar! Maksimal ukuran bukti adalah 5 MB.";
            } else {
                $upload_dir = __DIR__ . '/uploads/bukti/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $new_filename = 'bukti_' . $id_user . '_' . time() . '_' . rand(100, 999) . '.' . $ext;
                $destination = $upload_dir . $new_filename;

                if (move_uploaded_file($file_tmp, $destination)) {
                    $bukti_filename = $new_filename;
                } else {
                    $error = "Gagal mengunggah berkas bukti pembayaran ke server!";
                }
            }
        }
    }

    // If all validation passes, save to database
    if (!$error) {
        $status_order = 'pending';
        $bukti_val = $bukti_filename ? "'$bukti_filename'" : "NULL";

        $query_order = "INSERT INTO orders (id_user, alamat, total_harga, status, metode_pembayaran, status_pembayaran, bukti_pembayaran) 
                        VALUES ('$id_user', '$alamat', '$total_belanja', '$status_order', '$metode_pembayaran', '$status_pembayaran', $bukti_val)";
        
        if (mysqli_query($conn, $query_order)) {
            $inserted_id = mysqli_insert_id($conn);
            $order_success = true;

            // Insert each item into order_items and deduct stock
            if (!empty($_SESSION['keranjang'])) {
                foreach ($_SESSION['keranjang'] as $id_buku => $jumlah) {
                    $id_buku_safe = intval($id_buku);
                    $jumlah_safe = intval($jumlah);

                    $q_price = mysqli_query($conn, "SELECT harga FROM books WHERE id_buku = $id_buku_safe");
                    $book_data = mysqli_fetch_assoc($q_price);
                    $harga = $book_data['harga'];

                    // 1. Insert order item
                    $query_item = "INSERT INTO order_items (id_order, id_buku, jumlah, harga) VALUES ($inserted_id, $id_buku_safe, $jumlah_safe, $harga)";
                    mysqli_query($conn, $query_item);

                    // 2. Deduct stock automatically
                    $query_stok = "UPDATE books SET stok = GREATEST(0, stok - $jumlah_safe) WHERE id_buku = $id_buku_safe";
                    mysqli_query($conn, $query_stok);
                }
            }

            // Save summary details to display on success screen
            $_SESSION['last_order'] = [
                'id' => $inserted_id,
                'items' => $checkout_items,
                'total' => $total_belanja,
                'alamat' => $alamat,
                'metode' => $metode_pembayaran,
                'status_bayar' => $status_pembayaran,
                'bukti' => $bukti_filename
            ];

            // Clear cart
            unset($_SESSION['keranjang']);
        } else {
            $error = "Terjadi kesalahan saat memproses pesanan di database: " . mysqli_error($conn);
        }
    }
}

include 'header.php';
?>

<main class="flex-1">
    <div class="container mx-auto px-5 py-6 max-w-xl">
        
        <?php if (!$order_success): ?>
            <!-- ================= STEP 2: ADDRESS & PAYMENT INPUT ================= -->
            <a href="cart.php" class="text-xs text-[#f0ebe0]/40 hover:text-emas transition-colors mb-4 inline-block flex items-center gap-1">
                ← Kembali ke Keranjang
            </a>
            <h3 class="font-serif font-bold text-xl mb-6 text-white">Konfirmasi Pemesanan</h3>

            <?php if (isset($error) && $error): ?>
                <div class="bg-[#ef4444]/10 text-[#f87171] border border-[#ef4444]/20 p-4 rounded-2xl mb-6 text-xs leading-relaxed">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form id="checkoutForm" method="POST" action="" enctype="multipart/form-data" class="space-y-6">
                <!-- Alamat Input -->
                <div class="rounded-2xl p-4 bg-white/[0.04] border border-white/[0.07]">
                    <label class="block text-[10px] uppercase tracking-widest font-semibold text-emas mb-3">
                        Alamat Pengiriman Lengkap <span class="text-red-400">*</span>
                    </label>
                    <textarea name="alamat" rows="3" required
                              class="w-full rounded-xl px-4 py-3 text-xs outline-none bg-white/[0.04] border border-white/[0.08] text-white focus:border-emas resize-none transition-all"
                              placeholder="Tulis alamat rumah lengkap, nama jalan, RT/RW, kelurahan, kecamatan, kota, dan kode pos..."><?php echo isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : ''; ?></textarea>
                </div>

                <!-- Payment Method Selection -->
                <div class="rounded-2xl p-4 bg-white/[0.04] border border-white/[0.07]">
                    <label class="block text-[10px] uppercase tracking-widest font-semibold text-emas mb-3">Pilih Metode Pembayaran</label>
                    <div class="space-y-2.5">
                        
                        <!-- Option 1: COD -->
                        <label class="flex items-center justify-between p-3.5 rounded-xl border border-white/[0.08] bg-white/[0.02] hover:border-emas/40 cursor-pointer transition-all payment-option">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_choice" value="COD" checked onclick="selectPayment('COD')" class="accent-[#d4a853]">
                                <div>
                                    <div class="text-xs font-bold text-white flex items-center gap-1.5">
                                        🚚 Bayar di Tempat (COD)
                                    </div>
                                    <div class="text-[10px] text-[#f0ebe0]/45 mt-0.5">Bayar tunai kepada kurir saat pesanan tiba di alamat Anda.</div>
                                </div>
                            </div>
                        </label>

                        <!-- Option 2: Transfer Bank BCA -->
                        <label class="flex items-center justify-between p-3.5 rounded-xl border border-white/[0.08] bg-white/[0.02] hover:border-emas/40 cursor-pointer transition-all payment-option">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_choice" value="BCA" onclick="selectPayment('BCA')" class="accent-[#d4a853]">
                                <div>
                                    <div class="text-xs font-bold text-white flex items-center gap-1.5">
                                        🏦 Transfer Bank BCA
                                    </div>
                                    <div class="text-[10px] text-[#f0ebe0]/45 mt-0.5">Transfer rekening bank resmi Cell’s Bookshelf dengan verifikasi bukti bayar.</div>
                                </div>
                            </div>
                        </label>

                        <!-- Option 3: QRIS -->
                        <label class="flex items-center justify-between p-3.5 rounded-xl border border-white/[0.08] bg-white/[0.02] hover:border-emas/40 cursor-pointer transition-all payment-option">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_choice" value="QRIS" onclick="selectPayment('QRIS')" class="accent-[#d4a853]">
                                <div>
                                    <div class="text-xs font-bold text-white flex items-center gap-1.5">
                                        📱 QRIS Resmi Cell’s Bookshelf
                                    </div>
                                    <div class="text-[10px] text-[#f0ebe0]/45 mt-0.5">Scan kode barcode QRIS melalui m-Banking atau dompet digital.</div>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Instruction Card for BCA -->
                    <div id="infoBoxBCA" class="mt-4 p-4 rounded-xl bg-white/[0.03] border border-emas/20 space-y-2 hidden animate-fade-up">
                        <div class="text-[10px] uppercase tracking-widest text-emas font-semibold">Instruksi Transfer BCA</div>
                        <div class="text-xs text-[#f0ebe0]/70">Silakan transfer sesuai nominal tagihan ke nomor rekening berikut:</div>
                        <div class="p-3 rounded-lg bg-black/40 border border-white/10 flex items-center justify-between font-mono">
                            <div>
                                <div class="text-xs text-[#f0ebe0]/40">Bank Central Asia (BCA)</div>
                                <div class="text-sm font-bold text-emas tracking-wider">88012938192318</div>
                                <div class="text-[10px] text-[#f0ebe0]/60">a.n. Cell’s Bookshelf Official</div>
                            </div>
                            <button type="button" onclick="navigator.clipboard.writeText('88012938192318'); Swal.fire({title:'Nomor Rekening Tersalin',icon:'success',toast:true,position:'top',showConfirmButton:false,timer:1500});" class="text-xs px-2.5 py-1 rounded bg-emas/20 text-emas font-sans font-semibold hover:bg-emas/30 transition-all">Salin</button>
                        </div>
                    </div>

                    <!-- Instruction Card for QRIS -->
                    <div id="infoBoxQRIS" class="mt-4 p-4 rounded-xl bg-white/[0.03] border border-emas/20 text-center space-y-2 hidden animate-fade-up">
                        <div class="text-[10px] uppercase tracking-widest text-emas font-semibold">Instruksi Scan QRIS</div>
                        <div class="text-xs text-[#f0ebe0]/70">Scan kode barcode resmi berikut untuk menyelesaikan pembayaran:</div>
                        <div class="w-40 h-40 mx-auto p-2 bg-white rounded-2xl shadow-xl flex items-center justify-center my-2">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=BOOKSTORE-OFFICIAL-PAYMENT-CODE" alt="QRIS Barcode" class="w-full h-full">
                        </div>
                        <div class="text-[10px] text-emas font-mono uppercase tracking-widest">NMID: ID1029381920381</div>
                    </div>

                    <!-- Upload Proof Box (For BCA & QRIS) -->
                    <div id="uploadProofSection" class="mt-4 p-4 rounded-xl bg-[rgba(212,168,83,0.06)] border border-emas/30 space-y-2 hidden animate-fade-up">
                        <label class="block text-[10px] uppercase tracking-widest font-semibold text-emas">
                            Upload Bukti Pembayaran <span class="text-red-400">*Wajib</span>
                        </label>
                        <p class="text-[11px] text-[#f0ebe0]/60">
                            Lampirkan foto struk/screenshot mutasi atau dokumen PDF bukti pembayaran Anda:
                        </p>
                        <input type="file" name="bukti_transfer" id="buktiTransferInput" accept=".jpg,.jpeg,.png,.webp,.pdf"
                               class="w-full text-xs text-[#f0ebe0]/80 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emas file:text-darkBg hover:file:bg-emas-hover cursor-pointer bg-white/[0.04] p-2 rounded-xl border border-white/10">
                    </div>
                </div>

                <input type="hidden" name="pesan" value="1">

                <button type="submit" 
                        class="w-full py-4 rounded-2xl font-bold text-sm btn-gold text-darkBg shadow-lg transition-all">
                    Selesaikan Pesanan — Rp <?php echo number_format($total_belanja, 0, ',', '.'); ?>
                </button>
            </form>

        <?php else: 
            $last = isset($_SESSION['last_order']) ? $_SESSION['last_order'] : null;
            unset($_SESSION['last_order']); // Clear so it only shows once
            $metode_text = $last && isset($last['metode']) ? $last['metode'] : 'Bayar di Tempat (COD)';
            $status_bayar_text = $last && isset($last['status_bayar']) ? $last['status_bayar'] : 'Menunggu Konfirmasi COD';
        ?>
            <!-- ================= STEP 3: DONE / SUCCESS SCREEN ================= -->
            <div class="flex flex-col items-center justify-center min-h-[400px] text-center animate-fade-up">
                <div class="w-16 h-16 rounded-full flex items-center justify-center text-3xl mb-5 bg-green-500/10 border border-green-500/30 text-green-400">
                    ✓
                </div>
                <h3 class="font-serif font-bold text-2xl mb-1 text-white">Pesanan Anda Berhasil Diterima!</h3>
                <p class="text-[10px] font-mono text-[#f0ebe0]/45 mb-6">
                    ORDER ID #<?php echo $last ? $last['id'] : $inserted_id; ?> · <?php echo date('d M Y, H:i'); ?>
                </p>

                <?php if ($last): ?>
                    <div class="w-full rounded-2xl p-5 text-left mb-6 bg-white/[0.04] border border-white/[0.08]">
                        <div class="flex justify-between items-center mb-3">
                            <div class="text-[10px] uppercase tracking-widest font-semibold text-emas">Ringkasan Pesanan</div>
                            <span class="text-[10px] px-2.5 py-0.5 rounded-full font-bold bg-emas/15 text-emas border border-emas/20">
                                <?php echo htmlspecialchars($metode_text); ?>
                            </span>
                        </div>
                        
                        <div class="mb-3 pb-3 border-b border-white/[0.05] text-xs text-[#f0ebe0]/70">
                            <span class="text-[10px] text-[#f0ebe0]/40 uppercase tracking-wider block mb-1">Status Pembayaran:</span>
                            <span class="font-semibold text-emas"><?php echo htmlspecialchars($status_bayar_text); ?></span>
                        </div>

                        <div class="mb-3 pb-3 border-b border-white/[0.05] text-xs text-[#f0ebe0]/70">
                            <span class="text-[10px] text-[#f0ebe0]/40 uppercase tracking-wider block mb-1">Alamat Tujuan Pengiriman:</span>
                            <span><?php echo nl2br(htmlspecialchars($last['alamat'])); ?></span>
                        </div>

                        <?php if (!empty($last['bukti'])): ?>
                            <div class="mb-3 pb-3 border-b border-white/[0.05] text-xs">
                                <span class="text-[10px] text-[#f0ebe0]/40 uppercase tracking-wider block mb-1">Bukti Transfer:</span>
                                <a href="uploads/bukti/<?php echo urlencode($last['bukti']); ?>" target="_blank" class="text-emas hover:underline font-semibold flex items-center gap-1">
                                    Lihat Berkas Bukti yang Telah Diunggah →
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="space-y-2.5 max-h-40 overflow-y-auto pr-1">
                            <?php foreach ($last['items'] as $item): ?>
                                <div class="flex justify-between text-xs py-1.5 border-b border-white/[0.05]">
                                    <span class="text-[#f0ebe0]/70 truncate max-w-[200px]"><?php echo htmlspecialchars($item['judul']); ?> &times;<?php echo $item['jumlah']; ?></span>
                                    <span class="text-emas font-medium">Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="flex justify-between font-bold text-xs pt-4 mt-1 border-t border-white/[0.08]">
                            <span>Total Tagihan</span>
                            <span class="gold-text">Rp <?php echo number_format($last['total'], 0, ',', '.'); ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="flex flex-col sm:flex-row gap-3 w-full">
                    <a href="cetak_struk.php?id=<?php echo $last ? $last['id'] : $inserted_id; ?>" target="_blank" 
                       class="flex-1 py-4 text-center rounded-2xl font-bold text-sm bg-emas/15 border border-emas/30 text-emas hover:bg-emas/25 shadow-lg flex items-center justify-center gap-2 transition-all">
                        🖨️ Cetak Struk (PDF)
                    </a>
                    <a href="orders.php" 
                       class="flex-1 py-4 text-center rounded-2xl font-bold text-sm btn-gold text-darkBg shadow-lg transition-all">
                        Lihat Pesanan Saya →
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
function selectPayment(choice) {
    const infoBCA = document.getElementById('infoBoxBCA');
    const infoQRIS = document.getElementById('infoBoxQRIS');
    const uploadBox = document.getElementById('uploadProofSection');
    const fileInput = document.getElementById('buktiTransferInput');

    if (choice === 'COD') {
        infoBCA.classList.add('hidden');
        infoQRIS.classList.add('hidden');
        uploadBox.classList.add('hidden');
        if (fileInput) fileInput.required = false;
    } else if (choice === 'BCA') {
        infoBCA.classList.remove('hidden');
        infoQRIS.classList.add('hidden');
        uploadBox.classList.remove('hidden');
        if (fileInput) fileInput.required = true;
    } else if (choice === 'QRIS') {
        infoBCA.classList.add('hidden');
        infoQRIS.classList.remove('hidden');
        uploadBox.classList.remove('hidden');
        if (fileInput) fileInput.required = true;
    }
}

document.getElementById('checkoutForm')?.addEventListener('submit', function(e) {
    const alamat = document.querySelector('textarea[name="alamat"]').value;
    if (!alamat.trim()) {
        e.preventDefault();
        Swal.fire({
            title: 'Alamat Diperlukan',
            text: 'Silakan isi alamat pengiriman lengkap Anda!',
            icon: 'warning',
            background: '#14112a',
            color: '#f0ebe0',
            confirmButtonColor: '#d4a853'
        });
        return;
    }

    const selectedChoice = document.querySelector('input[name="payment_choice"]:checked')?.value;
    const fileInput = document.getElementById('buktiTransferInput');
    if ((selectedChoice === 'BCA' || selectedChoice === 'QRIS') && (!fileInput || !fileInput.files || fileInput.files.length === 0)) {
        e.preventDefault();
        Swal.fire({
            title: 'Bukti Pembayaran Diperlukan',
            text: 'Harap lampirkan foto struk atau dokumen PDF bukti transfer pembayaran Anda!',
            icon: 'warning',
            background: '#14112a',
            color: '#f0ebe0',
            confirmButtonColor: '#d4a853'
        });
    }
});
</script>

<?php
include 'footer.php';
?>

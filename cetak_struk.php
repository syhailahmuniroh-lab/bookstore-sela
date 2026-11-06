<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php';

// Guard: Must be logged in
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$id_user_logged = intval($_SESSION['id_user']);
$role_logged = isset($_SESSION['role']) ? $_SESSION['role'] : 'user';

// Get order ID from GET
$id_order = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_order <= 0) {
    die("ID Pesanan tidak valid.");
}

// Fetch order details
$q_order = "SELECT o.*, u.nama, u.email, u.no_telp 
            FROM orders o 
            LEFT JOIN users u ON o.id_user = u.id_user 
            WHERE o.id_order = $id_order";
$res_order = mysqli_query($conn, $q_order);
$order = mysqli_fetch_assoc($res_order);

if (!$order) {
    die("Pesanan tidak ditemukan di sistem.");
}

// Authorization check: Only admin or the owner can print the receipt
if ($role_logged !== 'admin' && intval($order['id_user']) !== $id_user_logged) {
    die("Akses ditolak. Anda tidak berhak mencetak struk pesanan ini.");
}

// Fetch order items
$q_items = "SELECT oi.*, b.judul, b.penulis, b.harga as harga_buku 
            FROM order_items oi 
            JOIN books b ON oi.id_buku = b.id_buku 
            WHERE oi.id_order = $id_order";
$res_items = mysqli_query($conn, $q_items);
$items = [];
$total_qty = 0;
while ($row = mysqli_fetch_assoc($res_items)) {
    $items[] = $row;
    $total_qty += intval($row['jumlah']);
}

$order_no = "ORD-" . date('Ymd', strtotime($order['tanggal_order'])) . "-" . str_pad($order['id_order'], 4, '0', STR_PAD_LEFT);
$order_date = date('d/m/Y H:i', strtotime($order['tanggal_order']));
$cetak_date = date('d/m/Y H:i:s');
$alamat = !empty($order['alamat']) ? $order['alamat'] : 'Ambil di Tempat / Tanpa Alamat';
$no_telp = !empty($order['no_telp']) ? $order['no_telp'] : '-';
$metode = !empty($order['metode_pembayaran']) ? $order['metode_pembayaran'] : 'COD';
$status_bayar = !empty($order['status_pembayaran']) ? $order['status_pembayaran'] : 'Pending';
$total_harga = intval($order['total_harga']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi #<?php echo $order['id_order']; ?> - Cell’s Bookshelf</title>
    <!-- Google Fonts for authentic monospace receipt -->
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        /* Base Screen Styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            background-color: #1a1728;
            font-family: 'Courier Prime', Courier, monospace;
            color: #111;
            padding: 40px 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        /* Action Bar (Top Controls) */
        .action-bar {
            width: 100%;
            max-width: 380px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            font-family: 'Inter', sans-serif;
        }
        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
        }
        .btn-print {
            background: #d4a853;
            color: #0a0814;
            flex: 1;
            box-shadow: 0 4px 14px rgba(212, 168, 83, 0.35);
        }
        .btn-print:hover {
            background: #c4882a;
            transform: translateY(-1px);
        }
        .btn-photo {
            background: #f3c5b8;
            color: #1e1322;
            box-shadow: 0 4px 14px rgba(243, 197, 184, 0.35);
        }
        .btn-photo:hover {
            background: #fae2dc;
            transform: translateY(-1px);
        }
        .btn-back {
            background: rgba(255, 255, 255, 0.1);
            color: #f0ebe0;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
        .btn-back:hover {
            background: rgba(255, 255, 255, 0.18);
            color: #fff;
        }

        /* Thermal Receipt Paper Container */
        .receipt-container {
            width: 100%;
            max-width: 360px;
            background: #ffffff;
            color: #000000;
            padding: 24px 20px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.5);
            border-radius: 4px;
            position: relative;
            font-size: 12px;
            line-height: 1.4;
        }

        /* Subtle zig-zag tear top and bottom for receipt authenticity */
        .receipt-container::before,
        .receipt-container::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            height: 6px;
            background-size: 12px 6px;
            background-repeat: repeat-x;
        }
        .receipt-container::before {
            top: -6px;
            background-image: radial-gradient(circle at 6px 6px, transparent 6px, #ffffff 6px);
        }
        .receipt-container::after {
            bottom: -6px;
            background-image: radial-gradient(circle at 6px 0, transparent 6px, #ffffff 6px);
        }

        /* Header Elements */
        .store-header {
            text-align: center;
            margin-bottom: 12px;
        }
        .store-name {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .store-subtitle {
            font-size: 10px;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .store-address {
            font-size: 10px;
            color: #333;
            line-height: 1.3;
        }

        /* Divider lines */
        .divider-dashed {
            border-top: 1px dashed #444;
            margin: 10px 0;
        }
        .divider-double {
            border-top: 2px solid #000;
            margin: 10px 0;
        }

        /* Key-Value Metadata table */
        .meta-table {
            width: 100%;
            font-size: 11px;
            margin-bottom: 4px;
        }
        .meta-table td {
            padding: 1.5px 0;
            vertical-align: top;
        }
        .meta-table .label {
            width: 42%;
            color: #444;
        }
        .meta-table .val {
            width: 58%;
            text-align: right;
            font-weight: 600;
        }

        /* Item Rows */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        .items-table th {
            text-align: left;
            padding: 4px 0;
            border-bottom: 1px dashed #444;
            font-size: 10px;
            text-transform: uppercase;
        }
        .item-row td {
            padding-top: 6px;
            padding-bottom: 2px;
            vertical-align: top;
        }
        .item-title {
            font-weight: 700;
            font-size: 11px;
        }
        .item-author {
            font-size: 9.5px;
            color: #555;
            margin-bottom: 2px;
        }
        .item-calc {
            display: flex;
            justify-content: space-between;
            font-size: 10.5px;
            color: #222;
        }

        /* Calculation Summary */
        .calc-summary {
            width: 100%;
            font-size: 11px;
            margin-top: 4px;
        }
        .calc-summary td {
            padding: 2.5px 0;
        }
        .calc-summary .right {
            text-align: right;
        }
        .total-row td {
            font-size: 14px;
            font-weight: 700;
            padding: 6px 0;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
        }

        /* Barcode Area */
        .barcode-section {
            text-align: center;
            margin-top: 16px;
            padding-top: 10px;
        }
        .barcode-svg {
            margin: 0 auto 4px auto;
            display: block;
            max-width: 220px;
            height: 38px;
        }
        .barcode-no {
            font-size: 10px;
            letter-spacing: 3px;
            font-weight: 700;
        }

        /* Footer Notes */
        .receipt-footer {
            text-align: center;
            margin-top: 14px;
            font-size: 9.5px;
            color: #333;
            line-height: 1.4;
        }
        .status-stamp {
            display: inline-block;
            margin: 8px 0;
            padding: 3px 12px;
            border: 1.5px solid #000;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            border-radius: 4px;
        }

        /* Print Settings (Strict Standard Thermal 80mm / POS Paper) */
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .action-bar {
                display: none !important;
            }
            .receipt-container {
                box-shadow: none !important;
                border-radius: 0 !important;
                padding: 4mm !important;
                width: 100% !important;
                max-width: 80mm !important;
                margin: 0 auto !important;
            }
            .receipt-container::before,
            .receipt-container::after {
                display: none !important;
            }
            @page {
                size: 80mm auto;
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <!-- On-screen Action Toolbar -->
    <div class="action-bar">
        <a href="orders.php" class="btn-action btn-back">
            ← Kembali
        </a>
        <button onclick="window.print()" class="btn-action btn-print">
            🖨️ Cetak / Simpan PDF
        </button>
        <button id="btn-download-img" onclick="downloadReceiptImage()" class="btn-action btn-photo">
            📸 Unduh Foto (PNG)
        </button>
    </div>

    <!-- Thermal POS Receipt Card -->
    <div class="receipt-container">
        
        <!-- Store Identity -->
        <div class="store-header">
            <div class="store-name" style="font-family: 'Cormorant Garamond', 'Playfair Display', serif; font-size: 21px; font-weight: 700; letter-spacing: 2px;">CELL’S BOOKSHELF</div>
            <div class="store-subtitle" style="font-size: 9px; letter-spacing: 2px; color: #555;">Curated Literary Boutique</div>
            <div class="store-address">
                Boutique Sanctuary, Jakarta Selatan<br>
                CS: 0812-3456-7890 · concierge@cellsbookshelf.co.id<br>
                www.cellsbookshelf.co.id
            </div>
        </div>

        <div class="divider-double"></div>

        <!-- Order Meta -->
        <table class="meta-table">
            <tr>
                <td class="label">No. Struk</td>
                <td class="val">#<?php echo $order_no; ?></td>
            </tr>
            <tr>
                <td class="label">Waktu Order</td>
                <td class="val"><?php echo $order_date; ?></td>
            </tr>
            <tr>
                <td class="label">Waktu Cetak</td>
                <td class="val"><?php echo $cetak_date; ?></td>
            </tr>
            <tr>
                <td class="label">Kasir / Terminal</td>
                <td class="val">SYSTEM-ONLINE #POS1</td>
            </tr>
            <tr>
                <td class="label">Pelanggan</td>
                <td class="val"><?php echo htmlspecialchars($order['nama']); ?></td>
            </tr>
            <tr>
                <td class="label">No. Telepon</td>
                <td class="val"><?php echo htmlspecialchars($no_telp); ?></td>
            </tr>
            <tr>
                <td class="label">Tujuan Kirim</td>
                <td class="val" style="word-break: break-word;"><?php echo htmlspecialchars($alamat); ?></td>
            </tr>
        </table>

        <div class="divider-dashed"></div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 65%;">Karya Pustaka</th>
                    <th style="width: 35%; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): 
                    $subtotal = intval($item['harga']) * intval($item['jumlah']);
                ?>
                    <tr class="item-row">
                        <td colspan="2">
                            <div class="item-title"><?php echo htmlspecialchars($item['judul']); ?></div>
                            <div class="item-author">Oleh: <?php echo htmlspecialchars($item['penulis']); ?></div>
                            <div class="item-calc">
                                <span><?php echo $item['jumlah']; ?> x Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></span>
                                <span style="font-weight: 700;">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></span>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="divider-dashed"></div>

        <!-- Calculations & Totals -->
        <table class="calc-summary">
            <tr>
                <td>Total Kuantitas</td>
                <td class="right"><?php echo $total_qty; ?> Eksemplar</td>
            </tr>
            <tr>
                <td>Subtotal Belanja</td>
                <td class="right">Rp <?php echo number_format($total_harga, 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <td>Biaya Pengiriman</td>
                <td class="right">Rp 0 (Bebas Biaya)</td>
            </tr>
            <tr>
                <td>PPN (0%)</td>
                <td class="right">Rp 0</td>
            </tr>
            <tr class="total-row">
                <td>TOTAL TAGIHAN</td>
                <td class="right">Rp <?php echo number_format($total_harga, 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <td style="padding-top: 6px;">Metode Pembayaran</td>
                <td class="right" style="padding-top: 6px; font-weight: 700;"><?php echo htmlspecialchars($metode); ?></td>
            </tr>
            <tr>
                <td>Status Pelunasan</td>
                <td class="right" style="font-weight: 700;"><?php echo htmlspecialchars($status_bayar); ?></td>
            </tr>
        </table>

        <!-- Stamp -->
        <div style="text-align: center;">
            <div class="status-stamp">
                <?php 
                if ($order['status'] === 'selesai' || $status_bayar === 'Lunas') {
                    echo "✓ LUNAS - RESMI";
                } elseif ($metode === 'Bayar di Tempat (COD)') {
                    echo "TAGIHAN COD - KURIR";
                } else {
                    echo "MENUNGGU VERIFIKASI";
                }
                ?>
            </div>
        </div>

        <!-- Barcode Graphic (Pure SVG Code128 pattern) -->
        <div class="barcode-section">
            <svg class="barcode-svg" viewBox="0 0 200 40" preserveAspectRatio="none">
                <!-- Barcode bars pattern -->
                <rect x="5" y="0" width="3" height="40" fill="#000"/>
                <rect x="11" y="0" width="2" height="40" fill="#000"/>
                <rect x="16" y="0" width="5" height="40" fill="#000"/>
                <rect x="24" y="0" width="2" height="40" fill="#000"/>
                <rect x="29" y="0" width="4" height="40" fill="#000"/>
                <rect x="36" y="0" width="2" height="40" fill="#000"/>
                <rect x="41" y="0" width="5" height="40" fill="#000"/>
                <rect x="49" y="0" width="2" height="40" fill="#000"/>
                <rect x="54" y="0" width="3" height="40" fill="#000"/>
                <rect x="60" y="0" width="4" height="40" fill="#000"/>
                <rect x="67" y="0" width="2" height="40" fill="#000"/>
                <rect x="72" y="0" width="6" height="40" fill="#000"/>
                <rect x="81" y="0" width="3" height="40" fill="#000"/>
                <rect x="87" y="0" width="2" height="40" fill="#000"/>
                <rect x="92" y="0" width="5" height="40" fill="#000"/>
                <rect x="100" y="0" width="2" height="40" fill="#000"/>
                <rect x="105" y="0" width="4" height="40" fill="#000"/>
                <rect x="112" y="0" width="3" height="40" fill="#000"/>
                <rect x="118" y="0" width="2" height="40" fill="#000"/>
                <rect x="123" y="0" width="6" height="40" fill="#000"/>
                <rect x="132" y="0" width="2" height="40" fill="#000"/>
                <rect x="137" y="0" width="4" height="40" fill="#000"/>
                <rect x="144" y="0" width="3" height="40" fill="#000"/>
                <rect x="150" y="0" width="2" height="40" fill="#000"/>
                <rect x="155" y="0" width="5" height="40" fill="#000"/>
                <rect x="163" y="0" width="2" height="40" fill="#000"/>
                <rect x="168" y="0" width="4" height="40" fill="#000"/>
                <rect x="175" y="0" width="2" height="40" fill="#000"/>
                <rect x="180" y="0" width="5" height="40" fill="#000"/>
                <rect x="188" y="0" width="3" height="40" fill="#000"/>
            </svg>
            <div class="barcode-no">*<?php echo $order['id_order']; ?>*</div>
        </div>

        <div class="divider-dashed"></div>

        <!-- Retail Footer -->
        <div class="receipt-footer">
            <p style="font-weight: 700; margin-bottom: 3px;">TERIMA KASIH ATAS KUNJUNGAN ANDA</p>
            <p>Buku yang telah dibeli tidak dapat ditukar atau dikembalikan kecuali terdapat cacat cetak fisik (maksimal 3 hari sejak barang diterima).</p>
            <p style="margin-top: 5px;">Simpan struk ini sebagai bukti transaksi resmi BookStore.</p>
        </div>

    </div>

    <script>
        function downloadReceiptImage() {
            const receiptEl = document.querySelector('.receipt-container');
            const btn = document.getElementById('btn-download-img');
            const originalText = btn.innerHTML;
            btn.innerHTML = '⏳ Memproses Foto...';
            btn.disabled = true;

            html2canvas(receiptEl, {
                scale: 3,
                backgroundColor: '#ffffff',
                useCORS: true,
                logging: false
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'Struk_Cells_Bookshelf_#<?php echo $order['id_order']; ?>.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
                btn.innerHTML = '✅ Foto Tersimpan!';
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }, 2500);
            }).catch(err => {
                console.error(err);
                alert('Gagal menghasilkan gambar struk. Silakan coba kembali.');
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }
    </script>
</body>
</html>


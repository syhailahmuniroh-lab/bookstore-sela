<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../koneksi.php';

// 1. Guard check - Admin only
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$tab = isset($_GET['tab']) ? $_GET['tab'] : 'stats';

// --- CONTROLLER ACTIONS ---

// Action: Update Order Status
if (isset($_GET['update_order']) && isset($_GET['status'])) {
    $order_id = intval($_GET['update_order']);
    $new_status = mysqli_real_escape_string($conn, $_GET['status']);
    
    $q = "UPDATE orders SET status = '$new_status' WHERE id_order = $order_id";
    mysqli_query($conn, $q);
    header("Location: index.php?tab=orders");
    exit;
}

// Action: Delete Book
if (isset($_GET['delete_book'])) {
    $book_id = intval($_GET['delete_book']);
    $q = "DELETE FROM books WHERE id_buku = $book_id";
    mysqli_query($conn, $q);
    header("Location: index.php?tab=books");
    exit;
}

// Action: Add/Edit Book Form Submission
if (isset($_POST['save_book'])) {
    $title = mysqli_real_escape_string($conn, $_POST['judul']);
    $author = mysqli_real_escape_string($conn, $_POST['penulis']);
    $id_cat = intval($_POST['id_kategori']);
    $price = intval($_POST['harga']);
    $stok = isset($_POST['stok']) ? intval($_POST['stok']) : 25;
    $cover = mysqli_real_escape_string($conn, $_POST['gambar']);
    $desc = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    
    if (isset($_POST['id_buku']) && !empty($_POST['id_buku'])) {
        // Edit Book
        $id_buku = intval($_POST['id_buku']);
        $q = "UPDATE books SET judul='$title', penulis='$author', id_kategori=$id_cat, harga=$price, stok=$stok, gambar='$cover', deskripsi='$desc' WHERE id_buku=$id_buku";
    } else {
        // Add Book
        $q = "INSERT INTO books (judul, penulis, id_kategori, harga, stok, gambar, deskripsi) VALUES ('$title', '$author', $id_cat, $price, $stok, '$cover', '$desc')";
    }
    mysqli_query($conn, $q);
    header("Location: index.php?tab=books");
    exit;
}

// Action: Add/Edit Category Form Submission
if (isset($_POST['save_category'])) {
    $cat_name = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    
    if (isset($_POST['id_kategori']) && !empty($_POST['id_kategori'])) {
        // Edit Category
        $id_cat = intval($_POST['id_kategori']);
        $q = "UPDATE categories SET nama_kategori='$cat_name' WHERE id_kategori=$id_cat";
    } else {
        // Add Category
        $q = "INSERT INTO categories (nama_kategori) VALUES ('$cat_name')";
    }
    mysqli_query($conn, $q);
    header("Location: index.php?tab=categories");
    exit;
}

// Action: Delete Category
if (isset($_GET['delete_cat'])) {
    $id_cat = intval($_GET['delete_cat']);
    // Temporarily disable foreign key checks to prevent errors if books belong to it
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");
    $q = "DELETE FROM categories WHERE id_kategori = $id_cat";
    mysqli_query($conn, $q);
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");
    header("Location: index.php?tab=categories");
    exit;
}

// Fetch stats data
$total_books_res = mysqli_query($conn, "SELECT COUNT(*) as count FROM books");
$total_books = mysqli_fetch_assoc($total_books_res)['count'];

$total_orders_res = mysqli_query($conn, "SELECT COUNT(*) as count, SUM(total_harga) as sum FROM orders");
$orders_data = mysqli_fetch_assoc($total_orders_res);
$total_orders = $orders_data['count'];
$total_sales = $orders_data['sum'] ? $orders_data['sum'] : 0;

$total_users_res = mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role = 'user'");
$total_users = mysqli_fetch_assoc($total_users_res)['count'];

$total_cats_res = mysqli_query($conn, "SELECT COUNT(*) as count FROM categories");
$total_cats = mysqli_fetch_assoc($total_cats_res)['count'];

// Fetch counts for Chart.js
$pending_count_res = mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE status='pending'");
$pending_count = mysqli_fetch_assoc($pending_count_res)['count'];
$dikirim_count_res = mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE status='dikirim'");
$dikirim_count = mysqli_fetch_assoc($dikirim_count_res)['count'];
$selesai_count_res = mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE status='selesai'");
$selesai_count = mysqli_fetch_assoc($selesai_count_res)['count'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Cell’s Bookshelf</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
              serif: ['Playfair Display', 'ui-serif', 'Georgia', 'serif'],
            },
            colors: {
              emas: {
                DEFAULT: '#d4a853',
                hover: '#c4882a',
                light: '#f5d78e',
              },
              darkBg: '#0a0814',
              darkPanel: '#14112a',
            }
          }
        }
      }
    </script>
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom theme styles -->
    <link rel="stylesheet" href="../style.css">
</head>
<body class="bg-darkBg text-[#f0ebe0] font-sans min-h-screen flex flex-col">

    <!-- Admin Header -->
    <header class="sticky top-0 z-40 px-6 py-4 flex items-center justify-between border-b border-[rgba(212,168,83,0.1)] bg-[rgba(14,11,28,0.95)] backdrop-blur-md">
        <div class="flex items-center gap-2">
            <i data-lucide="book-open" class="w-5 h-5 text-emas"></i>
            <span class="font-serif font-normal text-lg sm:text-xl text-transparent bg-clip-text bg-gradient-to-r from-amber-100 via-rose-200 to-amber-200">Cell’s Bookshelf <span class="text-[10px] text-rose-300 font-sans ml-1 uppercase px-2.5 py-0.5 rounded-full bg-rose-400/10 border border-rose-300/20 font-semibold tracking-wider">Management Portal</span></span>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-xs text-[#f0ebe0]/60">Halo, <b><?php echo htmlspecialchars($_SESSION['nama']); ?></b></span>
            <a href="../logout.php" class="text-xs px-3 py-1.5 rounded-xl border border-red-500/20 bg-red-500/10 text-red-400 hover:bg-red-500/20 transition-all font-semibold">
                Keluar
            </a>
        </div>
    </header>

    <div class="flex-1 flex flex-col lg:flex-row">
        
        <!-- Sidebar Menu -->
        <aside class="w-full lg:w-64 border-b lg:border-b-0 lg:border-r border-white/[0.08] bg-white/[0.02] flex flex-row lg:flex-col lg:py-6 overflow-x-auto lg:overflow-x-visible">
            <a href="index.php?tab=stats" class="flex-1 lg:flex-none flex items-center gap-3 px-6 py-4 border-b border-transparent text-xs font-semibold <?php echo $tab === 'stats' ? 'text-emas bg-white/[0.03] lg:border-r-2 lg:border-emas' : 'text-[#f0ebe0]/50 hover:text-white'; ?>">
                <i data-lucide="bar-chart-3" class="w-4 h-4"></i> Statistik
            </a>
            <a href="index.php?tab=categories" class="flex-1 lg:flex-none flex items-center gap-3 px-6 py-4 border-b border-transparent text-xs font-semibold <?php echo $tab === 'categories' ? 'text-emas bg-white/[0.03] lg:border-r-2 lg:border-emas' : 'text-[#f0ebe0]/50 hover:text-white'; ?>">
                <i data-lucide="tag" class="w-4 h-4"></i> Kategori
            </a>
            <a href="index.php?tab=books" class="flex-1 lg:flex-none flex items-center gap-3 px-6 py-4 border-b border-transparent text-xs font-semibold <?php echo $tab === 'books' ? 'text-emas bg-white/[0.03] lg:border-r-2 lg:border-emas' : 'text-[#f0ebe0]/50 hover:text-white'; ?>">
                <i data-lucide="book-text" class="w-4 h-4"></i> Data Buku
            </a>
            <a href="index.php?tab=users" class="flex-1 lg:flex-none flex items-center gap-3 px-6 py-4 border-b border-transparent text-xs font-semibold <?php echo $tab === 'users' ? 'text-emas bg-white/[0.03] lg:border-r-2 lg:border-emas' : 'text-[#f0ebe0]/50 hover:text-white'; ?>">
                <i data-lucide="users" class="w-4 h-4"></i> Pelanggan
            </a>
            <a href="index.php?tab=orders" class="flex-1 lg:flex-none flex items-center gap-3 px-6 py-4 border-b border-transparent text-xs font-semibold <?php echo $tab === 'orders' ? 'text-emas bg-white/[0.03] lg:border-r-2 lg:border-emas' : 'text-[#f0ebe0]/50 hover:text-white'; ?>">
                <i data-lucide="package" class="w-4 h-4"></i> Pesanan
            </a>
        </aside>

        <!-- Main Workspace -->
        <main class="flex-1 p-6 overflow-y-auto">
            
            <?php if ($tab === 'stats'): ?>
                <!-- ================= TAB: STATISTICS ================= -->
                <h3 class="font-serif font-bold text-xl mb-6 text-white">Statistik Utama</h3>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <!-- Revenue card -->
                    <div class="p-5 rounded-2xl bg-white/[0.04] border border-white/[0.08] flex flex-col justify-between relative overflow-hidden">
                        <span class="text-[10px] text-[#f0ebe0]/40 uppercase tracking-widest font-semibold">Total Pendapatan</span>
                        <h4 class="text-xl sm:text-2xl font-serif font-bold text-emas mt-2">Rp <?php echo number_format($total_sales, 0, ',', '.'); ?></h4>
                    </div>
                    <!-- Orders count card -->
                    <div class="p-5 rounded-2xl bg-white/[0.04] border border-white/[0.08] flex flex-col justify-between relative overflow-hidden">
                        <span class="text-[10px] text-[#f0ebe0]/40 uppercase tracking-widest font-semibold">Total Transaksi</span>
                        <h4 class="text-xl sm:text-2xl font-serif font-bold text-white mt-2"><?php echo $total_orders; ?> Order</h4>
                    </div>
                    <!-- Books count card -->
                    <div class="p-5 rounded-2xl bg-white/[0.04] border border-white/[0.08] flex flex-col justify-between relative overflow-hidden">
                        <span class="text-[10px] text-[#f0ebe0]/40 uppercase tracking-widest font-semibold">Stok Buku</span>
                        <h4 class="text-xl sm:text-2xl font-serif font-bold text-white mt-2"><?php echo $total_books; ?> Buku</h4>
                    </div>
                    <!-- Users count card -->
                    <div class="p-5 rounded-2xl bg-white/[0.04] border border-white/[0.08] flex flex-col justify-between relative overflow-hidden">
                        <span class="text-[10px] text-[#f0ebe0]/40 uppercase tracking-widest font-semibold">Pelanggan Aktif</span>
                        <h4 class="text-xl sm:text-2xl font-serif font-bold text-white mt-2"><?php echo $total_users; ?> User</h4>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Latest messages -->
                    <div class="lg:col-span-2 rounded-2xl p-6 bg-white/[0.04] border border-white/[0.08]">
                        <h4 class="font-serif font-bold text-sm mb-4 text-white uppercase tracking-wider">Kotak Masuk Pesan</h4>
                        <div class="space-y-3 max-h-80 overflow-y-auto pr-1">
                            <?php
                            $msg_q = "SELECT m.*, u.nama, u.email FROM messages m LEFT JOIN users u ON m.id_user = u.id_user ORDER BY m.id_pesan DESC";
                            $msg_res = mysqli_query($conn, $msg_q);
                            if (mysqli_num_rows($msg_res) === 0):
                            ?>
                                <p class="text-xs text-[#f0ebe0]/30 text-center py-6">Belum ada pesan masuk</p>
                            <?php 
                            else:
                                while($msg = mysqli_fetch_assoc($msg_res)):
                                    $msg_date = date('d M Y, H:i', strtotime($msg['tanggal']));
                            ?>
                                <div class="p-4 rounded-xl bg-white/[0.02] border border-white/[0.05] space-y-1">
                                    <div class="flex justify-between items-center text-[10px] text-[#f0ebe0]/40">
                                        <span><b><?php echo htmlspecialchars($msg['nama']); ?></b> (<?php echo htmlspecialchars($msg['email']); ?>)</span>
                                        <span><?php echo $msg_date; ?></span>
                                    </div>
                                    <p class="text-xs text-[#f0ebe0]/80 leading-relaxed"><?php echo htmlspecialchars($msg['isi_pesan']); ?></p>
                                </div>
                            <?php 
                                endwhile;
                            endif;
                            ?>
                        </div>
                    </div>

                    <!-- Chart Widget -->
                    <div class="rounded-2xl p-6 bg-white/[0.04] border border-white/[0.08] flex flex-col justify-between">
                        <div>
                            <h4 class="font-serif font-bold text-sm mb-4 text-white uppercase tracking-wider">Status Transaksi</h4>
                            <div class="relative w-full aspect-square max-h-52 flex items-center justify-center">
                                <canvas id="orderStatusChart"></canvas>
                            </div>
                        </div>
                        <div class="text-[10px] text-[#f0ebe0]/40 mt-4 border-t border-white/[0.05] pt-3 text-center">
                            Total <?php echo $total_orders; ?> Pesanan Terdaftar
                        </div>
                    </div>
                </div>

            <?php elseif ($tab === 'categories'): ?>
                <!-- ================= TAB: CATEGORIES ================= -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="font-serif font-bold text-xl text-white">Kategori Buku</h3>
                        <p class="text-xs text-[#f0ebe0]/40 mt-0.5"><?php echo $total_cats; ?> kategori aktif</p>
                    </div>
                    <button onclick="openCategoryModal()" class="text-xs px-4 py-2.5 rounded-xl btn-gold text-darkBg font-semibold shadow-md">
                        + Tambah Kategori
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php
                    $cats_q = "SELECT c.*, COUNT(b.id_buku) as book_count FROM categories c LEFT JOIN books b ON c.id_kategori = b.id_kategori GROUP BY c.id_kategori ORDER BY c.id_kategori DESC";
                    $cats_res = mysqli_query($conn, $cats_q);
                    while ($cat = mysqli_fetch_assoc($cats_res)):
                        $js_cat_data = htmlspecialchars(json_encode($cat), ENT_QUOTES, 'UTF-8');
                    ?>
                        <div class="rounded-2xl p-5 bg-white/[0.04] border border-white/[0.07] flex flex-col justify-between">
                            <div>
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl mb-4 bg-emas/10">🏷️</div>
                                <div class="font-serif font-bold text-base mb-2 text-white"><?php echo htmlspecialchars($cat['nama_kategori']); ?></div>
                                <div class="text-xs px-2.5 py-1 rounded-lg bg-emas/10 text-emas border border-emas/15 inline-block mb-4">
                                    <?php echo $cat['book_count']; ?> buku
                                </div>
                            </div>
                            <div class="flex gap-2 w-full pt-2">
                                <button onclick="editCategory(<?php echo $js_cat_data; ?>)" class="flex-1 py-2 rounded-xl text-xs bg-white/[0.06] text-[#f0ebe0]/75 hover:text-white transition-colors">Edit</button>
                                <a href="index.php?tab=categories&delete_cat=<?php echo $cat['id_kategori']; ?>" 
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Semua buku terkait akan kehilangan kategorinya.')" 
                                   class="flex-1 py-2 rounded-xl text-xs bg-red-500/10 text-red-400 hover:bg-red-500/20 text-center font-medium transition-colors">Hapus</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

            <?php elseif ($tab === 'books'): ?>
                <!-- ================= TAB: MANAGE BOOKS ================= -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
                    <h3 class="font-serif font-bold text-xl text-white">Kelola Buku</h3>
                    <button onclick="openBookFormModal()" class="text-xs px-4 py-2.5 rounded-xl btn-gold text-darkBg font-semibold shadow-md">
                        + Tambah Buku Baru
                    </button>
                </div>

                <!-- Books Table -->
                <div class="overflow-x-auto rounded-2xl border border-white/[0.08] bg-white/[0.02]">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-white/[0.04] text-emas uppercase tracking-wider text-[10px] border-b border-white/[0.08]">
                            <tr>
                                <th class="p-4">Cover</th>
                                <th class="p-4">Judul / Penulis</th>
                                <th class="p-4">Kategori</th>
                                <th class="p-4">Harga</th>
                                <th class="p-4 text-center">Stok</th>
                                <th class="p-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/[0.05]">
                            <?php
                            $books_q = "SELECT b.*, c.nama_kategori FROM books b LEFT JOIN categories c ON b.id_kategori = c.id_kategori ORDER BY b.id_buku DESC";
                            $books_res = mysqli_query($conn, $books_q);
                            while ($b = mysqli_fetch_assoc($books_res)):
                                $price_fmt = 'Rp ' . number_format($b['harga'], 0, ',', '.');
                                $stok_val = intval($b['stok']);
                                $js_edit_data = htmlspecialchars(json_encode($b), ENT_QUOTES, 'UTF-8');
                            ?>
                                <tr>
                                    <td class="p-4">
                                        <img src="<?php echo htmlspecialchars($b['gambar']); ?>" alt="Cover" class="w-10 h-14 object-cover rounded-lg bg-[#1a1528] border border-white/10">
                                    </td>
                                    <td class="p-4">
                                        <div class="font-bold text-white"><?php echo htmlspecialchars($b['judul']); ?></div>
                                        <div class="text-[#f0ebe0]/40 text-[10px] mt-0.5"><?php echo htmlspecialchars($b['penulis']); ?></div>
                                    </td>
                                    <td class="p-4">
                                        <span class="px-2.5 py-0.5 rounded-lg bg-emas/10 text-emas border border-emas/15 text-[10px] font-medium">
                                            <?php echo htmlspecialchars($b['nama_kategori'] ? $b['nama_kategori'] : 'Tanpa Kategori'); ?>
                                        </span>
                                    </td>
                                    <td class="p-4 font-semibold text-white"><?php echo $price_fmt; ?></td>
                                    <td class="p-4 text-center">
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold <?php echo $stok_val > 0 ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/20' : 'bg-red-500/15 text-red-400 border border-red-500/20'; ?>">
                                            <?php echo $stok_val; ?>
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex items-center justify-center gap-3">
                                            <button onclick="editBook(<?php echo $js_edit_data; ?>)" class="text-emas hover:underline font-semibold">Edit</button>
                                            <a href="index.php?tab=books&delete_book=<?php echo $b['id_buku']; ?>" 
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')" 
                                               class="text-red-400 hover:underline">Hapus</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($tab === 'users'): ?>
                <!-- ================= TAB: USERS LIST ================= -->
                <h3 class="font-serif font-bold text-xl mb-6 text-white">Daftar Pelanggan</h3>
                
                <!-- Users Table -->
                <div class="overflow-x-auto rounded-2xl border border-white/[0.08] bg-white/[0.02]">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-white/[0.04] text-emas uppercase tracking-wider text-[10px] border-b border-white/[0.08]">
                            <tr>
                                <th class="p-4">Nama Pelanggan</th>
                                <th class="p-4">Email</th>
                                <th class="p-4">Role</th>
                                <th class="p-4 text-center">Total Pesanan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/[0.05]">
                            <?php
                            $users_q = "SELECT u.*, COUNT(o.id_order) as orders_count FROM users u LEFT JOIN orders o ON u.id_user = o.id_user GROUP BY u.id_user ORDER BY u.id_user DESC";
                            $users_res = mysqli_query($conn, $users_q);
                            while ($u = mysqli_fetch_assoc($users_res)):
                            ?>
                                <tr>
                                    <td class="p-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-bold font-serif bg-gradient-to-r from-emas-hover to-emas text-darkBg">
                                                <?php echo strtoupper(substr($u['nama'], 0, 1)); ?>
                                            </div>
                                            <span class="font-bold text-white"><?php echo htmlspecialchars($u['nama']); ?></span>
                                        </div>
                                    </td>
                                    <td class="p-4 text-[#f0ebe0]/60"><?php echo htmlspecialchars($u['email']); ?></td>
                                    <td class="p-4">
                                        <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-semibold border <?php echo $u['role'] === 'admin' ? 'bg-red-500/10 text-red-400 border-red-500/20' : 'bg-emas/10 text-emas border-emas/15'; ?>">
                                            <?php echo strtoupper($u['role']); ?>
                                        </span>
                                    </td>
                                    <td class="p-4 text-center font-bold text-emas"><?php echo $u['orders_count']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($tab === 'orders'): ?>
                <!-- ================= TAB: MANAGE ORDERS ================= -->
                <h3 class="font-serif font-bold text-xl mb-6 text-white">Kelola Pesanan</h3>
                
                <!-- Orders Table -->
                <div class="overflow-x-auto rounded-2xl border border-white/[0.08] bg-white/[0.02]">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-white/[0.04] text-emas uppercase tracking-wider text-[10px] border-b border-white/[0.08]">
                            <tr>
                                <th class="p-4">Order ID</th>
                                <th class="p-4">Pelanggan</th>
                                <th class="p-4">Tanggal</th>
                                <th class="p-4">Total Uang</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 text-center">Ganti Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/[0.05]">
                            <?php
                            $orders_q = "SELECT o.*, u.nama, u.email FROM orders o LEFT JOIN users u ON o.id_user = u.id_user ORDER BY o.id_order DESC";
                            $orders_res = mysqli_query($conn, $orders_q);
                            while ($o = mysqli_fetch_assoc($orders_res)):
                                $total_fmt = 'Rp ' . number_format($o['total_harga'], 0, ',', '.');
                                $order_date = date('d M Y, H:i', strtotime($o['tanggal_order']));
                                
                                // status classes
                                $badge_class = 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20';
                                if ($o['status'] === 'dikirim') $badge_class = 'bg-blue-500/10 text-blue-400 border-blue-500/20';
                                if ($o['status'] === 'selesai') $badge_class = 'bg-green-500/10 text-green-400 border-green-500/20';
                            ?>
                                <tr>
                                    <td class="p-4 font-mono font-bold text-[#f0ebe0]/75">#<?php echo $o['id_order']; ?></td>
                                    <td class="p-4">
                                        <div class="font-bold text-white"><?php echo htmlspecialchars($o['nama']); ?></div>
                                        <div class="text-[#f0ebe0]/40 text-[10px] mt-0.5"><?php echo htmlspecialchars($o['email']); ?></div>
                                        <?php if (!empty($o['alamat'])): ?>
                                            <div class="mt-2 text-[10px] text-[#f0ebe0]/70 bg-white/[0.03] border border-white/[0.06] p-2 rounded-xl">
                                                <span class="text-[8px] uppercase tracking-wider text-emas font-bold block mb-0.5">Alamat Pengiriman:</span>
                                                <span><?php echo nl2br(htmlspecialchars($o['alamat'])); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <!-- Relational ordered items array display -->
                                        <div class="flex gap-1.5 mt-2.5 flex-wrap max-w-xs">
                                            <?php
                                             $oid = $o['id_order'];
                                            $o_items = mysqli_query($conn, "SELECT oi.jumlah, b.judul, b.gambar FROM order_items oi JOIN books b ON oi.id_buku = b.id_buku WHERE oi.id_order = $oid");
                                            while ($item = mysqli_fetch_assoc($o_items)):
                                            ?>
                                                <div class="flex items-center gap-1 bg-white/[0.03] border border-white/[0.05] p-1 pr-2 rounded-lg text-[9px]">
                                                    <img src="<?php echo htmlspecialchars($item['gambar']); ?>" alt="Cover" class="w-6 h-8 object-cover rounded-md bg-[#1a1528] border border-white/5">
                                                    <span class="text-[#f0ebe0]/60 truncate max-w-[60px]" title="<?php echo htmlspecialchars($item['judul']); ?>"><?php echo htmlspecialchars($item['judul']); ?></span>
                                                    <span class="text-emas font-bold">x<?php echo $item['jumlah']; ?></span>
                                                </div>
                                            <?php endwhile; ?>
                                        </div>
                                    </td>
                                    <td class="p-4 text-[#f0ebe0]/60"><?php echo $order_date; ?></td>
                                    <td class="p-4 font-bold text-emas"><?php echo $total_fmt; ?></td>
                                    <td class="p-4">
                                        <div class="space-y-1">
                                            <span class="px-2 py-0.5 rounded-lg border text-[10px] font-semibold <?php echo $badge_class; ?>">
                                                <?php echo ucfirst($o['status']); ?>
                                            </span>
                                            <div class="text-[9px] font-bold text-emas mt-1">
                                                <?php echo isset($o['metode_pembayaran']) ? htmlspecialchars($o['metode_pembayaran']) : 'COD'; ?>
                                            </div>
                                            <div class="text-[8px] text-[#f0ebe0]/50">
                                                <?php echo isset($o['status_pembayaran']) ? htmlspecialchars($o['status_pembayaran']) : 'Menunggu Konfirmasi'; ?>
                                            </div>

                                            <?php if (!empty($o['bukti_pembayaran'])): 
                                                $ext = strtolower(pathinfo($o['bukti_pembayaran'], PATHINFO_EXTENSION));
                                            ?>
                                                <div class="pt-1.5">
                                                    <?php if ($ext === 'pdf'): ?>
                                                        <a href="../uploads/bukti/<?php echo urlencode($o['bukti_pembayaran']); ?>" target="_blank" 
                                                           class="px-2 py-1 rounded-lg bg-emas/15 text-emas border border-emas/20 text-[9px] font-semibold hover:bg-emas/25 transition-all inline-flex items-center gap-1">
                                                            📄 Bukti PDF (Buka)
                                                        </a>
                                                    <?php else: ?>
                                                        <button type="button" onclick="previewBukti('../uploads/bukti/<?php echo urlencode($o['bukti_pembayaran']); ?>')" 
                                                                class="px-2 py-1 rounded-lg bg-emas/15 text-emas border border-emas/20 text-[9px] font-semibold hover:bg-emas/25 transition-all inline-flex items-center gap-1">
                                                            🖼️ Bukti Foto (Lihat)
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            <?php elseif ($o['metode_pembayaran'] === 'Bayar di Tempat (COD)' || $o['metode_pembayaran'] === 'COD'): ?>
                                                <div class="text-[8px] text-[#f0ebe0]/40 pt-1 italic">COD (Tanpa Bukti)</div>
                                            <?php else: ?>
                                                <div class="text-[8px] text-red-400/70 pt-1 italic">Belum Ada Bukti</div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                            <a href="../cetak_struk.php?id=<?php echo $o['id_order']; ?>" target="_blank" class="text-[10px] px-2 py-1 rounded bg-emas/15 border border-emas/25 text-emas hover:bg-emas/25 font-semibold transition-all inline-flex items-center gap-1" title="Cetak Struk Resmi">🖨️ Struk</a>
                                            <a href="index.php?tab=orders&update_order=<?php echo $o['id_order']; ?>&status=pending" class="text-[10px] px-2 py-1 rounded bg-yellow-500/10 border border-yellow-500/20 text-yellow-500 hover:bg-yellow-500/20 font-medium transition-all">Pending</a>
                                            <a href="index.php?tab=orders&update_order=<?php echo $o['id_order']; ?>&status=dikirim" class="text-[10px] px-2 py-1 rounded bg-blue-500/10 border border-blue-500/20 text-blue-400 hover:bg-blue-500/20 font-medium transition-all">Kirim</a>
                                            <a href="index.php?tab=orders&update_order=<?php echo $o['id_order']; ?>&status=selesai" class="text-[10px] px-2 py-1 rounded bg-green-500/10 border border-green-500/20 text-green-400 hover:bg-green-500/20 font-medium transition-all">Selesai</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        </main>
    </div>

    <!-- Category Form Dialog Modal -->
    <div id="categoryModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden bg-[rgba(10,8,20,0.8)] backdrop-blur-sm transition-opacity duration-300">
        <div class="w-full max-w-sm rounded-3xl p-6 bg-darkPanel border border-[rgba(212,168,83,0.2)] animate-fade-up">
            <div class="flex justify-between items-center mb-5">
                <h4 id="catModalTitle" class="font-serif font-bold text-lg text-white">Tambah Kategori</h4>
                <button onclick="closeCategoryModal()" class="text-white/40 hover:text-white">&times;</button>
            </div>
            <form method="POST" action="" class="space-y-4 text-xs">
                <input type="hidden" name="id_kategori" id="field_id_kategori">
                <div>
                    <label class="block text-[9px] uppercase tracking-widest font-semibold text-emas mb-1.5">Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="field_nama_kategori" required
                           class="w-full rounded-xl px-4 py-3 outline-none bg-white/[0.04] border border-white/[0.08] text-white focus:border-emas transition-all">
                </div>
                <button type="submit" name="save_category" class="w-full py-3 rounded-xl font-bold text-xs btn-gold text-darkBg">
                    Simpan Kategori
                </button>
            </form>
        </div>
    </div>

    <!-- Add/Edit Book Dialog Modal -->
    <div id="bookFormModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden bg-[rgba(10,8,20,0.8)] backdrop-blur-sm transition-opacity duration-300">
        <div class="w-full max-w-md rounded-3xl p-6 bg-darkPanel border border-[rgba(212,168,83,0.2)] animate-fade-up">
            <div class="flex justify-between items-center mb-4">
                <h4 id="formModalTitle" class="font-serif font-bold text-lg text-white">Tambah Buku Baru</h4>
                <button onclick="closeBookFormModal()" class="text-white/40 hover:text-white">&times;</button>
            </div>
            
            <form method="POST" action="" class="space-y-4 text-xs">
                <input type="hidden" name="id_buku" id="field_id_buku">
                
                <div>
                    <label class="block text-[9px] uppercase tracking-widest font-semibold text-emas mb-1.5">Judul Buku</label>
                    <input type="text" name="judul" id="field_judul" required
                           class="w-full rounded-xl px-4 py-2.5 outline-none bg-white/[0.04] border border-white/[0.08] text-white focus:border-emas transition-all">
                </div>
                
                <div>
                    <label class="block text-[9px] uppercase tracking-widest font-semibold text-emas mb-1.5">Nama Penulis</label>
                    <input type="text" name="penulis" id="field_penulis" required
                           class="w-full rounded-xl px-4 py-2.5 outline-none bg-white/[0.04] border border-white/[0.08] text-white focus:border-emas transition-all">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[9px] uppercase tracking-widest font-semibold text-emas mb-1.5">Kategori</label>
                        <select name="id_kategori" id="field_id_kategori" required
                                class="w-full rounded-xl px-3 py-2.5 outline-none bg-[#14112a] border border-white/[0.08] text-white focus:border-emas transition-all">
                            <?php
                            $cats_q = mysqli_query($conn, "SELECT * FROM categories");
                            while($c = mysqli_fetch_assoc($cats_q)) {
                                echo "<option value='{$c['id_kategori']}'>{$c['nama_kategori']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[9px] uppercase tracking-widest font-semibold text-emas mb-1.5">Harga (Rp)</label>
                        <input type="number" name="harga" id="field_harga" required
                               class="w-full rounded-xl px-4 py-2.5 outline-none bg-white/[0.04] border border-white/[0.08] text-white focus:border-emas transition-all">
                    </div>
                    <div>
                        <label class="block text-[9px] uppercase tracking-widest font-semibold text-emas mb-1.5">Stok Buku</label>
                        <input type="number" name="stok" id="field_stok" min="0" required
                               class="w-full rounded-xl px-4 py-2.5 outline-none bg-white/[0.04] border border-white/[0.08] text-white focus:border-emas transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-[9px] uppercase tracking-widest font-semibold text-emas mb-1.5">Image URL Cover</label>
                    <input type="url" name="gambar" id="field_gambar" required
                           class="w-full rounded-xl px-4 py-2.5 outline-none bg-white/[0.04] border border-white/[0.08] text-white focus:border-emas transition-all"
                           placeholder="https://images.unsplash.com/...">
                </div>

                <div>
                    <label class="block text-[9px] uppercase tracking-widest font-semibold text-emas mb-1.5">Deskripsi Buku</label>
                    <textarea name="deskripsi" id="field_deskripsi" rows="3" required
                              class="w-full rounded-xl px-4 py-2.5 outline-none bg-white/[0.04] border border-white/[0.08] text-white focus:border-emas resize-none transition-all"
                              placeholder="Sinopsis atau deskripsi buku..."></textarea>
                </div>

                <button type="submit" name="save_book" class="w-full py-3 rounded-xl font-bold text-xs btn-gold text-darkBg">
                    Simpan Buku
                </button>
            </form>
        </div>
    </div>

    <script>
        function openCategoryModal() {
            document.getElementById('catModalTitle').innerText = 'Tambah Kategori';
            document.getElementById('field_id_kategori').value = '';
            document.getElementById('field_nama_kategori').value = '';
            
            const modal = document.getElementById('categoryModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function editCategory(cat) {
            document.getElementById('catModalTitle').innerText = 'Edit Kategori';
            document.getElementById('field_id_kategori').value = cat.id_kategori;
            document.getElementById('field_nama_kategori').value = cat.nama_kategori;
            
            const modal = document.getElementById('categoryModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeCategoryModal() {
            const modal = document.getElementById('categoryModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openBookFormModal() {
            document.getElementById('formModalTitle').innerText = 'Tambah Buku Baru';
            document.getElementById('field_id_buku').value = '';
            document.getElementById('field_judul').value = '';
            document.getElementById('field_penulis').value = '';
            document.getElementById('field_harga').value = '';
            document.getElementById('field_stok').value = '25';
            document.getElementById('field_gambar').value = '';
            document.getElementById('field_deskripsi').value = '';
            
            const modal = document.getElementById('bookFormModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeBookFormModal() {
            const modal = document.getElementById('bookFormModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function editBook(book) {
            document.getElementById('formModalTitle').innerText = 'Edit Buku';
            document.getElementById('field_id_buku').value = book.id_buku;
            document.getElementById('field_judul').value = book.judul;
            document.getElementById('field_penulis').value = book.penulis;
            document.getElementById('field_id_kategori').value = book.id_kategori;
            document.getElementById('field_harga').value = book.harga;
            document.getElementById('field_stok').value = book.stok !== undefined ? book.stok : 25;
            document.getElementById('field_gambar').value = book.gambar;
            document.getElementById('field_deskripsi').value = book.deskripsi;
            
            const modal = document.getElementById('bookFormModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function previewBukti(url) {
            Swal.fire({
                title: 'Bukti Pembayaran Pelanggan',
                imageUrl: url,
                imageAlt: 'Bukti Pembayaran',
                background: '#14112a',
                color: '#f0ebe0',
                confirmButtonColor: '#d4a853',
                confirmButtonText: 'Tutup',
                customClass: { popup: 'border border-[rgba(212,168,83,0.3)] rounded-3xl font-sans' }
            });
        }

        // Close modals when clicking outside content
        document.getElementById('categoryModal').addEventListener('click', function(e) {
            if (e.target === this) closeCategoryModal();
        });
        document.getElementById('bookFormModal').addEventListener('click', function(e) {
            if (e.target === this) closeBookFormModal();
        });
    </script>

    <!-- Chart.js Script CDN & Init -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartCanvas = document.getElementById('orderStatusChart');
            if (chartCanvas) {
                const ctx = chartCanvas.getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Pending', 'Dikirim', 'Selesai'],
                        datasets: [{
                            data: [
                                <?php echo $pending_count; ?>, 
                                <?php echo $dikirim_count; ?>, 
                                <?php echo $selesai_count; ?>
                            ],
                            backgroundColor: [
                                'rgba(245, 158, 11, 0.8)',  // amber
                                'rgba(59, 130, 246, 0.8)',  // blue
                                'rgba(34, 197, 94, 0.8)'    // green
                            ],
                            hoverBackgroundColor: [
                                '#fbbf24',
                                '#60a5fa',
                                '#4ade80'
                            ],
                            borderColor: '#14112a',
                            borderWidth: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: '#f0ebe0',
                                    padding: 12,
                                    font: {
                                        family: 'Inter',
                                        size: 10
                                    }
                                }
                            }
                        },
                        cutout: '70%'
                    }
                });
            }
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>

</body>
</html>

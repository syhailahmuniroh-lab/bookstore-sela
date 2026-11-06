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

// Fetch detailed user information
$user_q = mysqli_query($conn, "SELECT * FROM users WHERE id_user = '$id_user'");
$user_info = mysqli_fetch_assoc($user_q);
$no_telp = $user_info['no_telp'] ? $user_info['no_telp'] : 'Belum diisi';
$tanggal_daftar = date('d F Y', strtotime($user_info['tanggal_daftar']));

// Query total orders count
$q_orders = "SELECT COUNT(*) as count FROM orders WHERE id_user = '$id_user'";
$res_orders = mysqli_query($conn, $q_orders);
$orders_data = mysqli_fetch_assoc($res_orders);
$total_orders = $orders_data['count'];
?>

<main class="flex-1">
    <div class="container mx-auto px-5 py-6 max-w-xl">
        <h3 class="font-serif font-bold text-xl mb-6 text-white">Profil Saya</h3>
        
        <!-- Profile Avatar Card -->
        <div class="relative rounded-3xl overflow-hidden mb-6 bg-white/[0.04] border border-white/[0.08] shadow-lg">
            <div class="h-24 bg-gradient-to-r from-[#1a1528] via-[#2a1f10] to-[#1a2a1a]"></div>
            <div class="px-6 pb-6">
                <!-- Avatar circle -->
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-2xl font-serif font-bold -mt-8 mb-3 bg-gradient-to-r from-emas-hover to-emas text-darkBg shadow-xl">
                    <?php echo strtoupper(substr($user_info['nama'], 0, 1)); ?>
                </div>
                <h4 class="font-serif font-bold text-lg text-white"><?php echo htmlspecialchars($user_info['nama']); ?></h4>
                <p class="text-[10px] text-emas/80 uppercase tracking-widest mt-1">Anggota Pustaka Terdaftar · Sejak <?php echo $tanggal_daftar; ?></p>
            </div>
        </div>

        <!-- Profile Details List -->
        <div class="rounded-2xl overflow-hidden mb-6 bg-white/[0.04] border border-white/[0.08]">
            <!-- Email field -->
            <div class="flex items-center gap-4 px-5 py-4 border-b border-white/[0.05]">
                <span class="text-lg w-6">📧</span>
                <div class="flex-1">
                    <div class="text-[10px] uppercase tracking-wider text-[#f0ebe0]/35 mb-0.5">Email</div>
                    <div class="text-xs text-[#f0ebe0]"><?php echo htmlspecialchars($user_info['email']); ?></div>
                </div>
            </div>
            <!-- Telepon field -->
            <div class="flex items-center gap-4 px-5 py-4 border-b border-white/[0.05]">
                <span class="text-lg w-6">📱</span>
                <div class="flex-1">
                    <div class="text-[10px] uppercase tracking-wider text-[#f0ebe0]/35 mb-0.5">Telepon</div>
                    <div class="text-xs text-[#f0ebe0]"><?php echo htmlspecialchars($no_telp); ?></div>
                </div>
            </div>
            <!-- Role field -->
            <div class="flex items-center gap-4 px-5 py-4 border-b border-white/[0.05]">
                <span class="text-lg w-6">👤</span>
                <div class="flex-1">
                    <div class="text-[10px] uppercase tracking-wider text-[#f0ebe0]/35 mb-0.5">Hak Akses</div>
                    <div class="text-xs text-[#f0ebe0]"><?php echo htmlspecialchars(ucfirst($user_info['role'])); ?></div>
                </div>
            </div>
            <!-- Orders count field -->
            <div class="flex items-center gap-4 px-5 py-4">
                <span class="text-lg w-6">📦</span>
                <div class="flex-1">
                    <div class="text-[10px] uppercase tracking-wider text-[#f0ebe0]/35 mb-0.5">Total Pesanan</div>
                    <div class="text-xs text-emas font-semibold"><?php echo $total_orders; ?> pesanan</div>
                </div>
            </div>
        </div>

        <!-- Logout Button -->
        <a href="logout.php" 
           class="block w-full py-4 text-center rounded-2xl font-semibold text-xs border border-[#ef4444]/25 bg-[#ef4444]/10 text-[#f87171] hover:bg-[#ef4444]/20 transition-all">
            Keluar dari Akun
        </a>
    </div>
</main>

<?php
include 'footer.php';
?>

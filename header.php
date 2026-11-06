<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php';

// Dynamically style header based on page context
$is_guest_landing = (basename($_SERVER['PHP_SELF']) == 'index.php' && !isset($_SESSION['id_user']));
$header_class = "sticky top-0 z-40 px-5 py-4 flex items-center justify-between transition-all duration-300";
if ($is_guest_landing) {
    $header_class .= " bg-transparent border-b border-transparent";
} else {
    $header_class .= " bg-[rgba(14,11,28,0.95)] backdrop-blur-md border-b border-[rgba(212,168,83,0.15)]";
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cell’s Bookshelf - Curated Literary Boutique</title>
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,400&family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        serif: ['Cormorant Garamond', 'Playfair Display', 'Georgia', 'serif'],
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
    <!-- Custom theme styles -->
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-darkBg text-[#f0ebe0] font-sans min-h-screen flex flex-col pb-20 md:pb-0">

    <!-- Header / Navbar -->
    <header class="<?php echo $header_class; ?>">
        <a href="index.php" class="group flex items-center gap-3 transition-transform hover:scale-[1.01]">
            <div class="relative flex items-center justify-center">
                <div class="w-9 h-9 rounded-2xl bg-gradient-to-tr from-rose-400/20 via-amber-200/20 to-purple-400/20 border border-rose-200/30 flex items-center justify-center text-rose-200 shadow-[0_0_15px_rgba(244,114,182,0.18)] group-hover:border-rose-200/60 transition-all">
                    <i data-lucide="sparkles" class="w-4 h-4 text-rose-200"></i>
                </div>
                <div class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-amber-300 animate-pulse"></div>
            </div>
            <div class="flex flex-col">
                <span class="font-serif text-xl sm:text-2xl tracking-wide font-normal text-transparent bg-clip-text bg-gradient-to-r from-amber-100 via-rose-200 to-amber-200 leading-tight">
                    Cell’s Bookshelf
                </span>
                <span class="text-[9px] tracking-[0.22em] uppercase text-rose-200/60 font-light -mt-0.5 font-sans">
                    Curated Literary Boutique
                </span>
            </div>
        </a>

        <?php if (isset($_SESSION['id_user'])): ?>
            <!-- Desktop Middle Menu (Visible on desktop screen sizes) -->
            <nav class="hidden md:flex items-center gap-6 text-xs font-semibold text-[#f0ebe0]/75">
                <a href="index.php"
                    class="hover:text-emas transition-colors flex items-center gap-1.5 <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'text-emas border-b border-emas pb-0.5' : ''; ?>">
                    <i data-lucide="home" class="w-3.5 h-3.5"></i> Beranda
                </a>
                <a href="search.php"
                    class="hover:text-emas transition-colors flex items-center gap-1.5 <?php echo (basename($_SERVER['PHP_SELF']) == 'search.php') ? 'text-emas border-b border-emas pb-0.5' : ''; ?>">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i> Cari Buku
                </a>
                <a href="cart.php"
                    class="hover:text-emas transition-colors flex items-center gap-1.5 <?php echo (basename($_SERVER['PHP_SELF']) == 'cart.php') ? 'text-emas border-b border-emas pb-0.5' : ''; ?>">
                    <i data-lucide="shopping-cart" class="w-3.5 h-3.5"></i> Keranjang
                    <?php
                    $cart_count_header = isset($_SESSION['keranjang']) ? array_sum($_SESSION['keranjang']) : 0;
                    if ($cart_count_header > 0):
                        ?>
                        <span
                            class="px-1.5 py-0.5 rounded-full bg-emas text-darkBg text-[8px] font-bold"><?php echo $cart_count_header; ?></span>
                    <?php endif; ?>
                </a>
                <a href="orders.php"
                    class="hover:text-emas transition-colors flex items-center gap-1.5 <?php echo (basename($_SERVER['PHP_SELF']) == 'orders.php') ? 'text-emas border-b border-emas pb-0.5' : ''; ?>">
                    <i data-lucide="package" class="w-3.5 h-3.5"></i> Pesanan Saya
                </a>
            </nav>
        <?php endif; ?>

        <div class="flex items-center gap-3">
            <button onclick="openContactModal()"
                class="text-xs px-3 py-1.5 rounded-xl transition-all bg-[rgba(212,168,83,0.1)] text-emas border border-[rgba(212,168,83,0.2)] hover:bg-[rgba(212,168,83,0.2)] flex items-center gap-1.5">
                <i data-lucide="mail" class="w-3.5 h-3.5"></i> Kontak
            </button>

            <?php if (isset($_SESSION['id_user'])): ?>
                <!-- Logged in state -->
                <div class="flex items-center gap-2 pl-3 border-l border-white/10">
                    <a href="profile.php"
                        class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold font-serif bg-gradient-to-r from-emas-hover to-emas text-darkBg">
                        <?php echo strtoupper(substr($_SESSION['nama'], 0, 1)); ?>
                    </a>
                    <a href="profile.php"
                        class="text-xs text-[#f0ebe0]/60 hover:text-emas transition-colors hidden sm:inline">
                        <?php
                        $first_name = explode(' ', $_SESSION['nama'])[0];
                        echo htmlspecialchars($first_name);
                        ?>
                    </a>
                </div>
            <?php else: ?>
                <!-- Guest state -->
                <div class="flex items-center gap-2 pl-2 border-l border-white/10">
                    <a href="login.php"
                        class="text-xs font-medium text-[#f0ebe0]/70 hover:text-emas transition-colors">Masuk</a>
                    <a href="register.php"
                        class="text-xs px-4 py-2 rounded-xl btn-gold text-darkBg font-semibold">Daftar</a>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <?php if ($is_guest_landing): ?>
        <script>
            // Scroll handler for Landing Page
            window.addEventListener('scroll', function () {
                const header = document.querySelector('header');
                if (window.scrollY > 40) {
                    header.classList.add('scrolled');
                    header.style.borderBottom = '1px solid rgba(212, 168, 83, 0.15)';
                } else {
                    header.classList.remove('scrolled');
                    header.style.borderBottom = '1px solid transparent';
                }
            });
        </script>
    <?php endif; ?>

    <?php
    include 'book_modal.php';
    ?>
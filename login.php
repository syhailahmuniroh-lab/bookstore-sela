<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php';

// If user is already logged in, send them to home or admin dashboard
if (isset($_SESSION['id_user'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/index.php");
    } else {
        header("Location: index.php");
    }
    exit;
}

if (isset($_POST['masuk'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];

            if ($user['role'] == 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak terdaftar!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Cell’s Bookshelf</title>
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet">
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
    <!-- Custom theme styles -->
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-darkBg text-[#f0ebe0] font-sans min-h-screen flex flex-col items-center justify-center py-12 px-4">

    <div
        class="w-full max-w-md rounded-3xl p-8 md:p-10 bg-darkPanel border border-[rgba(212,168,83,0.15)] shadow-2xl relative overflow-hidden animate-fade-up">
        <!-- Back to Home Button -->
        <a href="index.php"
            class="absolute left-6 top-6 w-8 h-8 rounded-xl flex items-center justify-center bg-white/[0.04] border border-white/[0.08] hover:bg-emas/10 hover:border-emas/30 text-[#f0ebe0]/50 hover:text-emas transition-all"
            title="Kembali ke Beranda">
            ←
        </a>

        <!-- Decoration blur -->
        <div class="absolute -right-16 -top-16 w-36 h-36 rounded-full bg-emas/5 blur-2xl"></div>

        <h4 class="font-serif font-bold text-2xl text-center text-white mt-4 mb-1">Selamat Datang</h4>
        <p class="text-xs text-[#f0ebe0]/40 text-center mb-6">Masuk ke akun Cell’s Bookshelf Anda</p>

        <?php if (isset($error)): ?>
            <div class="bg-[#ef4444]/10 text-[#f87171] border border-[#ef4444]/20 text-center p-3 rounded-xl mb-4 text-xs">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Form login -->
        <form method="POST" action="" class="space-y-4">
            <div>
                <label class="block text-[10px] uppercase tracking-widest font-semibold text-emas mb-2">Email</label>
                <input type="email" name="email" required placeholder="email@contoh.com"
                    class="w-full rounded-xl px-4 py-3 text-xs outline-none bg-white/[0.04] border border-white/[0.08] text-white focus:border-emas transition-all">
            </div>

            <div>
                <label class="block text-[10px] uppercase tracking-widest font-semibold text-emas mb-2">Password</label>
                <input type="password" name="password" required placeholder="********"
                    class="w-full rounded-xl px-4 py-3 text-xs outline-none bg-white/[0.04] border border-white/[0.08] text-white focus:border-emas transition-all">
            </div>

            <button type="submit" name="masuk"
                class="w-full py-3.5 rounded-xl font-bold text-xs btn-gold text-darkBg shadow-lg pt-2 mt-4">
                Masuk
            </button>
        </form>

        <div class="text-center mt-6">
            <p class="text-xs text-[#f0ebe0]/40">
                Belum punya akun? <a href="register.php" class="text-emas hover:underline">Daftar sekarang</a>
            </p>
            <p class="text-[9px] text-[#f0ebe0]/30 mt-4 leading-relaxed">
                Hint Budi: budi@email.com / user123<br>
                Hint Admin: admin@bookstore.com / admin123
            </p>
        </div>
    </div>

</body>

</html>
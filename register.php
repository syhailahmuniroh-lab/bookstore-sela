<?php
include 'koneksi.php';

if (isset($_POST['daftar'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Email sudah digunakan! Silakan gunakan email lain.";
    } else {
        $query = "INSERT INTO users (nama, email, no_telp, password, role) VALUES ('$nama', '$email', '$no_telp', '$password', 'user')";
        $hasil = mysqli_query($conn, $query);
        if ($hasil) {
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
                        title: 'Pendaftaran Berhasil',
                        text: 'Pendaftaran Berhasil! Silakan Masuk.',
                        icon: 'success',
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
        } else {
            $error = "Gagal mendaftar, terjadi kesalahan sistem.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Cell’s Bookshelf</title>
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
    <!-- Custom theme styles -->
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-darkBg text-[#f0ebe0] font-sans min-h-screen flex flex-col items-center justify-center py-12 px-4">

    <div class="w-full max-w-md rounded-3xl p-8 md:p-10 bg-darkPanel border border-[rgba(212,168,83,0.15)] shadow-2xl relative overflow-hidden animate-fade-up">
        <!-- Back to Home Button -->
        <a href="index.php" class="absolute left-6 top-6 w-8 h-8 rounded-xl flex items-center justify-center bg-white/[0.04] border border-white/[0.08] hover:bg-emas/10 hover:border-emas/30 text-[#f0ebe0]/50 hover:text-emas transition-all" title="Kembali ke Beranda">
            ←
        </a>
        
        <!-- Decoration blur -->
        <div class="absolute -right-16 -top-16 w-36 h-36 rounded-full bg-emas/5 blur-2xl"></div>
        
        <h4 class="font-serif font-bold text-2xl text-center text-white mt-4 mb-1">Pendaftaran Anggota</h4>
        <p class="text-xs text-[#f0ebe0]/40 text-center mb-6">Bergabunglah untuk mengoleksi pustaka terbaik</p>

        <?php if(isset($error)): ?>
            <div class="bg-[#ef4444]/10 text-[#f87171] border border-[#ef4444]/20 text-center p-3 rounded-xl mb-4 text-xs">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Form register -->
        <form method="POST" action="" class="space-y-4">
            <div>
                <label class="block text-[10px] uppercase tracking-widest font-semibold text-emas mb-2">Nama Lengkap</label>
                <input type="text" name="nama" required placeholder="Nama Anda"
                       class="w-full rounded-xl px-4 py-3 text-xs outline-none bg-white/[0.04] border border-white/[0.08] text-white focus:border-emas transition-all">
            </div>

            <div>
                <label class="block text-[10px] uppercase tracking-widest font-semibold text-emas mb-2">Email</label>
                <input type="email" name="email" required placeholder="email@contoh.com"
                       class="w-full rounded-xl px-4 py-3 text-xs outline-none bg-white/[0.04] border border-white/[0.08] text-white focus:border-emas transition-all">
            </div>

            <div>
                <label class="block text-[10px] uppercase tracking-widest font-semibold text-emas mb-2">Nomor Telepon</label>
                <input type="tel" name="no_telp" required placeholder="08123456789"
                       class="w-full rounded-xl px-4 py-3 text-xs outline-none bg-white/[0.04] border border-white/[0.08] text-white focus:border-emas transition-all">
            </div>
            
            <div>
                <label class="block text-[10px] uppercase tracking-widest font-semibold text-emas mb-2">Password</label>
                <input type="password" name="password" required placeholder="********"
                       class="w-full rounded-xl px-4 py-3 text-xs outline-none bg-white/[0.04] border border-white/[0.08] text-white focus:border-emas transition-all">
            </div>
            
            <button type="submit" name="daftar" 
                    class="w-full py-3.5 rounded-xl font-bold text-xs btn-gold text-darkBg shadow-lg pt-2 mt-4">
                Daftar Sekarang
            </button>
        </form>

        <div class="text-center mt-6">
            <p class="text-xs text-[#f0ebe0]/40">
                Sudah punya akun? <a href="login.php" class="text-emas hover:underline">Masuk</a>
            </p>
        </div>
    </div>

</body>
</html>
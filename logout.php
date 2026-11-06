<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION = [];
session_unset();
session_destroy();
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
            title: 'Berhasil Keluar',
            text: 'Anda telah keluar dari akun dengan aman.',
            icon: 'success',
            background: '#14112a',
            color: '#f0ebe0',
            confirmButtonColor: '#d4a853',
            width: '90%',
            customClass: {
                popup: 'border border-[rgba(212,168,83,0.2)] rounded-3xl font-sans'
            }
        }).then(function() {
            window.location = 'index.php';
        });
    });
    </script>
</body>
</html>
";
?>

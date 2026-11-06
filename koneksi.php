<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_bookstore";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal, coba cek lagi XAMPP-nya ya: " . mysqli_connect_error());
}
?>
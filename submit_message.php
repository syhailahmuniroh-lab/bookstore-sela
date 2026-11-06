<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php';

if (isset($_POST['message']) && isset($_SESSION['id_user'])) {
    $id_user = intval($_SESSION['id_user']);
    $message = trim(mysqli_real_escape_string($conn, $_POST['message']));
    
    if (empty($message)) {
        echo "empty";
        exit;
    }

    $q = "INSERT INTO messages (id_user, isi_pesan) VALUES ($id_user, '$message')";
    if (mysqli_query($conn, $q)) {
        echo "success";
    } else {
        error_log("Database error in submit_message.php: " . mysqli_error($conn));
        echo "error";
    }
} else {
    echo "unauthorized";
}
?>

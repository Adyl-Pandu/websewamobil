<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_sewa_mobil";

$conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi (cara procedural)
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>

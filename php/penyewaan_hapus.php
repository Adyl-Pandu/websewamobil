<?php
include 'koneksi.php';

$id = $_GET['id'];

$query = "DELETE FROM penyewaan WHERE id = $id";
mysqli_query($conn, $query);

header("Location: ../daftar_transaksi.php");
?>

<?php
include 'php/koneksi.php';

$id = $_GET['id'];

// Cek apakah pelanggan digunakan di tabel penyewaan
$check = mysqli_query($conn, "SELECT * FROM penyewaan WHERE id_pelanggan = $id");

if (mysqli_num_rows($check) > 0) {
    echo "<script>
        alert('Pelanggan tidak bisa dihapus karena masih ada data penyewaan terkait.');
        window.location.href = 'daftar_pelanggan.php';
    </script>";
    exit;
}

// Jika aman, hapus
$query = "DELETE FROM pelanggan WHERE id_pelanggan = $id";
mysqli_query($conn, $query);

header("Location: pelanggan-add.php");
exit;
?>

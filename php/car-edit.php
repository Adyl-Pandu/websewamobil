<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $merek = mysqli_real_escape_string($conn, $_POST['merek']);
    $model = mysqli_real_escape_string($conn, $_POST['model']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $plat_nomor = mysqli_real_escape_string($conn, $_POST['plat_nomor']);
    $tahun = intval($_POST['tahun']);
    $warna = mysqli_real_escape_string($conn, $_POST['warna']);
    $harga_sewa = intval($_POST['harga_sewa']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $query = "UPDATE mobil SET 
                merek='$merek', 
                model='$model', 
                kategori='$kategori',
                plat_nomor='$plat_nomor',
                tahun=$tahun,
                warna='$warna',
                harga_sewa=$harga_sewa,
                status='$status'
              WHERE id=$id";

    if (mysqli_query($conn, $query)) {
        header("Location: ../car-list.php");
    } else {
        echo "Gagal memperbarui data: " . mysqli_error($conn);
    }
} else {
    echo "Metode tidak diizinkan.";
}
?>

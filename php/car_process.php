<?php
session_start();
require 'Koneksi.php';

// Ambil data dari form
$merek = $_POST['merek'] ?? '';
$model = $_POST['model'] ?? '';
$plat_nomor = $_POST['plat_nomor'] ?? '';
$tahun = $_POST['tahun'] ?? '';
$warna = $_POST['warna'] ?? '';
$kategori = $_POST['kategori'] ?? '';
$harga_sewa = $_POST['harga_sewa'] ?? '';
$status = $_POST['status'] ?? '';
$deskripsi = $_POST['deskripsi'] ?? '';

// Validasi field wajib
if (
    empty($merek) || empty($model) || empty($plat_nomor) || empty($tahun) ||
    empty($warna) || empty($kategori) || empty($harga_sewa) || empty($status)
) {
    echo "<script>alert('Harap lengkapi semua field yang wajib diisi.'); window.location.href='../car-add.php';</script>";
    exit;
}

// Simpan ke database tanpa foto
$stmt = $conn->prepare("INSERT INTO mobil (merek, model, plat_nomor, tahun, warna, kategori, harga_sewa, status, deskripsi) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssiss", $merek, $model, $plat_nomor, $tahun, $warna, $kategori, $harga_sewa, $status, $deskripsi);

if ($stmt->execute()) {
    echo "<script>alert('Data mobil berhasil ditambahkan!'); window.location.href='../car-list.php';</script>";
} else {
    echo "<script>alert('Terjadi kesalahan saat menyimpan data.'); window.location.href='../car-add.php';</script>";
}

$stmt->close();
$conn->close();
?>

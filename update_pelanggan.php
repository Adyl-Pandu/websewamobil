<?php
include 'php/koneksi.php';

$id = $_POST['id_pelanggan'];
$nama = $_POST['nama'];
$no_ktp = $_POST['no_ktp'];
$alamat = $_POST['alamat'];
$no_telepon = $_POST['no_telepon'];
$email = $_POST['email'];
$catatan = $_POST['catatan'];

$query = "UPDATE pelanggan SET 
    nama='$nama',
    no_ktp='$no_ktp',
    alamat='$alamat',
    no_telepon='$no_telepon',
    email='$email',
    catatan='$catatan'
    WHERE id_pelanggan = $id";

mysqli_query($conn, $query);

header("Location: daftar_pelanggan.php"); // kembali ke halaman utama
exit;

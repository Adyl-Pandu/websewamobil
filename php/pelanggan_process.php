<?php
include 'koneksi.php';

// Ambil data dari form dengan trim untuk menghilangkan spasi
$nama = trim($_POST['nama']);
$no_ktp = trim($_POST['no_ktp']);
$alamat = trim($_POST['alamat']);
$no_telepon = trim($_POST['no_telepon']);
$email = trim($_POST['email']);
$catatan = trim($_POST['catatan']);
$created_at = date('Y-m-d H:i:s');

// Validasi sederhana untuk field wajib
if (empty($nama) || empty($no_ktp) || empty($alamat)) {
    header("Location: ../pelanggan_add.php?status=error&message=" . urlencode("Field wajib tidak boleh kosong."));
    exit;
}

// Cek apakah no_ktp sudah ada di database
$cek_query = "SELECT COUNT(*) FROM pelanggan WHERE no_ktp = ?";
$cek_stmt = mysqli_prepare($conn, $cek_query);
mysqli_stmt_bind_param($cek_stmt, "s", $no_ktp);
mysqli_stmt_execute($cek_stmt);
mysqli_stmt_bind_result($cek_stmt, $jumlah);
mysqli_stmt_fetch($cek_stmt);
mysqli_stmt_close($cek_stmt);

if ($jumlah > 0) {
    // Redirect jika no_ktp sudah digunakan
    header("Location: ../pelanggan_add.php?status=error&message=" . urlencode("No KTP sudah terdaftar."));
    exit;
}

// Insert data ke database
$query = "INSERT INTO pelanggan (nama, no_ktp, alamat, no_telepon, email, catatan, created_at) 
          VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "sssssss", $nama, $no_ktp, $alamat, $no_telepon, $email, $catatan, $created_at);

if (mysqli_stmt_execute($stmt)) {
    // Redirect ke form tambah dengan status sukses
    header("Location: ../penyewaan_list.php?status=success");
} else {
    // Jika gagal, tampilkan error
    $error = mysqli_error($conn);
    header("Location: ../pelanggan_add.php?status=error&message=" . urlencode($error));
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

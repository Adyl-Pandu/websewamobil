<?php
session_start();
include 'koneksi.php';

// Aktifkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validasi dan sanitasi input
$id_penyewaan = isset($_POST['id_penyewaan']) ? (int)$_POST['id_penyewaan'] : 0;
if ($id_penyewaan <= 0) {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'ID penyewaan tidak valid.';
    header('Location: ../rental-list.php'); // Sesuaikan dengan nama file yang benar
    exit;
}

$tanggal_mulai = isset($_POST['tanggal_mulai']) ? mysqli_real_escape_string($conn, $_POST['tanggal_mulai']) : '';
$tanggal_selesai = isset($_POST['tanggal_selesai']) ? mysqli_real_escape_string($conn, $_POST['tanggal_selesai']) : '';
$id_mobil = isset($_POST['id_mobil']) ? (int)$_POST['id_mobil'] : 0;
$status_pembayaran = isset($_POST['status_pembayaran']) ? mysqli_real_escape_string($conn, $_POST['status_pembayaran']) : '';

// Validasi tanggal
if (!$tanggal_mulai || !$tanggal_selesai || !strtotime($tanggal_mulai) || !strtotime($tanggal_selesai)) {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Tanggal tidak valid.';
    header('Location: penyewaan_edit.php?id=' . $id_penyewaan);
    exit;
}

// Hitung durasi
$start = new DateTime($tanggal_mulai);
$end = new DateTime($tanggal_selesai);
$durasi = ($end->diff($start)->days) + 1;
if ($durasi <= 0) {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Durasi tidak valid.';
    header('Location: penyewaan_edit.php?id=' . $id_penyewaan);
    exit;
}

// Ambil harga_sewa dari mobil
$query_harga = "SELECT harga_sewa FROM mobil WHERE id = ?";
$stmt_harga = mysqli_prepare($conn, $query_harga);
mysqli_stmt_bind_param($stmt_harga, 'i', $id_mobil);
mysqli_stmt_execute($stmt_harga);
$result_harga = mysqli_stmt_get_result($stmt_harga);
$harga_sewa = mysqli_fetch_assoc($result_harga)['harga_sewa'] ?? 0;
$total_biaya = $harga_sewa * $durasi;

// Update data
$query_update = "UPDATE penyewaan SET id_mobil = ?, tanggal_mulai = ?, durasi = ?, total_biaya = ?, status_pembayaran = ? WHERE id = ?";
$stmt_update = mysqli_prepare($conn, $query_update);
mysqli_stmt_bind_param($stmt_update, 'issdsi', $id_mobil, $tanggal_mulai, $durasi, $total_biaya, $status_pembayaran, $id_penyewaan);

if (mysqli_stmt_execute($stmt_update)) {
    $_SESSION['status'] = 'success';
    $_SESSION['message'] = 'Penyewaan berhasil diperbarui.';
} else {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Gagal memperbarui penyewaan: ' . mysqli_error($conn);
}

header('Location: ../rental-list.php'); // Sesuaikan dengan nama file yang benar
exit;

mysqli_stmt_close($stmt_update);
mysqli_stmt_close($stmt_harga);
mysqli_close($conn);
?>
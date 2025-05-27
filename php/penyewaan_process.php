<?php
session_start();
include 'koneksi.php';

// Cek CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header("Location: ../penyewaan_tambah.php?status=invalid&message=Token tidak valid");
    exit();
}
unset($_SESSION['csrf_token']); // Hapus token setelah digunakan

// Ambil dan validasi data input
$id_pelanggan = intval($_POST['id_pelanggan']);
$id_mobil = intval($_POST['id_mobil']);
$tanggal_mulai = trim($_POST['tanggal_mulai']);
$durasi = intval($_POST['durasi']);
$harga_sewa = floatval($_POST['harga_sewa']);
$total_biaya = floatval($_POST['total_biaya']);
$status_pembayaran = trim($_POST['status_pembayaran']);
$catatan = isset($_POST['catatan']) ? trim($_POST['catatan']) : '';

// Validasi input
if (
    $id_pelanggan <= 0 || $id_mobil <= 0 || $durasi <= 0 ||
    $harga_sewa <= 0 || $total_biaya <= 0 ||
    !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_mulai) ||
    strtotime($tanggal_mulai) === false ||
    !in_array($status_pembayaran, ['Belum Dibayar', 'Dibayar Sebagian', 'Lunas'])
) {
    header("Location: ../penyewaan_tambah.php?status=invalid&message=Data tidak valid");
    exit();
}

// Cek apakah id_mobil tersedia
$cekMobil = mysqli_prepare($conn, "SELECT COUNT(*) FROM mobil WHERE id_mobil = ?");
mysqli_stmt_bind_param($cekMobil, "i", $id_mobil);
mysqli_stmt_execute($cekMobil);
mysqli_stmt_bind_result($cekMobil, $count);
mysqli_stmt_fetch($cekMobil);
mysqli_stmt_close($cekMobil);

if ($count == 0) {
    header("Location: ../penyewaan_tambah.php?status=error&message=" . urlencode("Mobil tidak ditemukan."));
    exit();
}

// Hitung tanggal selesai
$tanggal_selesai = date('Y-m-d', strtotime($tanggal_mulai . ' + ' . ($durasi - 1) . ' days'));

// Mulai transaksi
mysqli_begin_transaction($conn);

try {
    // Insert ke tabel penyewaan
    $query = "INSERT INTO penyewaan 
        (id_pelanggan, id_mobil, tanggal_mulai, tanggal_selesai, durasi, harga_sewa, total_biaya, status_pembayaran, catatan)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        throw new Exception("Gagal prepare statement: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param(
        $stmt,
        'iissiddss',
        $id_pelanggan,
        $id_mobil,
        $tanggal_mulai,
        $tanggal_selesai,
        $durasi,
        $harga_sewa,
        $total_biaya,
        $status_pembayaran,
        $catatan
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Gagal insert data penyewaan: " . mysqli_stmt_error($stmt));
    }
    mysqli_stmt_close($stmt);

    // Update status mobil
    // Update status mobil
    $update_query = "UPDATE mobil SET status = 'Disewa' WHERE id_mobil = ?";
    $update_stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($update_stmt, "i", $id_mobil);

    if (!mysqli_stmt_execute($update_stmt)) {
        throw new Exception("Gagal update status mobil: " . mysqli_stmt_error($update_stmt));
    }
    mysqli_stmt_close($update_stmt);

    // Commit transaksi
    mysqli_commit($conn);
    header("Location: ../penyewaan_list.php?status=success");
    exit();

} catch (Exception $e) {
    mysqli_rollback($conn); // Batalkan semua query jika ada error
    error_log("Error: " . $e->getMessage());
    header("Location: ../penyewaan_tambah.php?status=error&message=" . urlencode($e->getMessage()));
    exit();
}

mysqli_close($conn);
?>
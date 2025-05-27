<?php
session_start();
include 'koneksi.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Anda harus login terlebih dahulu";
    header('Location: ../login.php');
    exit;
}

// Validasi CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = "Token keamanan tidak valid";
    header('Location: ../car-list.php?hapus=gagal');
    exit;
}

// Validasi ID
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    $_SESSION['error'] = "ID mobil tidak valid";
    header('Location: ../car-list.php?hapus=gagal');
    exit;
}

$id = (int)$_POST['id'];

// Proses penghapusan
try {
    // Cek apakah mobil ada
    $check = $conn->prepare("SELECT id_mobil FROM mobil WHERE id_mobil = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $check->store_result();
    
    if ($check->num_rows === 0) {
        $_SESSION['error'] = "Mobil tidak ditemukan";
        header('Location: ../car-list.php?hapus=gagal');
        exit;
    }
    
    // Hapus data
    $stmt = $conn->prepare("DELETE FROM mobil WHERE id_mobil = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header('Location: ../car-list.php?hapus=berhasil');
    } else {
        throw new Exception("Gagal eksekusi query");
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header('Location: ../car-list.php?hapus=gagal');
}

$stmt->close();
$conn->close();
?>
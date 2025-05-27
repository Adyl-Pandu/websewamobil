<?php
session_start();
require 'Koneksi.php';

// Cek apakah ID mobil dikirim melalui URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Cek apakah ID valid
    if (is_numeric($id)) {
        // Query untuk menghapus mobil berdasarkan ID
        $query = "DELETE FROM mobil WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "<script>alert('Mobil berhasil dihapus!'); window.location.href='../car-list.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan saat menghapus mobil.'); window.location.href='../car-list.php';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('ID tidak valid.'); window.location.href='../car-list.php';</script>";
    }
} else {
    echo "<script>alert('ID mobil tidak ditemukan.'); window.location.href='../car-list.php';</script>";
}

// Tutup koneksi
$conn->close();
?>

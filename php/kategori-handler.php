<?php
include 'koneksi.php';

// Aktifkan error reporting untuk debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';

if ($aksi == 'tambah') {
    $nama_kategori = $_POST['nama_kategori'];
    $deskripsi = $_POST['deskripsi'];

    $stmt = $conn->prepare("INSERT INTO kategori_mobil (nama_kategori, deskripsi) VALUES (?, ?)");
    $stmt->bind_param("ss", $nama_kategori, $deskripsi);
    if ($stmt->execute()) {
        header("Location: ../car-kategori.php?status=success");
    } else {
        $error_message = urlencode($stmt->error);
        header("Location: ../car-kategori.php?status=error&message=$error_message");
    }
    $stmt->close();
} elseif ($aksi == 'edit') {
    $id = $_POST['id'];
    $nama_kategori = $_POST['nama_kategori'];
    $deskripsi = $_POST['deskripsi'];

    $stmt = $conn->prepare("UPDATE kategori_mobil SET nama_kategori = ?, deskripsi = ? WHERE id = ?");
    $stmt->bind_param("ssi", $nama_kategori, $deskripsi, $id);
    if ($stmt->execute()) {
        header("Location: ../car-kategori.php?status=success");
    } else {
        $error_message = urlencode($stmt->error);
        header("Location: ../car-kategori.php?status=error&message=$error_message");
    }
    $stmt->close();
} elseif ($aksi == 'hapus') {
    $id = isset($_GET['id']) ? $_GET['id'] : '';

    if (!empty($id) && is_numeric($id)) {
        // Catat ke log untuk debugging
        error_log("Mencoba menghapus kategori dengan ID: $id");

        // Ambil nama_kategori untuk memperbarui tabel mobil
        $stmt = $conn->prepare("SELECT nama_kategori FROM kategori_mobil WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $nama_kategori = $row['nama_kategori'];
        $stmt->close();

        // Perbarui tabel mobil untuk menghindari pelanggaran kunci asing
        $stmt = $conn->prepare("UPDATE mobil SET kategori = NULL WHERE kategori = ?");
        $stmt->bind_param("s", $nama_kategori);
        if (!$stmt->execute()) {
            $error_message = urlencode("Gagal memperbarui tabel mobil: " . $stmt->error);
            header("Location: ../car-kategori.php?status=error&message=$error_message");
            exit;
        }
        $stmt->close();

        // Hapus kategori
        $stmt = $conn->prepare("DELETE FROM kategori_mobil WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            error_log("Hapus berhasil untuk ID: $id");
            header("Location: ../car-kategori.php?status=success");
        } else {
            $error_message = urlencode("Gagal menghapus kategori: " . $stmt->error);
            error_log("Hapus gagal: " . $stmt->error);
            header("Location: ../car-kategori.php?status=error&message=$error_message");
        }
        $stmt->close();
    } else {
        error_log("ID tidak valid: $id");
        header("Location: ../car-kategori.php?status=invalid");
    }
} else {
    header("Location: ../car-kategori.php?status=invalid");
}

$conn->close();
?>
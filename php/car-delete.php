<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM mobil WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header("Location: ../car-list.php"); // ganti dengan nama file halaman daftar mobil
    } else {
        echo "Gagal menghapus data.";
    }
} else {
    echo "ID tidak ditemukan.";
}
?>

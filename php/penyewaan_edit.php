<?php
include 'koneksi.php';

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM penyewaan WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (isset($_POST['submit'])) {
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $status_pembayaran = $_POST['status_pembayaran'];
    $catatan = $_POST['catatan'];

    $update = "UPDATE penyewaan SET 
                tanggal_mulai = '$tanggal_mulai', 
                tanggal_selesai = '$tanggal_selesai', 
                status_pembayaran = '$status_pembayaran',
                catatan = '$catatan'
               WHERE id = $id";

    mysqli_query($conn, $update);
    header("Location: ../payment-list.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Penyewaan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Style -->
    <style>
        body {
            background: #f1f4f7;
            font-family: 'Segoe UI', sans-serif;
        }

        .form-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }

        .form-container h4 {
            color: #2a5dba;
            font-weight: 600;
            margin-bottom: 25px;
        }

        .btn-primary {
            background-color: #2a5dba;
            border: none;
        }

        .btn-primary:hover {
            background-color: #244fa3;
        }

        .btn-outline-secondary:hover {
            background-color: #e2e6ea;
        }

        .form-label {
            font-weight: 500;
        }

        textarea {
            resize: none;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <h4 class="text-center">Edit Data Penyewaan</h4>
        <form method="post">
            <div class="mb-3">
                <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="<?= $data['tanggal_mulai'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="<?= $data['tanggal_selesai'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="status_pembayaran" class="form-label">Status Pembayaran</label>
                <select class="form-select" id="status_pembayaran" name="status_pembayaran" required>
                    <option value="Belum Dibayar" <?= $data['status_pembayaran'] == 'Belum Dibayar' ? 'selected' : '' ?>>Belum Dibayar</option>
                    <option value="Dibayar Sebagian" <?= $data['status_pembayaran'] == 'Dibayar Sebagian' ? 'selected' : '' ?>>Dibayar Sebagian</option>
                    <option value="Lunas" <?= $data['status_pembayaran'] == 'Lunas' ? 'selected' : '' ?>>Lunas</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="catatan" class="form-label">Catatan</label>
                <textarea class="form-control" id="catatan" name="catatan" rows="3"><?= $data['catatan'] ?></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="daftar_transaksi.php" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" name="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap JS (Opsional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

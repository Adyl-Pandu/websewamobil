<?php
include 'php/koneksi.php';

$id = $_GET['id'];
$query = "SELECT * FROM pelanggan WHERE id_pelanggan = $id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pelanggan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg rounded">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Edit Data Pelanggan</h4>
        </div>
        <div class="card-body">
            <form action="pelanggan-add.php" method="post">
                <input type="hidden" name="id_pelanggan" value="<?= $row['id_pelanggan'] ?>">

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($row['nama']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">No KTP</label>
                    <input type="text" name="no_ktp" class="form-control" value="<?= htmlspecialchars($row['no_ktp']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3"><?= htmlspecialchars($row['alamat']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">No Telepon</label>
                    <input type="text" name="no_telepon" class="form-control" value="<?= htmlspecialchars($row['no_telepon']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($row['email']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Catatan</label>
                    <textarea name="catatan" class="form-control" rows="3"><?= htmlspecialchars($row['catatan']) ?></textarea>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success">💾 Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>

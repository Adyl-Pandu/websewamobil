<?php
include 'php/koneksi.php';

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan.";
    exit;
}

$id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM mobil WHERE id = $id");

if (mysqli_num_rows($result) === 0) {
    echo "Data mobil tidak ditemukan.";
    exit;
}

$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mobil - RentCarPro</title>

    <link rel="stylesheet" href="vendors/styles/core.css">
    <link rel="stylesheet" href="vendors/styles/style.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <?php include 'menu_kiri.php'; ?>

    <div class="main-container">
        <div class="pd-ltr-20">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Edit Data Mobil</h4>
                    <p class="mb-0">Perbarui informasi mobil yang terdaftar dalam sistem.</p>
                </div>
                <div class="pb-20 p-3">
                    <form action="php/car-update.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

                        <div class="form-group">
                            <label>Merek</label>
                            <input type="text" name="merek" class="form-control" value="<?php echo $data['merek']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Model</label>
                            <input type="text" name="model" class="form-control" value="<?php echo $data['model']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Kategori</label>
                            <input type="text" name="kategori" class="form-control" value="<?php echo $data['kategori']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Plat Nomor</label>
                            <input type="text" name="plat_nomor" class="form-control" value="<?php echo $data['plat_nomor']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Tahun</label>
                            <input type="number" name="tahun" class="form-control" value="<?php echo $data['tahun']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Warna</label>
                            <input type="text" name="warna" class="form-control" value="<?php echo $data['warna']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Harga Sewa</label>
                            <input type="number" name="harga_sewa" class="form-control" value="<?php echo $data['harga_sewa']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="Available" <?php if ($data['status'] === 'Available') echo 'selected'; ?>>Available</option>
                                <option value="Rented" <?php if ($data['status'] === 'Rented') echo 'selected'; ?>>Rented</option>
                                <option value="Maintenance" <?php if ($data['status'] === 'Maintenance') echo 'selected'; ?>>Maintenance</option>
                            </select>
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="car-list.php" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="footer-wrap pd-20 mb-20 card-box">
                RentCarPro - Sistem Manajemen Penyewaan Mobil &copy; 2023
            </div>
        </div>
    </div>

    <script src="vendors/scripts/core.js"></script>
</body>

</html>

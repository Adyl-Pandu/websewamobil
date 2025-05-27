<?php
session_start();
include 'koneksi.php';

// Buat CSRF token jika belum ada
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Ambil ID mobil dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data mobil dari database
$sql = "SELECT * FROM mobil WHERE id_mobil = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$mobil = $result->fetch_assoc();

if (!$mobil) {
    $_SESSION['error'] = "Data mobil tidak ditemukan.";
    header("Location: ../car-list.php");
    exit;
}

// Proses update saat form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cek token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error'] = "Token CSRF tidak valid.";
        header("Location: mobil_edit.php?id=$id");
        exit;
    }

    // Validasi status
    $allowed_status = ['Tersedia', 'Disewa', 'Perawatan'];
    if (!in_array($_POST['status'], $allowed_status)) {
        $_SESSION['error'] = "Status tidak valid. Pilih antara: " . implode(', ', $allowed_status);
        header("Location: mobil_edit.php?id=$id");
        exit;
    }

    // Ambil dan sanitasi data
    $merek = trim($_POST['merek']);
    $model = trim($_POST['model']);
    $plat_nomor = trim($_POST['plat_nomor']);
    $tahun = intval($_POST['tahun']);
    $warna = trim($_POST['warna']);
    $harga_sewa = intval($_POST['harga_sewa']);
    $status = $_POST['status'];

    // Siapkan dan jalankan query update
    $update_sql = "UPDATE mobil SET merek=?, model=?, plat_nomor=?, tahun=?, warna=?, harga_sewa=?, status=? WHERE id_mobil=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssssssi", $merek, $model, $plat_nomor, $tahun, $warna, $harga_sewa, $status, $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Data mobil berhasil diperbarui.";
        header("Location: ../car-list.php");
        exit;
    } else {
        $_SESSION['error'] = "Gagal update: " . $stmt->error;
        header("Location: mobil_edit.php?id=$id");
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mobil - RentCarPro</title>
    <style>
        /* Gaya seperti sebelumnya (CSS reset, form, button, responsive, dll.) */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f6fa;
        }

        .main-container {
            display: flex;
            min-height: 100vh;
        }

        .pd-ltr-20 {
            padding: 20px;
            width: 100%;
        }

        .card-box {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .text-blue {
            color: #3498db;
        }

        h4 {
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }

        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border 0.3s;
        }

        .form-control:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background-color: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-secondary {
            background-color: #95a5a6;
            color: white;
            margin-left: 10px;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .pd-ltr-20 {
                padding: 15px;
            }

            .btn {
                width: 100%;
                margin-bottom: 10px;
            }

            .btn-secondary {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="pd-ltr-20">
            <div class="card-box">
                <h4 class="text-blue h4">Edit Mobil</h4>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

                    <div class="form-group">
                        <label>Merek Mobil</label>
                        <input type="text" name="merek" class="form-control" value="<?= htmlspecialchars($mobil['merek']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Model</label>
                        <input type="text" name="model" class="form-control" value="<?= htmlspecialchars($mobil['model']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Plat Nomor</label>
                        <input type="text" name="plat_nomor" class="form-control" value="<?= htmlspecialchars($mobil['plat_nomor']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Tahun</label>
                        <input type="number" name="tahun" class="form-control" min="1900" max="<?= date('Y')+1; ?>" value="<?= htmlspecialchars($mobil['tahun']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Warna</label>
                        <input type="text" name="warna" class="form-control" value="<?= htmlspecialchars($mobil['warna']); ?>">
                    </div>

                    <div class="form-group">
                        <label>Harga Sewa per Hari (Rp)</label>
                        <input type="number" name="harga_sewa" class="form-control" min="100000" step="50000" value="<?= htmlspecialchars($mobil['harga_sewa']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="Tersedia" <?= $mobil['status'] === 'Tersedia' ? 'selected' : ''; ?>>Tersedia</option>
                            <option value="Disewa" <?= $mobil['status'] === 'Disewa' ? 'selected' : ''; ?>>Disewa</option>
                            <option value="Perawatan" <?= $mobil['status'] === 'Perawatan' ? 'selected' : ''; ?>>Perawatan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="../car-list.php" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>

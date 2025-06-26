<?php
session_start();
include 'koneksi.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Silakan login terlebih dahulu.";
    header("Location: login.php");
    exit;
}

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

    // Validasi input tambahan
    if (empty($merek) || empty($model) || empty($plat_nomor)) {
        $_SESSION['error'] = "Merek, model, dan plat nomor tidak boleh kosong.";
        header("Location: mobil_edit.php?id=$id");
        exit;
    }

    // Siapkan dan jalankan query update dengan transaksi
    $conn->begin_transaction();
    try {
        $update_sql = "UPDATE mobil SET merek=?, model=?, plat_nomor=?, tahun=?, warna=?, harga_sewa=?, status=? WHERE id_mobil=?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sssssssi", $merek, $model, $plat_nomor, $tahun, $warna, $harga_sewa, $status, $id);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
        $_SESSION['success'] = "Data mobil berhasil diperbarui.";
        header("Location: ../car-list.php");
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Gagal update: " . $e->getMessage();
        header("Location: mobil_edit.php?id=$id");
    }
    exit;
}

// Tambahkan header untuk mencegah cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mobil - RentCarPro</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f6fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .main-container {
            display: flex;
            width: 100%;
            justify-content: center;
        }

        .pd-ltr-20 {
            padding: 20px;
            max-width: 600px;
            width: 100%;
        }

        .card-box {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 15px;
            margin-bottom: 20px;
            width: 100%;
        }

        .text-blue {
            color: #3498db;
        }

        h4 {
            margin-bottom: 15px;
            font-size: 1.3rem;
            text-align: center;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.9rem;
            transition: border 0.3s;
        }

        .form-control:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }

        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
            text-align: center;
            width: 48%;
            margin: 0 1%;
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
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
        }

        .alert {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .button-group {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .pd-ltr-20 {
                padding: 15px;
            }

            .btn {
                width: 100%;
                margin-bottom: 10px;
            }

            .button-group {
                flex-direction: column;
                align-items: center;
            }

            .btn + .btn {
                margin-left: 0;
                margin-top: 10px;
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
                    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

                    <div class="form-group">
                        <label for="merek">Merek Mobil</label>
                        <input type="text" id="merek" name="merek" class="form-control" value="<?= htmlspecialchars($mobil['merek']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="model">Model</label>
                        <input type="text" id="model" name="model" class="form-control" value="<?= htmlspecialchars($mobil['model']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="plat_nomor">Plat Nomor</label>
                        <input type="text" id="plat_nomor" name="plat_nomor" class="form-control" value="<?= htmlspecialchars($mobil['plat_nomor']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="tahun">Tahun</label>
                        <input type="number" id="tahun" name="tahun" class="form-control" min="1900" max="<?= date('Y')+1; ?>" value="<?= htmlspecialchars($mobil['tahun']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="warna">Warna</label>
                        <input type="text" id="warna" name="warna" class="form-control" value="<?= htmlspecialchars($mobil['warna']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="harga_sewa">Harga Sewa per Hari (Rp)</label>
                        <input type="number" id="harga_sewa" name="harga_sewa" class="form-control" min="100000" step="50000" value="<?= htmlspecialchars($mobil['harga_sewa']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="Tersedia" <?= $mobil['status'] === 'Tersedia' ? 'selected' : ''; ?>>Tersedia</option>
                            <option value="Disewa" <?= $mobil['status'] === 'Disewa' ? 'selected' : ''; ?>>Disewa</option>
                            <option value="Perawatan" <?= $mobil['status'] === 'Perawatan' ? 'selected' : ''; ?>>Perawatan</option>
                        </select>
                    </div>

                    <div class="button-group">
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
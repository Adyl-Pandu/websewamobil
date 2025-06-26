<?php
// Aktifkan error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include 'php/koneksi.php';

// Cek apakah user sudah login
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])):
    header('Location: index.php');
    exit;
endif;

// Koneksi database
include 'php/koneksi.php';

// Handle notifikasi
$notifications = [];
if (isset($_GET['hapus']) && in_array($_GET['hapus'], ['berhasil', 'gagal'])) {
    $notifications[] = [
        'type' => $_GET['hapus'] === 'berhasil' ? 'success' : 'danger',
        'message' => $_GET['hapus'] === 'berhasil' 
            ? 'Data mobil berhasil dihapus' 
            : 'Gagal menghapus data mobil' . (isset($_SESSION['error']) ? ': ' . $_SESSION['error'] : '')
    ];
    if (isset($_SESSION['error'])) unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
    $notifications[] = ['type' => 'success', 'message' => $_SESSION['success']];
    unset($_SESSION['success']);
}

if (isset($_SESSION['info'])) {
    $notifications[] = ['type' => 'info', 'message' => $_SESSION['info']];
    unset($_SESSION['info']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mobil - RentCarPro</title>
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/car-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/logo_mobil_biru.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="vendors/styles/core.css">
    <link rel="stylesheet" href="vendors/styles/icon-font.min.css">
    <link rel="stylesheet" href="src/plugins/datatables/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="vendors/styles/style.css">
    <style>
        .search-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .badge {
            font-size: 90%;
            padding: 5px 10px;
            border-radius: 12px;
        }
        .badge-success { background-color: #28a745; color: #fff; }
        .badge-warning { background-color: #ffc107; color: #212529; }
        .badge-secondary { background-color: #6c757d; color: #fff; }
        .action-buttons .btn { margin-right: 5px; margin-bottom: 5px; }
        .dataTables_wrapper .row:first-child { margin-bottom: 15px; }
        .table-responsive {
            overflow-x: auto;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.data-table th, table.data-table td {
            vertical-align: middle;
            text-align: center;
            padding: 10px;
        }
        table.data-table th {
            background-color: #343a40;
            color: #fff;
        }
        table.data-table tbody tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'menu_kiri.php'; ?>

    <div class="main-container">
        <div class="pd-ltr-20">
            <!-- Notifikasi -->
            <?php foreach ($notifications as $notification): ?>
                <div class="alert alert-<?= $notification['type'] ?> alert-dismissible fade show mb-4" role="alert">
                    <?= $notification['message'] ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            <?php endforeach; ?>

            <!-- Header dan Tombol Tambah -->
            <div class="page-header mb-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4 class="mb-0">Daftar Mobil</h4>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="car-add.php" class="btn btn-primary">
                            <i class="icon-copy dw dw-add"></i> Tambah Mobil
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form Pencarian -->
            <div class="card-box mb-4">
                <div class="search-box">
                    <form method="GET" class="row g-2">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Cari merek/model/plat/tahun/warna" 
                                   value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-control">
                                <option value="">Semua Status</option>
                                <option value="Tersedia" <?= ($_GET['status'] ?? '') === 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                                <option value="Disewa" <?= ($_GET['status'] ?? '') === 'Disewa' ? 'selected' : '' ?>>Disewa</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Cari</button>
                        </div>
                        <div class="col-md-2">
                            <a href="car-list.php" class="btn btn-secondary w-100">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Data Mobil -->
            <div class="card-box">
                <div class="table-responsive">
                    <table class="data-table table table-striped table-hover nowrap">
                        <thead class="thead-dark">
                            <tr>
                                <th>Merek</th>
                                <th>Model</th>
                                <th>Plat</th>
                                <th>Tahun</th>
                                <th>Warna</th>
                                <th>Harga/Hari</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $status_class = [
                                'Tersedia' => 'badge-success',
                                'Disewa' => 'badge-warning',
                            ];

                            $search = $_GET['search'] ?? '';
                            $status_filter = $_GET['status'] ?? '';
                            $query = "SELECT * FROM mobil WHERE 1=1";
                            $params = [];

                            // Perluas pencarian untuk semua kolom yang relevan
                            if ($search) {
                                $query .= " AND (merek LIKE ? OR model LIKE ? OR plat_nomor LIKE ? OR tahun LIKE ? OR warna LIKE ?)";
                                $params = ["%$search%", "%$search%", "%$search%", "%$search%", "%$search%"];
                            }
                            if ($status_filter) {
                                $query .= " AND status = ?";
                                $params[] = $status_filter;
                            }

                            $stmt = $conn->prepare($query);
                            if ($params) {
                                $stmt->bind_param(str_repeat('s', count($params)), ...$params);
                            }
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0):
                                while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['merek']) ?></td>
                                        <td><?= htmlspecialchars($row['model']) ?></td>
                                        <td><?= htmlspecialchars($row['plat_nomor']) ?></td>
                                        <td><?= htmlspecialchars($row['tahun']) ?></td>
                                        <td><?= htmlspecialchars($row['warna']) ?></td>
                                        <td>Rp <?= number_format($row['harga_sewa'], 0, ',', '.') ?></td>
                                        <td>
                                            <span class="badge <?= $status_class[$row['status']] ?? 'badge-secondary' ?>">
                                                <?= htmlspecialchars($row['status']) ?>
                                            </span>
                                        </td>
                                        <td class="action-buttons">
                                            <a href="php/mobil_edit.php?id=<?= $row['id_mobil'] ?>" 
                                               class="btn btn-warning btn-sm">
                                               <i class="icon-copy dw dw-edit"></i> Edit
                                            </a>
                                            <form method="POST" action="php/mobil_delete.php" class="d-inline">
                                                <input type="hidden" name="id" value="<?= $row['id_mobil'] ?>">
                                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus mobil ini?')">
                                                    <i class="icon-copy dw dw-delete"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile;
                            else: ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4">Tidak ada data mobil ditemukan</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer-wrap pd-20 mt-5 card-box">
                <div class="text-center">
                    RentCarPro © <?= date('Y') ?> - Sistem Manajemen Penyewaan Mobil
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="vendors/scripts/core.js"></script>
    <script src="vendors/scripts/script.min.js"></script>
    <script src="src/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
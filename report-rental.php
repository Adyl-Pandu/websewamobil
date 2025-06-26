<?php
include 'php/koneksi.php';

// Cek apakah user sudah login
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])):
    header('Location: index.php');
    exit;
endif;
include 'header.php';
include 'menu_kiri.php';

// Inisialisasi
$no = 1;

// Filter tanggal
$start_date = isset($_GET['start_date']) ? mysqli_real_escape_string($conn, $_GET['start_date']) : '';
$end_date = isset($_GET['end_date']) ? mysqli_real_escape_string($conn, $_GET['end_date']) : '';

// Query data penyewaan
$sql = "SELECT p.*, pel.nama, m.model 
        FROM penyewaan p
        LEFT JOIN pelanggan pel ON p.id_pelanggan = pel.id_pelanggan
        LEFT JOIN mobil m ON p.id_mobil = m.id_mobil
        WHERE 1=1";

if (!empty($start_date)) {
    $sql .= " AND p.tanggal_mulai >= '$start_date'";
}
if (!empty($end_date)) {
    $sql .= " AND p.tanggal_selesai <= '$end_date'";
}

$sql .= " ORDER BY p.tanggal_mulai DESC";

$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Query error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>RentCarPro - Laporan Penyewaan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/car-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/logo_mobil_biru.png">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS Libraries -->
    <link rel="stylesheet" type="text/css" href="vendors/styles/core.css">
    <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css">
    <link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="vendors/styles/style.css">
</head>

<body>
    <div class="main-container">
        <div class="pd-ltr-20">
            <h4 class="text-blue mb-4 mt-4">Laporan Penyewaan</h4>

            <!-- Filter Tanggal -->
            <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <label for="start_date" class="form-label">Tanggal Mulai</label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="<?= htmlspecialchars($start_date) ?>">
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label">Tanggal Selesai</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="<?= htmlspecialchars($end_date) ?>">
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Tampilkan Laporan</button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <a href="phptopdf/report-rental-to-pdh.php" class="btn btn-success w-100">Cetak Laporan</a>
            </div>
        </form>


            <!-- Tabel Laporan Penyewaan -->
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Daftar Penyewaan</h4>
                </div>
                <div class="pb-20">
                    <table class="data-table table stripe hover nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Penyewa</th>
                                <th>Mobil</th>
                                <th>Tanggal Mulai</th>
                                <th>Harga Sewa</th>
                                <th>Durasi (hari)</th>
                                <th>Total Bayar</th>
                                <th>Status Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($row['nama'] ?? 'Tidak diketahui') ?></td>
                                        <td><?= htmlspecialchars($row['model'] ?? 'Tidak diketahui') ?></td>
                                        <td><?= htmlspecialchars($row['tanggal_mulai']) ?></td>
                                        <td>Rp <?= number_format($row['harga_sewa'], 0, ',', '.') ?></td>
                                        <td><?= htmlspecialchars($row['durasi']) ?></td>
                                        <td>Rp <?= number_format($row['harga_sewa'] * $row['durasi'], 0, ',', '.') ?></td>
                                        <td>
                                            <?php 
                                            switch ($row['status_pembayaran']) {
                                                case 'Belum Dibayar':
                                                    echo '<span class="badge bg-danger">Belum Dibayar</span>';
                                                    break;
                                                case 'Dibayar Sebagian':
                                                    echo '<span class="badge bg-warning">Dibayar Sebagian</span>';
                                                    break;
                                                case 'Lunas':
                                                    echo '<span class="badge bg-success">Lunas</span>';
                                                    break;
                                                default:
                                                    echo '<span class="badge bg-secondary">Tidak Diketahui</span>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data penyewaan ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Libraries -->
    <script src="vendors/scripts/core.js"></script>
    <script src="vendors/scripts/script.min.js"></script>
    <script src="vendors/scripts/process.js"></script>
    <script src="vendors/scripts/layout-settings.js"></script>
    <script src="src/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script src="src/plugins/datatables/js/dataTables.responsive.min.js"></script>
    <script src="src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.data-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json'
                }
            });
        });
    </script>
</body>
</html>

<?php mysqli_close($conn); ?>

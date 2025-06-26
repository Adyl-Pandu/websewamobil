<?php
session_start();

// Redirect ke dashboard jika belum login
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

include 'php/koneksi.php';
include 'header.php';
include 'menu_kiri.php';

// Inisialisasi nomor baris
$no = 1;

// Sanitasi input tanggal
$start_date = isset($_GET['start_date']) ? mysqli_real_escape_string($conn, $_GET['start_date']) : '';
$end_date = isset($_GET['end_date']) ? mysqli_real_escape_string($conn, $_GET['end_date']) : '';

// Query total pendapatan
$total_sql = "SELECT SUM(harga_sewa * durasi) as total_pendapatan FROM penyewaan WHERE 1=1";

// Query daftar transaksi
$transaksi_sql = "
    SELECT p.id, p.id_mobil, p.id_pelanggan, p.tanggal_mulai, p.tanggal_selesai, p.harga_sewa, p.durasi, 
           (p.harga_sewa * p.durasi) as total_bayar, p.status_pembayaran, pel.nama, m.model 
    FROM penyewaan p
    LEFT JOIN pelanggan pel ON p.id_pelanggan = pel.id_pelanggan
    LEFT JOIN mobil m ON p.id_mobil = m.id_mobil
    WHERE 1=1
";

// Filter tanggal jika ada
if (!empty($start_date)) {
    $total_sql .= " AND tanggal_mulai >= '$start_date'";
    $transaksi_sql .= " AND p.tanggal_mulai >= '$start_date'";
}
if (!empty($end_date)) {
    $total_sql .= " AND tanggal_selesai <= '$end_date'";
    $transaksi_sql .= " AND p.tanggal_selesai <= '$end_date'";
}

// Eksekusi query
$total_result = mysqli_query($conn, $total_sql) or die("Error dalam query total: " . mysqli_error($conn));
$total_row = mysqli_fetch_assoc($total_result);
$total_pendapatan = $total_row['total_pendapatan'] ?? 0;

$result = mysqli_query($conn, $transaksi_sql) or die("Error dalam query transaksi: " . mysqli_error($conn));
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>RentCarPro - Laporan Keuangan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/car-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/logo_mobil_biru.png">

    <!-- Google Fonts & Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="vendors/styles/core.css">
    <link rel="stylesheet" href="vendors/styles/icon-font.min.css">
    <link rel="stylesheet" href="src/plugins/datatables/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="src/plugins/datatables/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="vendors/styles/style.css">

    <!-- Custom Style -->
    <style>
        .main-container {
            background-color: #f1f3f5;
            min-height: 100vh;
        }

        .card-box {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .text-blue {
            color: #007bff;
            font-weight: 600;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
        }

        .badge.bg-danger {
            background-color: #dc3545;
            color: #fff;
        }

        .badge.bg-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .badge.bg-success {
            background-color: #28a745;
            color: #fff;
        }

        .badge.bg-secondary {
            background-color: #6c757d;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="main-container">
        <div class="pd-ltr-20 mt-2">
            <h4 class="text-blue mb-4 mt-4">Laporan Keuangan</h4>

            <!-- Filter Tanggal -->
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input 
                        type="date" 
                        id="start_date" 
                        name="start_date" 
                        class="form-control" 
                        value="<?= htmlspecialchars($start_date) ?>"
                    >
                </div>
                
                <div class="col-md-4">
                    <label for="end_date" class="form-label">Tanggal Selesai</label>
                    <input 
                        type="date" 
                        id="end_date" 
                        name="end_date" 
                        class="form-control" 
                        value="<?= htmlspecialchars($end_date) ?>"
                    >
                </div>
                
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        Tampilkan Laporan
                    </button>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <a
                        href="phptopdf/report-finance-to-pdf.php?start_date=<?= urlencode($start_date) ?>&end_date=<?= urlencode($end_date) ?>" 
                        class="btn btn-success w-100"
                        target="_blank"
                    >Cetak Laporan</a>
                </div>
            </form>

            <!-- Ringkasan Keuangan -->
            <div class="card-box mb-4 p-3">
                <h5 class="text-blue mb-3">Ringkasan Keuangan</h5>
                <p>Total Pendapatan: <strong>Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></strong></p>
            </div>

            <!-- Tabel Transaksi -->
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Daftar Transaksi</h4>
                </div>
                <div class="pb-20">
                    <table class="data-table table stripe hover nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Penyewa</th>
                                <th>Mobil</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
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
                                        <td><?= htmlspecialchars($row['tanggal_selesai']) ?></td>
                                        <td>Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?></td>
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
                                    <td colspan="7" class="text-center">Tidak ada data transaksi ditemukan.</td>
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

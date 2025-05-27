<?php
session_start();
include 'php/koneksi.php';

// Inisialisasi nomor baris
$no = 1;

// Sanitasi input untuk mencegah SQL Injection
$start_date = isset($_GET['start_date']) ? mysqli_real_escape_string($conn, $_GET['start_date']) : '';
$end_date = isset($_GET['end_date']) ? mysqli_real_escape_string($conn, $_GET['end_date']) : '';

// Query untuk mengambil data penyewaan
$sql = "SELECT p.id, p.id_mobil, p.id_pelanggan, p.tanggal_mulai, p.tanggal_selesai, p.status_pembayaran, 
               pel.nama, m.model 
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

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error dalam query: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>RentCarPro - Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="apple-touch-icon" sizes="180x180" href="vendors/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="vendors/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png">
    
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
    <?php 
    include 'header.php';
    include 'menu_kiri.php'; 
    ?>

    <div class="main-container">
        <div class="pd-ltr-20">
            <h4 class="text-blue mb-4 mt-4">Laporan Penyewaan</h4>

            <!-- Filter Tanggal -->
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="text" id="start_date" name="start_date" class="form-control flatpickr" 
                           placeholder="YYYY-MM-DD" value="<?= htmlspecialchars($start_date) ?>">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-labelودهالتاريخ النهائي</label>
                    <input type="text" id="end_date" name="end_date" class="form-control flatpickr" 
                           placeholder="YYYY-MM-DD" value="<?= htmlspecialchars($end_date) ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary">Tampilkan Laporan</button>
                        <a href="?reset=1" class="btn btn-secondary">Reset Filter</a>
                    </div>
                </div>
            </form>

            <!-- Tabel Laporan -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="penyewaanTable">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Penyewa</th>
                            <th>Mobil</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
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
                                <td colspan="7" class="text-center">Tidak ada data penyewaan ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="vendors/scripts/core.js"></script>
    <script src="vendors/scripts/script.min.js"></script>
    <script src="vendors/scripts/process.js"></script>
    <script src="vendors/scripts/layout-settings.js"></script>
    <script src="src/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script src="src/plugins/datatables/js/dataTables.responsive.min.js"></script>
    <script src="src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
    <script src="src/plugins/flatpickr/flatpickr.min.js"></script>
    <script>
        // Inisialisasi Flatpickr
        flatpickr(".flatpickr", {
            dateFormat: "Y-m-d",
            maxDate: "today"
        });

        // Inisialisasi DataTable
        $(document).ready(function() {
            $('#penyewaanTable').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
                }
            });
        });
    </script>
</body>
</html>

<?php mysqli_close($conn); ?>
<?php
// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include file koneksi
include 'php/koneksi.php';

// Cek koneksi database
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Include header dan menu
include 'header.php';
include 'menu_kiri.php';

// Query untuk mengambil data mobil yang statusnya "Tersedia"
$sql = "SELECT id_mobil, merek, model, plat_nomor, tahun, warna, kategori, harga_sewa, deskripsi 
        FROM mobil 
        WHERE status = 'Tersedia'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error dalam query: " . mysqli_error($conn));
}

// Inisialisasi nomor baris
$no = 1;
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
    <div class="main-container">
        <div class="pd-ltr-20">
            <!-- Welcome Card -->
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Daftar Mobil yang Tersedia</h4>
                </div>
                <div class="pb-20">
                    <table class="data-table table stripe hover nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Merek</th>
                                <th>Model</th>
                                <th>Plat Nomor</th>
                                <th>Tahun</th>
                                <th>Warna</th>
                                <th>Kategori</th>
                                <th>Harga Sewa</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($result) > 0):
                                while ($row = mysqli_fetch_assoc($result)):
                                    ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($row['merek']) ?></td>
                                        <td><?= htmlspecialchars($row['model']) ?></td>
                                        <td><?= htmlspecialchars($row['plat_nomor']) ?></td>
                                        <td><?= htmlspecialchars($row['tahun']) ?></td>
                                        <td><?= htmlspecialchars($row['warna']) ?></td>
                                        <td><?= htmlspecialchars($row['kategori']) ?></td>
                                        <td>Rp <?= number_format($row['harga_sewa'], 0, ',', '.') ?></td>
                                        <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                                    </tr>
                                    <?php
                                endwhile;
                            else:
                                ?>
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada mobil yang tersedia saat ini.</td>
                                </tr>
                                <?php
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="vendors/scripts/core.js"></script>
    <script src="vendors/scripts/script.min.js"></script>
    <script src="vendors/scripts/process.js"></script>
    <script src="vendors/scripts/layout-settings.js"></script>
    <script src="src/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script src="src/plugins/datatables/js/dataTables.responsive.min.js"></script>
    <script src="src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
    <script src="src/plugins/apexcharts/apexcharts.min.js"></script>
    <!-- JS plugin untuk DataTables -->
    <script>
        $(document).ready(function () {
            $('.data-table').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
                }
            });
        });
    </script>
</body>
</html>

<?php mysqli_close($conn); ?>
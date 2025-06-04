<?php
// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Koneksi ke database
include 'php/koneksi.php';

// Cek koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Include bagian header dan menu
include 'header.php';
include 'menu_kiri.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>RentCarPro - Dashboard Penyewaan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="vendors/images/favicon-32x32.png">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="vendors/styles/core.css">
    <link rel="stylesheet" href="vendors/styles/icon-font.min.css">
    <link rel="stylesheet" href="src/plugins/datatables/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="src/plugins/datatables/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="vendors/styles/style.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Optional Custom CSS -->
    <style>
        .table td,
        .table th {
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <div class="main-container">
        <div class="pd-ltr-20">
            <!-- Tabel Daftar Penyewaan -->
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Daftar Mobil dan Status</h4>
                </div>
                <div class="pb-20 table-responsive">
                    <table class="table table-bordered table-hover table-striped text-nowrap">
                        <thead class="thead-dark">
                            <tr>
                                <th>Merk</th>
                                <th>Model</th>
                                <th>Plat Nomor</th>
                                <th>Tahun</th>
                                <th>Warna</th>
                                <th>Kategori</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Query untuk mengambil data mobil beserta statusnya
                            $query = "SELECT merek, model, plat_nomor, tahun, warna, kategori, status FROM mobil";
                            $result = mysqli_query($conn, $query);

                            // Cek jika query berhasil
                            if (!$result) {
                                die("Query gagal: " . mysqli_error($conn));
                            }

                            // Loop untuk menampilkan data
                            while ($row = mysqli_fetch_assoc($result)) {
                                $badgeClass = ($row['status'] == 'Tersedia') ? 'badge-success' : 'badge-danger';
                                echo "
                    <tr>
                        <td>{$row['merek']}</td>
                        <td>{$row['model']}</td>
                        <td>{$row['plat_nomor']}</td>
                        <td>{$row['tahun']}</td>
                        <td>{$row['warna']}</td>
                        <td>{$row['kategori']}</td>
                        <td><span class='badge {$badgeClass}'>{$row['status']}</span></td>
                    </tr>";
                            }
                            ?>
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

</body>

</html>
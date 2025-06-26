<?php
include 'php/koneksi.php';

// Cek apakah user sudah login
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])):
    header('Location: index.php');
    exit;
endif;

// Include header dan menu
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
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/car-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/logo_mobil_biru.png">

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

    <!-- Custom CSS -->
    <style>
    /* Table styling */
    .table {
        background-color: #ffffff;
        border-radius: 8px;
        overflow: hidden;
    }

    .table thead {
        background-color: #007bff;
        color: #ffffff;
    }

    .table td,
    .table th {
        vertical-align: middle;
        padding: 12px 15px;
    }

    /* Badge styling */
    .badge-success {
        background-color: #28a745;
        color: #fff;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.9rem;
    }

    .badge-danger {
        background-color: #dc3545;
        color: #fff;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.9rem;
    }

    /* Cetak Laporan button styling */
    .cetak-laporan-link {
        display: inline-block;
        padding: 8px 16px;
        background-color: #28a745;
        color: #fff;
        text-decoration: none;
        border-radius: 4px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .cetak-laporan-link:hover {
        background-color: #218838;
        text-decoration: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .cetak-laporan-link:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.4);
    }

    /* Card box styling */
    .card-box {
        background: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .pd-20 {
        padding: 20px;
    }

    .main-container {
        background-color: #f1f3f5;
        min-height: 100vh;
        padding-top: 20px;
    }

    /* Header styling */
    h4.text-blue {
        color: #007bff;
        font-weight: 600;
    }
</style>

</head>

<body>
    <div class="main-container">
        <div class="pd-ltr-20">
            <!-- Card Box -->
            <div class="card-box mb-30">
                <div class="pd-20 d-flex justify-content-between align-items-center">
                    <h4 class="text-blue h4 m-0">Daftar Mobil dan Status</h4>
                    <a href="phptopdf/report-car-to-pdf.php" class="cetak-laporan-link" target="_blank">
                        <i class="fa fa-print mr-1"></i> Cetak Laporan
                    </a>
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
                            $query = "SELECT merek, model, plat_nomor, tahun, warna, kategori, status FROM mobil";
                            $result = mysqli_query($conn, $query);

                            if (!$result) {
                                die("Query gagal: " . mysqli_error($conn));
                            }

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

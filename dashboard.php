<?php
include 'php/koneksi.php';

// Cek apakah user sudah login
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])):
    header('Location: index.php');
    exit;
else:
    include 'header.php';
    include 'menu_kiri.php';
?>



    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="utf-8">
        <title>RentCarPro - Dashboard</title>
        <link rel="icon" type="image/png" sizes="32x32" href="images/logo_mobil_biru.png">
        <link rel="apple-touch-icon" sizes="180x180" href="assets/images/car-icon.png">

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
                <div class="card-box pd-20 height-50-p mb-30 welcome-card">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <img src="images/sewa_mobil.avif" alt="RentCarPro Banner" style="max-width: 100%;">
                        </div>
                        <div class="col-md-8">
                            <h4 class="font-20 weight-500 mb-10 text-capitalize">
                                Selamat datang di <span class="weight-600 font-30 text-blue">SEWA MOBIL TANGERANG</span>
                            </h4>
                            <p class="font-18 max-width-600">
                                Sistem manajemen penyewaan mobil profesional untuk mengelola armada kendaraan, pelanggan,
                                transaksi, dan laporan keuangan dengan mudah dan efisien.
                            </p>
                            <div class="mt-3">
                                <a href="pelanggan-add.php" class="btn bg-primary text-white mr-2">
                                    <i class="fa fa-plus"></i> Buat Penyewaan Baru
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="footer-wrap pd-20 mb-20 card-box">
                    <div class="row">
                        <div class="col-md-6">
                            RentCarPro v2.0 &copy; <?= date('Y') ?> - Sistem Manajemen Penyewaan Mobil
                        </div>
                        <div class="col-md-6 text-right">
                            <span class="mr-3">Status Sistem: <span class="text-success">Aktif</span></span>
                            <span>Versi: 2.0.1</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            if (window.history) {
                window.history.forward(1);
            }
        </script>

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
    </body>

    </html>

<?php endif; ?>
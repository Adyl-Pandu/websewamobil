<?php
include 'php/koneksi.php';

// Cek apakah user sudah login
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])):
    header('Location: index.php');
    exit;
endif;

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

            <!-- Statistik Status Pembayaran -->
            <div class="row clearfix mb-4">
                <?php
                $belum = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as jumlah FROM penyewaan WHERE status_pembayaran = 'Belum Dibayar'"))['jumlah'];
                $sebagian = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as jumlah FROM penyewaan WHERE status_pembayaran = 'Dibayar Sebagian'"))['jumlah'];
                $lunas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as jumlah FROM penyewaan WHERE status_pembayaran = 'Lunas'"))['jumlah'];
                ?>

                <div class="col-md-4 mb-3">
                    <div class="card-box bg-danger text-white p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">Belum Dibayar</h5>
                                <h3><?= $belum ?></h3>
                            </div>
                            <i class="fas fa-times-circle fa-2x"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card-box bg-warning text-white p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">Dibayar Sebagian</h5>
                                <h3><?= $sebagian ?></h3>
                            </div>
                            <i class="fas fa-hand-holding-usd fa-2x"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card-box bg-success text-white p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">Lunas</h5>
                                <h3><?= $lunas ?></h3>
                            </div>
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Daftar Penyewaan -->
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Daftar Penyewaan</h4>
                </div>
                <div class="pb-20 table-responsive">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped table-sm text-nowrap data-table-export">
                            <thead class="thead-dark small">
                                <tr>
                                    <th>No</th>
                                    <th>Pelanggan</th>
                                    <th>Mobil</th>
                                    <th>Mulai</th>
                                    <th>Durasi</th>
                                    <th>Harga Sewa</th>
                                    <th>Total Biaya</th>
                                    <th>Status</th>
                                    <th>Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                <?php
                                $query = "
                                    SELECT p.*, m.model AS nama_mobil, c.nama AS nama_pelanggan
                                    FROM penyewaan p
                                    JOIN mobil m ON p.id_mobil = m.id_mobil
                                    JOIN pelanggan c ON p.id_pelanggan = c.id_pelanggan
                                ";
                                $result = mysqli_query($conn, $query);

                                $no = 1; // Inisialisasi nomor
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $badgeClass = match ($row['status_pembayaran']) {
                                        'Lunas' => 'badge-success',
                                        'Dibayar Sebagian' => 'badge-warning',
                                        default => 'badge-danger',
                                    };

                                    echo "
                                        <tr>
                                        <td>{$no}</td>
                                        <td>{$row['nama_pelanggan']}</td>
                                        <td>{$row['nama_mobil']}</td>
                                        <td>{$row['tanggal_mulai']}</td>
                                        <td>{$row['durasi']} hari</td>
                                        <td>Rp " . number_format($row['harga_sewa'], 2, ',', '.') . "</td>
                                        <td>Rp " . number_format($row['total_biaya'], 2, ',', '.') . "</td>
                                        <td><span class='badge {$badgeClass}'>{$row['status_pembayaran']}</span></td>
                                        <td>{$row['dibuat_pada']}</td>
                                            <td>
                                            <a class='btn btn-sm btn-primary' href='phptopdf/payement-lits-to-pdf.php?id={$row['id']}' target='_blank'>
                                                Cetak resi
                                            </a>
                                            </td>
                                                ";
                                    $no++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
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
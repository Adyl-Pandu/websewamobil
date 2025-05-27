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
        .table td, .table th {
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
                <table class="table table-bordered table-hover table-striped text-nowrap data-table-export">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Pelanggan</th>
                            <th>Mobil</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th>Durasi</th>
                            <th>Harga Sewa</th>
                            <th>Total Biaya</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th>Dibuat</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "
                            SELECT p.*, m.model AS nama_mobil, c.nama AS nama_pelanggan
                            FROM penyewaan p
                            JOIN mobil m ON p.id_mobil = m.id_mobil
                            JOIN pelanggan c ON p.id_pelanggan = c.id_pelanggan
                        ";
                        $result = mysqli_query($conn, $query);

                        while ($row = mysqli_fetch_assoc($result)) {
                            $badgeClass = match ($row['status_pembayaran']) {
                                'Lunas' => 'badge-success',
                                'Dibayar Sebagian' => 'badge-warning',
                                default => 'badge-danger',
                            };

                            echo "
                            <tr>
                                <td>{$row['id']}</td>
                                <td>{$row['nama_pelanggan']}</td>
                                <td>{$row['nama_mobil']}</td>
                                <td>{$row['tanggal_mulai']}</td>
                                <td>{$row['tanggal_selesai']}</td>
                                <td>{$row['durasi']} hari</td>
                                <td>Rp " . number_format($row['harga_sewa'], 2, ',', '.') . "</td>
                                <td>Rp " . number_format($row['total_biaya'], 2, ',', '.') . "</td>
                                <td><span class='badge {$badgeClass}'>{$row['status_pembayaran']}</span></td>
                                <td>{$row['catatan']}</td>
                                <td>{$row['dibuat_pada']}</td>
                                <td>
                                    <a href='php/penyewaan_edit.php?id={$row['id']}' class='btn btn-sm btn-primary mb-1'>
                                        <i class='fas fa-edit'></i>
                                    </a>
                                    <a href='php/penyewaan_hapus.php?id={$row['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Yakin ingin menghapus data ini?')\">
                                        <i class='fas fa-trash'></i>
                                    </a>
                                </td>
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

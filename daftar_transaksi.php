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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>RentCarPro - Dashboard</title>
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
            <div class="card-box mb-30 mt-3">
                <div class="pd-20 d-flex justify-content-between align-items-center">
                    <h4 class="text-blue h4">Daftar Penyewaan</h4>
                    <a href="penyewaan_list.php" class="btn btn-primary">+ Tambah Penyewaan</a>
                </div>

                <div class="pb-20">
                    <table class="data-table table stripe hover nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Penyewa</th>
                                <th>Mobil</th>
                                <th>Tanggal Sewa</th>
                                <th>Tanggal Kembali</th>
                                <th>Status Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query = "SELECT p.id, pelanggan.nama AS nama_pelanggan, mobil.model AS nama_mobil, p.tanggal_mulai, p.tanggal_selesai, p.status_pembayaran
                                      FROM penyewaan p
                                      JOIN pelanggan ON p.id_pelanggan = pelanggan.id_pelanggan
                                      JOIN mobil ON p.id_mobil = mobil.id_mobil";

                            $result = mysqli_query($conn, $query);
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
                                    <td><?= htmlspecialchars($row['nama_mobil']) ?></td>
                                    <td><?= $row['tanggal_mulai'] ?></td>
                                    <td><?= $row['tanggal_selesai'] ?></td>
                                    <td><?= htmlspecialchars($row['status_pembayaran']) ?></td>
                                    <td>
                                        <a href="php/penyewaan_edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                        <a href="php/penyewaan_hapus.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus penyewaan ini?')"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script src="vendors/scripts/core.js"></script>
    <script src="vendors/scripts/script.min.js"></script>
    <script src="vendors/scripts/process.js"></script>
    <script src="vendors/scripts/layout-settings.js"></script>
    <script src="src/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script src="src/plugins/datatables/js/dataTables.responsive.min.js"></script>
    <script src="src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
    <script src="src/plugins/apexcharts/apexcharts.min.js"></script>
    <!-- JS plugin untuk datatables -->
    <script>
        $(document).ready(function () {
            $('.data-table').DataTable();
        });
    </script>
</body>
</html>

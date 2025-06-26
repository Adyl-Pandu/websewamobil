<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>RentCarPro - Admin Dashboard</title>
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/car-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/logo_mobil_biru.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="vendors/styles/core.css">
    <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css">
    <link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="vendors/styles/style.css">
</head>
<body>
    <!-- <div class="pre-loader">
        <div class="pre-loader-box">
            <div class="loader-logo"><img src="vendors/images/rentcar-logo.svg" alt=""></div>
            <div class='loader-progress' id="progress_div">
                <div class='bar' id='bar1'></div>
            </div>
            <div class='percent' id='percent1'>0%</div>
            <div class="loading-text">Memuat Sistem...</div>
        </div>
    </div> -->

    <?php include 'header.php'; ?>
    <?php include 'menu_kiri.php'; ?>

    <div class="main-container">
        <div class="pd-20 card-box mb-30">
            <?php
            if (isset($_GET['status'])) {
                if ($_GET['status'] == 'success') {
                    echo '<div class="alert alert-success">Aksi berhasil dilakukan!</div>';
                } elseif ($_GET['status'] == 'error') {
                    echo '<div class="alert alert-danger">Terjadi kesalahan: ' . (isset($_GET['message']) ? htmlspecialchars($_GET['message']) : 'Silakan coba lagi.') . '</div>';
                } elseif ($_GET['status'] == 'invalid') {
                    echo '<div class="alert alert-warning">Input tidak valid.</div>';
                }
            }
            ?>
            <div class="clearfix mb-20">
                <div class="pull-left">
                    <h4 class="text-blue h4">Manajemen Kategori Mobil</h4>
                    <p class="mb-30">Daftar kategori mobil yang tersedia</p>
                </div>
                <div class="pull-right">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahKategori">
                        <i class="fa fa-plus"></i> Tambah Kategori
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped" id="tabelKategori">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori</th>
                            <th>Jumlah Mobil</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'php/koneksi.php';
                        $no = 1;
                        $q = mysqli_query($conn, "SELECT * FROM kategori_mobil ORDER BY id DESC");
                        while ($row = mysqli_fetch_assoc($q)):
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($row['nama_kategori']); ?></td>
                                <td>
                                    <?php
                                    $cek = mysqli_query($conn, "SELECT COUNT(*) AS total FROM mobil WHERE kategori = '" . mysqli_real_escape_string($conn, $row['nama_kategori']) . "'");
                                    $hasil = mysqli_fetch_assoc($cek);
                                    echo $hasil['total'];
                                    ?>
                                </td>
                                <td><?= htmlspecialchars($row['deskripsi']); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-toggle="modal"
                                        data-target="#modalEditKategori<?= $row['id']; ?>">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <a href="php/kategori-handler.php?aksi=hapus&id=<?= $row['id']; ?>"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Yakin ingin menghapus kategori <?= htmlspecialchars($row['nama_kategori']); ?>?')">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Modal Edit Kategori -->
                            <div class="modal fade" id="modalEditKategori<?= $row['id']; ?>" tabindex="-1" role="dialog"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <form action="php/kategori-handler.php?aksi=edit" method="POST">
                                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Edit Kategori</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Nama Kategori</label>
                                                    <input type="text" name="nama_kategori" class="form-control"
                                                           value="<?= htmlspecialchars($row['nama_kategori']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Deskripsi</label>
                                                    <textarea name="deskripsi" class="form-control"><?= htmlspecialchars($row['deskripsi']); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Kategori -->
    <div class="modal fade" id="modalTambahKategori" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Kategori Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form action="php/kategori-handler.php?aksi=tambah" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Kategori</label>
                            <input type="text" name="nama_kategori" class="form-control" placeholder="Misal: Sedan, SUV, MPV" required>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" placeholder="Deskripsi singkat kategori"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="vendors/scripts/core.js"></script>
    <script src="vendors/scripts/script.min.js"></script>
    <script src="vendors/scripts/process.js"></script>
    <script src="vendors/scripts/layout-settings.js"></script>
    <script src="src/plugins/apexcharts/apexcharts.min.js"></script>
    <script src="src/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script src="src/plugins/datatables/js/dataTables.responsive.min.js"></script>
    <script src="src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
    <script src="vendors/scripts/dashboard.js"></script>
    <script>
    $(document).ready(function() {
        $('#tabelKategori').Dataaunch({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
            }
        });
    });
    </script>
</body>
</html>
<?php
include 'php/koneksi.php';

// Cek apakah user sudah login
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])):
    header('Location: index.php');
    exit;
endif;
// Buat token CSRF untuk keamanan
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
include 'php/koneksi.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tambah Penyewaan - RentCarPro</title>
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/car-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/logo_mobil_biru.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="vendors/styles/core.css">
    <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css">
    <link rel="stylesheet" type="text/css" href="vendors/styles/style.css">

    <style>
        .form-group small {
            color: #666;
            display: block;
            margin-top: 5px;
        }
        .total-cost {
            font-weight: bold;
            color: #1b00ff;
        }
        .alert-dismissible .btn-close {
            padding: 0.75rem 1.25rem;
        }
        .form-control[readonly] {
            background-color: #e9ecef;
        }
        .card-box {
            margin-bottom: 30px;
        }
        .alert-info {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php
    include 'header.php';
    include 'menu_kiri.php';
    ?>

    <div class="main-container">
        <div class="pd-ltr-20">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Form Tambah Penyewaan</h4>
                    <p class="mb-0">Isi form berikut untuk menambahkan data penyewaan baru</p>
                </div>
                <div class="pd-20">
                    <?php
                    if (isset($_GET['status'])) {
                        $message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
                        if ($_GET['status'] == 'success') {
                            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Penyewaan berhasil ditambahkan!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                        } elseif ($_GET['status'] == 'error') {
                            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Terjadi kesalahan: ' . $message . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                        } elseif ($_GET['status'] == 'invalid') {
                            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">Input tidak valid: ' . $message . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                        }
                    }
                    ?>
                    <form method="POST" action="php/penyewaan_process.php" id="rentalForm">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id_pelanggan" class="form-label">Pelanggan</label>
                                    <?php
                                    $query_pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY nama");
                                    if (!$query_pelanggan) {
                                        error_log("Error fetching pelanggan: " . mysqli_error($conn));
                                        echo '<div class="alert alert-danger">Error: Tidak dapat mengambil data pelanggan. Silakan periksa database.</div>';
                                    } elseif (mysqli_num_rows($query_pelanggan) == 0) {
                                        echo '<div class="alert alert-info">Tidak ada pelanggan tersedia. <a href="pelanggan_add.php">Tambah pelanggan terlebih dahulu</a>.</div>';
                                        echo '<select class="form-control" id="id_pelanggan" name="id_pelanggan" disabled>';
                                        echo '<option value="">Tidak ada pelanggan</option>';
                                        echo '</select>';
                                    } else {
                                        echo '<select class="form-control" id="id_pelanggan" name="id_pelanggan" required aria-required="true">';
                                        echo '<option value="">Pilih Pelanggan</option>';
                                        while ($pelanggan = mysqli_fetch_assoc($query_pelanggan)) {
                                            $id = htmlspecialchars($pelanggan['id_pelanggan']);
                                            $nama = htmlspecialchars($pelanggan['nama']);
                                            $no_ktp = htmlspecialchars($pelanggan['no_ktp']);
                                            echo "<option value='$id'>$nama ($no_ktp)</option>";
                                        }
                                        echo '</select>';
                                    }
                                    ?>
                                    <small>Pilih pelanggan yang menyewa</small>
                                </div>

                                <div class="form-group">
                                    <label for="id_mobil" class="form-label">Mobil</label>
                                    <?php
                                    $query_mobil = mysqli_query($conn, "SELECT * FROM mobil WHERE status = 'Tersedia' ORDER BY merek, model");
                                    if (!$query_mobil) {
                                        error_log("Error fetching mobil: " . mysqli_error($conn));
                                        echo '<div class="alert alert-danger">Error: Tidak dapat mengambil data mobil. Silakan periksa database.</div>';
                                    } elseif (mysqli_num_rows($query_mobil) == 0) {
                                        echo '<div class="alert alert-info">Tidak ada mobil tersedia. <a href="mobil_add.php">Tambah mobil terlebih dahulu</a>.</div>';
                                        echo '<select class="form-control" id="id_mobil" name="id_mobil" disabled>';
                                        echo '<option value="">Tidak ada mobil</option>';
                                        echo '</select>';
                                    } else {
                                        echo '<select class="form-control" id="id_mobil" name="id_mobil" required aria-required="true" onchange="updateHargaSewa()">';
                                        echo '<option value="">Pilih Mobil</option>';
                                        while ($mobil = mysqli_fetch_assoc($query_mobil)) {
                                            $id_mobil = htmlspecialchars($mobil['id_mobil']);
                                            $merek = htmlspecialchars($mobil['merek']);
                                            $model = htmlspecialchars($mobil['model']);
                                            $plat_nomor = htmlspecialchars($mobil['plat_nomor']);
                                            $harga_sewa = is_numeric($mobil['harga_sewa']) ? number_format($mobil['harga_sewa'], 0, '.', '') : 0;
                                            echo "<option value='$id_mobil' data-harga='$harga_sewa'>$merek $model ($plat_nomor)</option>";
                                        }
                                        echo '</select>';
                                    }
                                    ?>
                                    <small>Pilih mobil yang tersedia untuk disewa</small>
                                </div>

                                <div class="form-group">
                                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai Sewa</label>
                                    <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required aria-required="true">
                                    <small>Pilih tanggal mulai penyewaan</small>
                                </div>

                                <div class="form-group">
                                    <label for="durasi" class="form-label">Durasi Sewa (Hari)</label>
                                    <input type="number" class="form-control" id="durasi" name="durasi" min="1" required aria-required="true" oninput="hitungTotal()">
                                    <small>Masukkan jumlah hari sewa</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="harga_sewa" class="form-label">Harga Sewa per Hari (Rp)</label>
                                    <input type="number" class="form-control" id="harga_sewa" name="harga_sewa" readonly>
                                    <small>Harga otomatis diambil dari mobil yang dipilih</small>
                                </div>

                                <div class="form-group">
                                    <label for="total_biaya" class="form-label">Total Biaya (Rp)</label>
                                    <input type="number" class="form-control" id="total_biaya" name="total_biaya" readonly>
                                    <small>Total biaya dihitung dari harga sewa x durasi</small>
                                </div>

                                <div class="form-group">
                                    <label for="status_pembayaran" class="form-label">Status Pembayaran</label>
                                    <select class="form-control" id="status_pembayaran" name="status_pembayaran" required aria-required="true">
                                        <option value="Belum Dibayar">Belum Dibayar</option>
                                        <option value="Dibayar Sebagian">Dibayar Sebagian</option>
                                        <option value="Lunas">Lunas</option>
                                    </select>
                                    <small>Pilih status pembayaran saat ini</small>
                                </div>

                                <div class="form-group">
                                    <label for="catatan" class="form-label">Catatan (Opsional)</label>
                                    <textarea class="form-control" id="catatan" name="catatan" rows="3" placeholder="Tambahkan catatan untuk penyewaan"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="icon-copy dw dw-diskette1"></i> Simpan Penyewaan
                                    </button>
                                    <button type="reset" class="btn btn-warning">
                                        <i class="icon-copy dw dw-refresh"></i> Reset Form
                                    </button>
                                    <a href="penyewaan_list.php" class="btn btn-secondary">
                                        <i class="icon-copy dw dw-cancel"></i> Batal
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="footer-wrap pd-20 mb-20 card-box">
                RentCarPro - Sistem Manajemen Penyewaan Mobil v1.0 © <?php echo date('Y'); ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="vendors/scripts/core.js"></script>
    <script src="vendors/scripts/script.min.js"></script>
    <script src="vendors/scripts/process.js"></script>
    <script src="vendors/scripts/layout-settings.js"></script>

    <script>
        flatpickr('#tanggal_mulai', {
            minDate: "today",
            dateFormat: "Y-m-d",
            disable: [
                function(date) {
                    return false; // Logika untuk tanggal yang dipesan bisa ditambahkan
                }
            ]
        });

        function updateHargaSewa() {
            const mobilSelect = document.getElementById('id_mobil');
            const hargaSewaInput = document.getElementById('harga_sewa');
            const selectedOption = mobilSelect.options[mobilSelect.selectedIndex];
            const hargaSewa = selectedOption && selectedOption.dataset.harga ? parseFloat(selectedOption.dataset.harga) : 0;

            console.log('Harga Sewa:', hargaSewa);
            hargaSewaInput.value = hargaSewa || '';
            hitungTotal();
        }

        function hitungTotal() {
            const hargaSewa = parseFloat(document.getElementById('harga_sewa').value) || 0;
            const durasi = parseInt(document.getElementById('durasi').value) || 0;
            const totalBiaya = hargaSewa * durasi;

            console.log('Total Biaya:', totalBiaya);
            document.getElementById('total_biaya').value = totalBiaya || '';
        }

        document.getElementById('rentalForm').addEventListener('submit', function(e) {
            const idPelangganSelect = document.getElementById('id_pelanggan');
            const idPelanggan = idPelangganSelect.value.trim();
            console.log('ID Pelanggan:', idPelanggan); // Debug nilai
            if (!idPelanggan || idPelanggan === '' || isNaN(idPelanggan) || parseInt(idPelanggan) <= 0) {
                alert('Silakan pilih pelanggan yang valid!');
                e.preventDefault();
                return;
            }
            const tanggalMulai = new Date(document.getElementById('tanggal_mulai').value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            if (tanggalMulai < today) {
                alert('Tanggal mulai sewa tidak boleh di masa lalu!');
                e.preventDefault();
                return;
            }
            const durasi = parseInt(document.getElementById('durasi').value);
            if (durasi <= 0 || isNaN(durasi)) {
                alert('Durasi sewa harus lebih dari 0!');
                e.preventDefault();
                return;
            }
            const hargaSewa = parseFloat(document.getElementById('harga_sewa').value);
            if (!hargaSewa || hargaSewa <= 0 || isNaN(hargaSewa)) {
                alert('Harga sewa tidak valid! Pilih mobil terlebih dahulu.');
                e.preventDefault();
                return;
            }
        });
    </script>
</body>
</html>
<?php mysqli_close($conn); ?>
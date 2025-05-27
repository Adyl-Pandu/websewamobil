<!--  -->



<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>Tambah Mobil - RentCarPro</title>

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="vendors/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="vendors/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="vendors/styles/core.css">
    <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css">
    <link rel="stylesheet" type="text/css" href="vendors/styles/style.css">

    <style>
        .car-preview {
            width: 200px;
            height: 150px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px dashed #ddd;
            display: none;
        }

        .upload-area {
            border: 2px dashed #ddd;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .upload-area:hover {
            border-color: #1b00ff;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    <?php include 'menu_kiri.php'; ?>

    <div class="main-container">
        <div class="pd-ltr-20">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Tambah Mobil Baru</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Halaman Utama</a></li>
                                <li class="breadcrumb-item"><a href="car-list.php">Daftar Mobil</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Tambah Mobil</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Form Tambah Mobil</h4>
                    <p class="mb-0">Isi form berikut untuk menambahkan mobil baru ke sistem</p>
                </div>
                <div class="pd-20">
                    <form method="POST" action="php/car_process.php" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Merek Mobil</label>
                                    <input type="text" class="form-control" name="merek" required
                                        placeholder="Contoh: Toyota">
                                </div>

                                <div class="form-group">
                                    <label>Model/Type</label>
                                    <input type="text" class="form-control" name="model" required
                                        placeholder="Contoh: Avanza">
                                </div>

                                <div class="form-group">
                                    <label>Plat Nomor</label>
                                    <input type="text" class="form-control" name="plat_nomor" required
                                        placeholder="Contoh: B 1234 ABC">
                                </div>

                                <div class="form-group">
                                    <label>Tahun</label>
                                    <select class="form-control" name="tahun" required>
                                        <option value="">Pilih Tahun</option>
                                        <?php
                                        $current_year = date("Y");
                                        for ($i = $current_year; $i >= 2000; $i--) {
                                            echo "<option value='$i'>$i</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Warna</label>
                                    <input type="text" class="form-control" name="warna" required
                                        placeholder="Contoh: Putih">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select class="form-control" name="kategori" required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="City Car">City Car</option>
                                        <option value="MPV">MPV</option>
                                        <option value="SUV">SUV</option>
                                        <option value="Sedan">Sedan</option>
                                        <option value="Sport">Sport</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Harga Sewa per Hari (Rp)</label>
                                    <input type="number" class="form-control" name="harga_sewa" required
                                        placeholder="Contoh: 350000">
                                </div>

                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" name="status" required>
                                        <option value="Tersedia">Tersedia</option>
                                        <option value="Disewa">Disewa</option>
                                        <option value="Perawatan">Perawatan</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Deskripsi (Optional)</label>
                                    <textarea class="form-control" name="deskripsi" rows="3"
                                        placeholder="Tambahkan deskripsi mobil"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="icon-copy dw dw-diskette1"></i> Simpan Data
                                    </button>
                                    <a href="car-list.php" class="btn btn-secondary">
                                        <i class="icon-copy dw dw-cancel"></i> Batal
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="footer-wrap pd-20 mb-20 card-box">
                RentCarPro - Sistem Manajemen Penyewaan Mobil v1.0 &copy; 2023
            </div>
        </div>
    </div>

    <!-- js -->
    <script src="vendors/scripts/core.js"></script>
    <script src="vendors/scripts/script.min.js"></script>
    <script src="vendors/scripts/process.js"></script>
    <script src="vendors/scripts/layout-settings.js"></script>

    <script>
        // Image preview functionality
        document.getElementById('upload-area').addEventListener('click', function () {
            document.getElementById('car-image').click();
        });

        document.getElementById('car-image').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    const preview = document.getElementById('car-preview');
                    preview.src = event.target.result;
                    preview.style.display = 'block';

                    // Update upload area display
                    const uploadArea = document.getElementById('upload-area');
                    uploadArea.innerHTML = '<p>Gambar terpilih: ' + file.name + '</p>';
                    uploadArea.style.padding = '10px';
                }
                reader.readAsDataURL(file);
            }
        });

        // Drag and drop functionality
        const uploadArea = document.getElementById('upload-area');

        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#1b00ff';
            uploadArea.style.backgroundColor = 'rgba(27, 0, 255, 0.05)';
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.style.borderColor = '#ddd';
            uploadArea.style.backgroundColor = '';
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#ddd';
            uploadArea.style.backgroundColor = '';

            const file = e.dataTransfer.files[0];
            if (file && file.type.match('image.*')) {
                document.getElementById('car-image').files = e.dataTransfer.files;

                const reader = new FileReader();
                reader.onload = function (event) {
                    const preview = document.getElementById('car-preview');
                    preview.src = event.target.result;
                    preview.style.display = 'block';

                    // Update upload area display
                    uploadArea.innerHTML = '<p>Gambar terpilih: ' + file.name + '</p>';
                    uploadArea.style.padding = '10px';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>

<?php
// Clear form data after display
unset($_SESSION['data_form']);
unset($_SESSION['daftar_error']);

// Include footer
// include __DIR__ . '/footer.php';
?>
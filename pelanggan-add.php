<?php
include 'php/koneksi.php';

// Cek apakah user sudah login
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])):
    header('Location: index.php');
    exit;
endif
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <!-- head kamu tetap sama -->
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
    <?php include 'header.php'; ?>
    <?php include 'menu_kiri.php'; ?>

    <div class="main-container mb-4">
        <div class="pd-ltr-20 mt-5">
            <div class="card-box mb-30 fade-in">
                <div class="pd-20">
                    <h4 class="text-blue h4 mb-2">Form Tambah Pelanggan</h4>
                    <p class="mb-0 text-muted">Masukkan data pelanggan baru ke dalam sistem.</p>
                </div>
                <div class="pd-20">
                    <form method="POST" action="php/pelanggan_process.php" id="pelangganForm" novalidate>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nama" required
                                        placeholder="Contoh: John Doe" data-bs-toggle="tooltip"
                                        title="Masukkan nama lengkap pelanggan">
                                    <div class="invalid-feedback">Nama lengkap wajib diisi.</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Nomor KTP <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="no_ktp" required maxlength="16"
                                        pattern="\d{16}" placeholder="Contoh: 1234567890123456" data-bs-toggle="tooltip"
                                        title="Masukkan 16 digit nomor KTP">
                                    <div class="invalid-feedback">Nomor KTP harus 16 digit angka.</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Alamat</label>
                                    <textarea class="form-control" name="alamat" rows="3"
                                        placeholder="Masukkan alamat lengkap"></textarea>
                                    <div class="form-text">Opsional: Masukkan alamat pelanggan.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Nomor Telepon</label>
                                    <input type="text" class="form-control" name="no_telepon" maxlength="15"
                                        pattern="\+?\d{10,15}" placeholder="Contoh: 081234567890"
                                        data-bs-toggle="tooltip" title="Masukkan nomor telepon (10-15 digit)">
                                    <div class="invalid-feedback">Nomor telepon harus 10-15 digit.</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email"
                                        placeholder="Contoh: john.doe@example.com" data-bs-toggle="tooltip"
                                        title="Masukkan alamat email yang valid">
                                    <div class="invalid-feedback">Email tidak valid.</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Catatan</label>
                                    <textarea class="form-control" name="catatan" rows="3"
                                        placeholder="Tambahkan catatan untuk pelanggan"></textarea>
                                    <div class="form-text">Opsional: Tambahkan catatan tambahan.</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-outline-danger" onclick="resetForm()">
                                        <i class="icon-copy dw dw-refresh"></i> Reset
                                    </button>
                                    <button type="button" class="btn btn-secondary" onclick="confirmCancel()">
                                        <i class="icon-copy dw dw-cancel"></i> Batal
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="icon-copy dw dw-diskette1"></i> Simpan Data
                                    </button>
                                    <a href="pelanggan-lits.php" class="btn btn-primary" id="submitBtn">
                                        <i class="icon-copy dw dw-diskette1"></i> Pelanggan lits
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="footer-wrap pd-20 mt-4 card-box">
                RentCarPro - Sistem Manajemen Penyewaan Mobil v1.0 © 2023
            </div>
        </div>
    </div>

    <script src="vendors/scripts/core.js"></script>
    <script src="vendors/scripts/script.min.js"></script>
    <script src="vendors/scripts/process.js"></script>
    <script src="vendors/scripts/layout-settings.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Bootstrap form validation
        (function () {
            'use strict';
            const form = document.getElementById('pelangganForm');
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    const submitBtn = document.getElementById('submitBtn');
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="icon-copy dw dw-diskette1"></i> Menyimpan...';
                }
                form.classList.add('was-validated');
            }, false);
        })();

        // Initialize Bootstrap tooltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

        // Reset form
        function resetForm() {
            const form = document.getElementById('pelangganForm');
            form.reset();
            form.classList.remove('was-validated');
            document.querySelectorAll('.form-control').forEach(input => {
                input.classList.remove('is-invalid');
            });
        }

        // Confirm cancel
        function confirmCancel() {
            if (confirm('Apakah Anda yakin ingin membatalkan? Data yang diisi akan hilang.')) {
                window.location.href = 'pelanggan_list.php';
            }
        }
    </script>
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

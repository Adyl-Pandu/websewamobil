<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>BIKI Sewa Mobil Tangerang - Daftar Akun</title>

  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="180x180" href="images/logo_mobil_biru.png">
  <link rel="icon" type="image/png" sizes="32x32" href="images/logo_mobil_biru.png">
  <link rel="icon" type="image/png" sizes="16x16" href="images/logo_mobil_biru.png">

  <!-- Responsive -->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Styles -->
  <link rel="stylesheet" type="text/css" href="vendors/styles/core.css">
  <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css">
  <link rel="stylesheet" type="text/css" href="vendors/styles/style.css">
</head>

<body class="login-page">
<?php session_start(); ?>

  <!-- Header -->
  <div class="login-header box-shadow">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <div class="brand-logo">
        <a href="login.php">
          <img src="images/logo_mobil_biru.png" alt="BIKI Sewa Mobil Tangerang" style="height: 40px;">
        </a>
      </div>
      <div class="login-menu">
        <ul>
          <li><a href="index.php">Login</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Register Content -->
  <div class="register-page-wrap d-flex align-items-center flex-wrap justify-content-center">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6 col-lg-7">
          <img src="images/logo_mobil_biru.png" alt="Daftar Sewa Mobil" class="img-fluid d-block mx-auto mb-4" width="500">
        </div>
        <div class="col-md-6 col-lg-5">
          <div class="register-box bg-white box-shadow border-radius-10">
            <div class="pd-30">
              <h4 class="text-center mb-4">Daftar Akun</h4>

              <!-- Tampilkan pesan error jika ada -->
              <?php if (!empty($_SESSION['register_error'])): ?>
                <div class="alert alert-danger">
                  <ul class="mb-0">
                    <?php foreach ($_SESSION['register_error'] as $error): ?>
                      <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                  </ul>
                </div>
                <?php unset($_SESSION['register_error']); ?>
              <?php endif; ?>

              <form action="php/register_process.php" method="post">
                <div class="form-group">
                  <label>Username*</label>
                  <input type="text" name="nama" class="form-control" placeholder="Username unik" required>
                </div>
                <div class="form-group">
                  <label>Password*</label>
                  <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="form-group">
                  <label>Ulangi Password*</label>
                  <input type="password" name="konfirmasi_password" class="form-control" placeholder="Ulangi password" required>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary btn-block">Daftar</button>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Sukses (tidak dipakai otomatis, hanya jika Anda ingin pakai JS untuk menampilkan) -->
  <div class="modal fade" id="success-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered max-width-400" role="document">
      <div class="modal-content">
        <div class="modal-body text-center font-18">
          <h3 class="mb-20">Pendaftaran Berhasil!</h3>
          <div class="mb-30">
            <img src="vendors/images/success.png" alt="Success">
          </div>
          Akun Anda telah berhasil didaftarkan di BIKI Sewa Mobil Tangerang.
        </div>
        <div class="modal-footer justify-content-center">
          <a href="login.php" class="btn btn-primary">Lanjut ke Login</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="vendors/scripts/core.js"></script>
  <script src="vendors/scripts/script.min.js"></script>
  <script src="vendors/scripts/process.js"></script>
  <script src="vendors/scripts/layout-settings.js"></script>
</body>
</html>

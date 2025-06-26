<?php
    session_start();
    include 'php/koneksi.php';

    // Redirect ke dashboard jika sudah login
    if (isset($_SESSION['username'])) {
        header("Location: dashboard.php");
      exit;
    }
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login - Sewa Mobil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/car-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/logo_mobil_biru.png">
    <link rel="stylesheet" type="text/css" href="vendors/styles/core.css">
    <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css">
    <link rel="stylesheet" type="text/css" href="vendors/styles/style.css">
    <script type="text/javascript">
        setTimeout(() =>window.history.forward(),0);
        window.onunload=()=>{null};
    </script>

</head>
<body class="login-page">
    <div class="login-header box-shadow">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="brand-logo">
                <a href="login.php">
                    <img src="images/logo_mobil_biru.png" alt="Sewa Mobil Logo" class="logo-icon" width="50" height="50">
                </a>
            </div>
            <div class="login-menu">
                <ul>
                    <li><a href="register.php">Daftar</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-7">
                    <img src="images/logo_mobil_biru.png" alt="Login Image" class="img-fluid d-block mx-auto mb-4" width="500"> 
                </div>
                <div class="col-md-6 col-lg-5">
                    <div class="login-box bg-white box-shadow border-radius-10">
                        <div class="login-title">
                            <h2 class="text-center text-primary">Masuk ke Sewa Mobil</h2>
                        </div>

                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger text-center">
                                <?php
                                    if ($_GET['error'] == 'password') echo "Password salah!";
                                    elseif ($_GET['error'] == 'username') echo "username tidak ditemukan!";
                                    elseif ($_GET['error'] == 'empty') echo "username dan password wajib diisi!";
                                ?>
                            </div>
                        <?php endif; ?>

                        <form action="php/login_process.php" method="POST">
                            <div class="input-group custom">
                                <input type="text" name="username" class="form-control form-control-lg" placeholder="username" required>
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                                </div>
                            </div>
                            <div class="input-group custom">
                                <input type="password" name="password" class="form-control form-control-lg" placeholder="********" required>
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                                </div>
                            </div>
                            <div class="row pb-30">
                                <div class="col-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1">
                                        <label class="custom-control-label" for="customCheck1">Ingat saya</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="forgot-password"><a href="#">Lupa Password?</a></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="input-group mb-0">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">Masuk</button>
                                    </div>
                                    <div class="font-16 weight-600 pt-10 pb-10 text-center" data-color="#707373">ATAU</div>
                                    <div class="input-group mb-0">
                                        <a class="btn btn-outline-primary btn-lg btn-block" href="register.php">Buat Akun Baru</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>

</html>

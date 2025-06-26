<div class="left-side-bar">
    <div class="brand-logo">
        <a href="index.php" class="d-flex justify-content-center">
            <img src="images/logo_mobil_biru.png" alt="Logo RentCarPro" class="dark-logo" width="60">
            <img src="images/logo_mobil_biru.png" alt="Logo RentCarPro" class="light-logo" width="60">
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>

    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">
                <!-- Halaman Utama -->
                <li>
                    <a href="dashboard.php" class="dropdown-toggle no-arrow">
                        <span class="micon dw dw-house-1"></span><span class="mtext">Halaman Utama</span>
                    </a>
                </li>

                <!-- Data Master -->
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon dw dw-library"></span><span class="mtext">Data Master</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="car-add.php">Tambah Mobil</a></li>
                        <li><a href="pelanggan-add.php">Tambah Pelanggan</a></li>
                        <li><a href="penyewaan_list.php">Form Tambah Penyewaan</a></li>
                        <li><a href="car-list.php">Daftar Mobil</a></li>
                    </ul>
                </li>

                <!-- Data Transaksi -->
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon dw dw-money"></span><span class="mtext">Data Transaksi</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="daftar_transaksi.php">Pembayaran</a></li>
                        <li><a href="payment-list.php">Daftar Transaksi</a></li>
                    </ul>
                </li>

                <!-- Data Laporan -->
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon dw dw-analytics"></span><span class="mtext">Data Laporan</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="report-rental.php">Laporan Penyewaan</a></li>
                        <li><a href="report-finance.php">Laporan Keuangan</a></li>
                        <li><a href="report-car.php">Laporan Mobil</a></li>
                    </ul>
                </li>

                <!-- Logout -->
                <li>
                    <a href="php/log_out.php" class="dropdown-toggle no-arrow">
                        <span class="micon dw dw-logout"></span><span class="mtext">Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="mobile-menu-overlay"></div>

<style>
.brand-logo {
    text-align: center;
    padding: 15px 0;
}

.brand-logo a {
    display: block;
    margin: 0 auto;
}

.sidebar-menu li.active > a {
    background-color: #1b00ff;
    color: white;
}

.sidebar-menu li a:hover {
    background-color: #f5f5f5;
}

/* Pastikan logo muncul */
.dark-logo, .light-logo {
    display: none;
}
.light-logo {
    display: block; /* Default ke light-logo */
}
</style>
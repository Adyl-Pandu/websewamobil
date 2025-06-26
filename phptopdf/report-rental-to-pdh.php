<?php
require_once "../vendor/autoload.php";
require_once "../php/koneksi.php";

$no = 1;
$query = "SELECT p.*, pelanggan.nama, mobil.model 
          FROM penyewaan p 
          JOIN pelanggan ON p.id_pelanggan = pelanggan.id_pelanggan 
          JOIN mobil ON p.id_mobil = mobil.id_mobil";
$result = mysqli_query($conn, $query);

ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12pt; color: #333; }
        h1 { text-align: center; color: #007bff; margin-bottom: 10px; }
        .subtitle { text-align: center; font-size: 10pt; color: #666; margin-bottom: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #bbb; padding: 8px; text-align: left; }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .badge { padding: 3px 6px; border-radius: 3px; font-size: 10pt; }
        .bg-danger { background-color: #dc3545; color: white; }
        .bg-warning { background-color: #ffc107; color: black; }
        .bg-success { background-color: #28a745; color: white; }
        .bg-secondary { background-color: #6c757d; color: white; }
        .footer { margin-top: 30px; text-align: center; font-size: 9pt; color: #777; }
    </style>
</head>
<body>
    <h1>Laporan Data Penyewaan</h1>
    <div class="subtitle">MitraTeknoNiaga - <?= date("d M Y") ?></div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Penyewa</th>
                <th>Mobil</th>
                <th>Tanggal Mulai</th>
                <th>Harga Sewa</th>
                <th>Durasi (hari)</th>
                <th>Total Bayar</th>
                <th>Status Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama'] ?? 'Tidak diketahui') ?></td>
                        <td><?= htmlspecialchars($row['model'] ?? 'Tidak diketahui') ?></td>
                        <td><?= htmlspecialchars($row['tanggal_mulai']) ?></td>
                        <td>Rp <?= number_format($row['harga_sewa'], 0, ',', '.') ?></td>
                        <td><?= htmlspecialchars($row['durasi']) ?></td>
                        <td>Rp <?= number_format($row['harga_sewa'] * $row['durasi'], 0, ',', '.') ?></td>
                        <td>
                            <?php
                            switch ($row['status_pembayaran']) {
                                case 'Belum Dibayar':
                                    echo '<span class="badge bg-danger">Belum Dibayar</span>';
                                    break;
                                case 'Dibayar Sebagian':
                                    echo '<span class="badge bg-warning">Dibayar Sebagian</span>';
                                    break;
                                case 'Lunas':
                                    echo '<span class="badge bg-success">Lunas</span>';
                                    break;
                                default:
                                    echo '<span class="badge bg-secondary">Tidak Diketahui</span>';
                            }
                            ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" style="text-align:center;">Tidak ada data penyewaan ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        &copy; <?= date("Y") ?> MitraTeknoNiaga. Semua Hak Dilindungi.
    </div>
</body>
</html>
<?php
$html = ob_get_clean();

$mpdf = new \Mpdf\Mpdf([
    'format' => 'A4',
    'margin_left' => 15,
    'margin_right' => 15,
    'margin_top' => 15,
    'margin_bottom' => 15
]);

$mpdf->WriteHTML($html);
$mpdf->Output('laporan_penyewaan.pdf', 'I'); // 'I' untuk tampilkan di browser
?>

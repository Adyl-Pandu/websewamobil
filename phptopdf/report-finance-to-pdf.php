<?php
require_once "../vendor/autoload.php";
require_once "../php/koneksi.php";

// Tangkap filter tanggal
$start_date = isset($_GET['start_date']) ? mysqli_real_escape_string($conn, $_GET['start_date']) : '';
$end_date = isset($_GET['end_date']) ? mysqli_real_escape_string($conn, $_GET['end_date']) : '';

// Query data
$query = "
    SELECT p.id, pelanggan.nama AS nama_pelanggan, mobil.model AS nama_mobil, 
           p.tanggal_mulai, p.tanggal_selesai, (p.harga_sewa * p.durasi) AS total_biaya
    FROM penyewaan p
    JOIN pelanggan ON p.id_pelanggan = pelanggan.id_pelanggan
    JOIN mobil ON p.id_mobil = mobil.id_mobil
    WHERE 1=1
";
if (!empty($start_date)) {
    $query .= " AND p.tanggal_mulai >= '$start_date'";
}
if (!empty($end_date)) {
    $query .= " AND p.tanggal_selesai <= '$end_date'";
}
$query .= " ORDER BY p.tanggal_mulai ASC";

$result = mysqli_query($conn, $query) or die("Error query: " . mysqli_error($conn));

ob_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Penyewaan Mobil</title>
  <style>
    body { font-family: Arial, sans-serif; font-size: 11pt; color: #333; }
    .header { text-align: center; margin-bottom: 20px; }
    .header h1 { color: #007bff; margin: 0; font-size: 18pt; }
    .header p { margin: 0; font-size: 10pt; color: #666; }
    .report-title {
      text-align: center;
      font-size: 14pt;
      font-weight: bold;
      color: #007bff;
      margin: 20px 0;
      border-bottom: 2px solid #007bff;
      display: inline-block;
      padding-bottom: 5px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      font-size: 10pt;
    }
    th, td {
      border: 1px solid #bbb;
      padding: 8px;
    }
    th {
      background-color: #007bff;
      color: #fff;
      text-align: center;
    }
    td {
      background-color: #f9f9f9;
    }
    tr:nth-child(even) td {
      background-color: #f2f2f2;
    }
    .footer {
      text-align: center;
      font-size: 9pt;
      color: #777;
      margin-top: 30px;
    }
  </style>
</head>
<body>

  <div class="header">
    <h1>Sewa Mobil Tanggerang</h1>
    <p>Jl. Mawar No. 13, Kota Tanggerang</p>
    <p>Telp: (021) 55815156885 | Email: MOBILSEWA@gmail.com</p>
  </div>

  <div class="report-title">LAPORAN PENYEWAAN MOBIL</div>

  <table>
    <thead>
      <tr>
        <th style="width: 5%;">No</th>
        <th style="width: 20%;">Nama Penyewa</th>
        <th style="width: 20%;">Mobil</th>
        <th style="width: 15%;">Tanggal Sewa</th>
        <th style="width: 15%;">Tanggal Kembali</th>
        <th style="width: 15%;">Total Biaya</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = 1;
      $total_semua = 0;
      if (mysqli_num_rows($result) > 0):
        while ($row = mysqli_fetch_assoc($result)):
          $total_semua += $row['total_biaya'];
      ?>
        <tr>
          <td style="text-align: center;"><?= $no++ ?></td>
          <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
          <td><?= htmlspecialchars($row['nama_mobil']) ?></td>
          <td><?= date('d M Y', strtotime($row['tanggal_mulai'])) ?></td>
          <td><?= date('d M Y', strtotime($row['tanggal_selesai'])) ?></td>
          <td style="text-align: right;">Rp <?= number_format($row['total_biaya'], 0, ',', '.') ?></td>
        </tr>
      <?php endwhile; ?>
      <tr>
        <td colspan="5" style="text-align: right;"><strong>Total Pendapatan:</strong></td>
        <td style="text-align: right;"><strong>Rp <?= number_format($total_semua, 0, ',', '.') ?></strong></td>
      </tr>
      <?php else: ?>
        <tr>
          <td colspan="6" style="text-align: center;">Tidak ada data untuk periode ini.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

  <div class="footer">
    Dicetak pada: <?= date('d M Y, H:i') ?><br>
    &copy; <?= date("Y") ?> Sewa Mobil Tanggerang. Semua Hak Dilindungi.
  </div>

</body>
</html>

<?php
$html = ob_get_clean();

// Inisialisasi mPDF
$mpdf = new \Mpdf\Mpdf([
  'format' => 'A4',
  'margin_left' => 15,
  'margin_right' => 15,
  'margin_top' => 15,
  'margin_bottom' => 15
]);

$mpdf->WriteHTML($html);
$mpdf->Output('laporan_penyewaan.pdf', 'I');

mysqli_close($conn);
?>

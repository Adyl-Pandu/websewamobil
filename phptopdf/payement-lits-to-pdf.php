<?php
require_once "../vendor/autoload.php";
require_once "../php/koneksi.php";


$id = $_GET['id'] ?? null;

if (!$id) {
  die("ID penyewaan tidak ditemukan!");
}

$query = "
  SELECT p.*, m.model AS nama_mobil, c.nama AS nama_pelanggan
  FROM penyewaan p
  JOIN mobil m ON p.id_mobil = m.id_mobil
  JOIN pelanggan c ON p.id_pelanggan = c.id_pelanggan
  WHERE p.id = '$id'
";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if (!$row) {
  die("Data tidak ditemukan!");
}

ob_start();
?>

<html>
<head>
  <style>
    body { font-family: Arial, sans-serif; font-size: 12pt; color: #333; }
    .header { text-align: center; margin-bottom: 20px; }
    .header h1 { color: #007bff; margin: 0; }
    .header p { margin: 0; font-size: 10pt; color: #666; }
    .logo { width: 80px; height: auto; margin-bottom: 10px; }
    
    .resi-title {
      text-align: center;
      font-size: 16pt;
      font-weight: bold;
      color: #007bff;
      margin: 20px 0;
      border-bottom: 2px solid #007bff;
      display: inline-block;
      padding-bottom: 5px;
    }

    .details {
      width: 100%;
      margin-top: 10px;
      border-collapse: collapse;
    }
    .details th {
      text-align: left;
      width: 30%;
      color: #555;
      padding: 6px;
    }
    .details td {
      padding: 6px;
    }
    .highlight {
      background-color: #e9f5ff;
    }

    .footer {
      text-align: center;
      font-size: 10pt;
      color: #777;
      margin-top: 30px;
    }
  </style>
</head>
<body>

  <div class="header">
    <!-- Logo perusahaan (opsional) -->
    <img src="../images/logo_mobil_biru.png" alt="Logo" class="logo">
    <h1>Sewa Mobil Tanggerang</h1>
    <p>Jl. Mawar No. 13, Kota Tanggerang</p>
    <p>Telp: (021) 55815156885 | Email: MOBILSEWA@gmail.com</p>
  </div>

  <div class="resi-title">BUKTI PEMBAYARAN SEWA MOBIL</div>

  <table class="details">
    <tr>
      <th>No. Resi</th>
      <td><?= $row['id'] ?></td>
    </tr>
    <tr class="highlight">
      <th>Nama Pelanggan</th>
      <td><?= $row['nama_pelanggan'] ?></td>
    </tr>
    <tr>
      <th>Mobil</th>
      <td><?= $row['nama_mobil'] ?></td>
    </tr>
    <tr>
      <th>Tanggal Mulai</th>
      <td><?= $row['tanggal_mulai'] ?></td>
    </tr>
    <tr>
      <th>Durasi</th>
      <td><?= $row['durasi'] ?> hari</td>
    </tr>
    <tr>
      <th>Harga Sewa</th>
      <td>Rp <?= number_format($row['harga_sewa'], 2, ',', '.') ?></td>
    </tr>
    <tr class="highlight">
      <th>Total Biaya</th>
      <td><strong>Rp <?= number_format($row['total_biaya'], 2, ',', '.') ?></strong></td>
    </tr>
    <tr>
      <th>Status Pembayaran</th>
      <td><?= ucfirst($row['status_pembayaran']) ?></td>
    </tr>
    <tr>
      <th>Tanggal Dibuat</th>
      <td><?= $row['dibuat_pada'] ?></td>
    </tr>
  </table>

  <div class="footer">
    <p>Terima kasih atas kepercayaan Anda.</p>
    <p>Dokumen ini sah dan dicetak secara otomatis oleh sistem.</p>
  </div>

</body>
</html>

<?php
$content = ob_get_clean();

$mpdf = new \Mpdf\Mpdf([
    'format' => 'A4',
    'margin_left' => 15,
    'margin_right' => 15,
    'margin_top' => 15,
    'margin_bottom' => 15
]);

$mpdf->WriteHTML($content);
$mpdf->Output('resi_penyewaan_'.$row['id'].'.pdf', 'I');

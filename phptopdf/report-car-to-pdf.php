<?php
require_once "../vendor/autoload.php";
require_once "../php/koneksi.php";

// Query data mobil
$query = "SELECT merek, model, plat_nomor, tahun, warna, kategori, status FROM mobil";
$result = mysqli_query($conn, $query);

// Buffering output
ob_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Data Mobil</title>
<style>
  body { font-family: Arial, sans-serif; font-size: 12pt; color: #333; }
  .header { text-align: center; margin-bottom: 20px; }
  .header h1 { color: #007bff; margin: 0; }
  .header p { margin: 0; font-size: 10pt; color: #666; }
  
  .laporan-title {
    text-align: center;
    font-size: 16pt;
    font-weight: bold;
    color: #007bff;
    margin: 20px 0;
    border-bottom: 2px solid #007bff;
    display: inline-block;
    padding-bottom: 5px;
  }
  
  table { width: 100%; border-collapse: collapse; margin-top: 10px; }
  th, td { border: 1px solid #333; padding: 6px; text-align: center; }
  th { background-color: #007bff; color: #fff; }
  
  .highlight { background-color: #e9f5ff; }
  
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
  <h1>Sewa Mobil Tanggerang</h1>
  <p>Jl. Mawar No. 13, Kota Tanggerang</p>
  <p>Telp: (021) 55815156885 | Email: MOBILSEWA@gmail.com</p>
</div>

<div class="laporan-title">LAPORAN DATA MOBIL</div>

<table>
  <thead>
    <tr>
      <th>Merk</th>
      <th>Model</th>
      <th>Plat Nomor</th>
      <th>Tahun</th>
      <th>Warna</th>
      <th>Kategori</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <?php
    if ($result && mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $statusClass = ($row['status'] === 'Tersedia') ? 'highlight' : '';
        echo "<tr class='$statusClass'>
                <td>".htmlspecialchars($row['merek'])."</td>
                <td>".htmlspecialchars($row['model'])."</td>
                <td>".htmlspecialchars($row['plat_nomor'])."</td>
                <td>".htmlspecialchars($row['tahun'])."</td>
                <td>".htmlspecialchars($row['warna'])."</td>
                <td>".htmlspecialchars($row['kategori'])."</td>
                <td>".htmlspecialchars($row['status'])."</td>
              </tr>";
      }
    } else {
      echo "<tr><td colspan='7'>Data tidak tersedia.</td></tr>";
    }
    ?>
  </tbody>
</table>

<div class="footer">
  <p>Dokumen ini dicetak secara otomatis oleh sistem.</p>
</div>

</body>
</html>
<?php
$html = ob_get_clean();

// Inisialisasi mPDF
$mpdf = new \Mpdf\Mpdf([
  'format' => 'A4-L', // Landscape
  'margin_left' => 15,
  'margin_right' => 15,
  'margin_top' => 15,
  'margin_bottom' => 15
]);

$mpdf->WriteHTML($html);
$mpdf->Output('Laporan-Mobil.pdf', 'I');
?>

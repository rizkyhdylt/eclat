<?php
$root = $_SERVER['DOCUMENT_ROOT'] . "/nada/config/"; 
include $root . "config.php";

// 1. Ambil data dataset yang sama dengan halaman analisis
$query = "SELECT p.status, s.nama_penyewa, k.tipe_kamar 
          FROM pembayaran p
          JOIN penyewa s ON p.id_penyewa = s.id_penyewa
          JOIN kontrakan k ON p.id_kontrakan = k.id_kontrakan";
$result = mysqli_query($conn, $query);

$pola_terlambat = [];
$total_transaksi = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $total_transaksi++;
    if ($row['status'] != 'Lunas') {
        $key = $row['nama_penyewa'] . " (" . $row['tipe_kamar'] . ")";
        if (!isset($pola_terlambat[$key])) { $pola_terlambat[$key] = 0; }
        $pola_terlambat[$key]++;
    }
}
arsort($pola_terlambat);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Analisis ECLAT - SI-KONTRAK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Times New Roman', Times, serif; }
        .header-laporan { border-bottom: 3px double #000; margin-bottom: 20px; padding-bottom: 10px; }
        @media print {
            .no-print { display: none; }
            @page { margin: 2cm; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container mt-4">
        <div class="text-center header-laporan">
            <h3 class="mb-0 fw-bold">SI-KONTRAK MANAGEMENT</h3>
            <p class="mb-0">Laporan Hasil Analisis Pola Keterlambatan Pembayaran (Metode ECLAT)</p>
            <small class="text-muted">Dicetak pada: <?= date('d/m/Y H:i') ?></small>
        </div>

        <table class="table table-bordered align-middle">
            <thead class="table-light text-center">
                <tr>
                    <th width="50">No</th>
                    <th>Itemset (Penyewa & Unit)</th>
                    <th>Frekuensi Terlambat</th>
                    <th>Nilai Support</th>
                    <th>Rekomendasi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1; 
                foreach ($pola_terlambat as $itemset => $frekuensi) : 
                    $support = ($frekuensi / $total_transaksi) * 100;
                ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><strong><?= $itemset ?></strong></td>
                    <td class="text-center"><?= $frekuensi ?> Kali</td>
                    <td class="text-center"><?= number_format($support, 1) ?>%</td>
                    <td class="text-center">
                        <?= ($support >= 50) ? 'Teguran Keras' : 'Pantau Berkala' ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="row mt-5">
            <div class="col-8"></div>
            <div class="col-4 text-center">
                <p>Mengetahui,</p>
                <br><br><br>
                <p class="fw-bold">( Pemilik Kontrakan )</p>
            </div>
        </div>

        <div class="mt-4 no-print text-center">
            <button onclick="window.close()" class="btn btn-secondary btn-sm">Tutup Halaman</button>
        </div>
    </div>
</body>
</html>
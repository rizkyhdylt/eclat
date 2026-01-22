<?php
// Sesuaikan path config Anda
include "../config/config.php"; 

// 1. Ambil data transaksi (Gunakan logika yang sama persis dengan halaman analisis)
$query = "SELECT p.status, s.nama_penyewa, k.tipe_kamar 
          FROM pembayaran p
          JOIN penyewa s ON p.id_penyewa = s.id_penyewa
          JOIN kontrakan k ON p.id_kontrakan = k.id_kontrakan";
$result = mysqli_query($conn, $query);

$pola_terlambat = [];
$total_transaksi = 0;

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $total_transaksi++;
        // ECLAT Logic: Menghitung kemunculan status Terlambat
        if ($row['status'] == 'Terlambat') {
            $key = $row['nama_penyewa'] . " [" . $row['tipe_kamar'] . "]";
            if (!isset($pola_terlambat[$key])) {
                $pola_terlambat[$key] = 0;
            }
            $pola_terlambat[$key]++;
        }
    }
    arsort($pola_terlambat); // Urutkan dari yang paling sering terlambat
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Analisis ECLAT - SI-KONTRAK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            font-family: 'Times New Roman', serif; 
            background-color: white;
            color: black;
        }
        .header-laporan { 
            border-bottom: 4px double #000; 
            margin-bottom: 30px; 
            padding-bottom: 10px; 
        }
        .table-bordered th, .table-bordered td { 
            border: 1px solid #000 !important; 
        }
        .badge-status {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.8rem;
        }
        @media print {
            .no-print { display: none !important; }
            @page { size: portrait; margin: 2cm; }
            body { -webkit-print-color-adjust: exact; }
            .table-light { background-color: #f8f9fa !important; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container mt-5">
        <div class="text-center header-laporan">
            <h2 class="mb-0 fw-bold">SI-KONTRAK MANAGEMENT</h2>
            <p class="mb-1">Sistem Informasi Pengelolaan Kontrakan Berbasis Data Mining</p>
            <p class="small mb-2">Laporan Hasil Transformasi Data Vertikal (Metode ECLAT)</p>
        </div>

        <div class="mb-4">
            <h5 class="text-center fw-bold text-uppercase">Ringkasan Analisis Pola Keterlambatan</h5>
            <div class="row mt-3">
                <div class="col-6">
                    <table class="table table-sm table-borderless">
                        <tr><td>Total Dataset</td><td>: <?= $total_transaksi ?> Transaksi</td></tr>
                        <tr><td>Itemset Terdeteksi</td><td>: <?= count($pola_terlambat) ?> Pola</td></tr>
                    </table>
                </div>
                <div class="col-6 text-end">
                    <p class="small">Tanggal Cetak: <?= date('d F Y H:i') ?></p>
                </div>
            </div>
        </div>

        <table class="table table-bordered align-middle">
            <thead class="table-light text-center">
                <tr>
                    <th width="5%">Rank</th>
                    <th width="45%">Itemset (Penyewa & Unit)</th>
                    <th width="15%">Frekuensi</th>
                    <th width="15%">Support (%)</th>
                    <th width="20%">Action Plan</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1; 
                if (count($pola_terlambat) > 0) :
                    foreach ($pola_terlambat as $itemset => $frekuensi) : 
                        $support = ($frekuensi / $total_transaksi) * 100;
                ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><strong><?= htmlspecialchars($itemset) ?></strong></td>
                    <td class="text-center"><?= $frekuensi ?> Kali</td>
                    <td class="text-center fw-bold"><?= number_format($support, 1) ?>%</td>
                    <td class="text-center">
                        <?php 
                            if ($support >= 50) echo '<span class="badge-status text-danger">Teguran Keras (SP)</span>';
                            elseif ($support >= 25) echo '<span class="badge-status text-warning">Peringatan Lisan</span>';
                            else echo '<span class="badge-status text-muted">Pantau Berkala</span>';
                        ?>
                    </td>
                </tr>
                <?php endforeach; else : ?>
                <tr>
                    <td colspan="5" class="text-center py-4 italic text-muted">-- Tidak ada pola keterlambatan yang ditemukan --</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="row mt-5">
            <div class="col-7">
                <div class="small p-2 border" style="width: fit-content; border-style: dashed !important;">
                    <strong>Keterangan:</strong><br>
                    Support = (Frekuensi / Total Dataset) * 100<br>
                    Analisis ini digunakan untuk penentuan kebijakan perpanjangan sewa.
                </div>
            </div>
            <div class="col-5 text-center">
                <p>Tangerang Selatan, <?= date('d F Y') ?></p>
                <p class="fw-bold mb-5">Administrator Sistem,</p>
                <div class="mt-5">
                    <hr class="mx-auto w-75" style="border: 1px solid #000; opacity: 1;">
                    <p class="fw-bold">Manajemen SI-KONTRAK</p>
                </div>
            </div>
        </div>

        <div class="mt-5 no-print text-center border-top pt-3">
            <button onclick="window.print()" class="btn btn-primary me-2">Cetak Ulang</button>
            <button onclick="window.close()" class="btn btn-secondary">Tutup Halaman</button>
        </div>
    </div>
</body>
</html>
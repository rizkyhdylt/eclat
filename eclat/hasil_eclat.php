<?php
/**
 * Halaman Hasil Analisis ECLAT
 * Analisis ini bertujuan untuk melihat pola pembayaran penyewa.
 */

// 1. Ambil data transaksi yang statusnya 'Terlambat' atau pola tertentu
$query = "SELECT p.bulan, p.tahun, p.status, s.nama_penyewa, k.tipe_kamar 
          FROM pembayaran p
          JOIN penyewa s ON p.id_penyewa = s.id_penyewa
          JOIN kontrakan k ON p.id_kontrakan = k.id_kontrakan
          ORDER BY p.tahun DESC, p.bulan DESC";
$result = mysqli_query($conn, $query);

// Placeholder untuk perhitungan ECLAT (Logika sederhana frekuensi pola)
$pola_terlambat = [];
$total_transaksi = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $total_transaksi++;
    if ($row['status'] != 'Lunas') {
        $key = $row['nama_penyewa'] . " (" . $row['tipe_kamar'] . ")";
        if (!isset($pola_terlambat[$key])) {
            $pola_terlambat[$key] = 0;
        }
        $pola_terlambat[$key]++;
    }
}
arsort($pola_terlambat); // Urutkan dari yang paling sering terlambat
?>

<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-right">
    <div>
        <h4 class="fw-bold mb-1 text-dark">Hasil Analisis ECLAT</h4>
        <p class="text-muted small mb-0">Identifikasi pola keterlambatan pembayaran penyewa menggunakan metode Itemset Mining.</p>
    </div>
    <a href="eclat/cetak_eclat.php" target="_blank" class="btn btn-outline-secondary px-4 shadow-sm rounded-pill bg-white">
    <i class="fa-solid fa-print me-2"></i>Cetak Hasil
</a>
</div>

<div class="row mb-4" data-aos="fade-up">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3 mb-3" style="border-radius: 15px; border-left: 5px solid #3b82f6 !important;">
            <div class="d-flex align-items-center">
                <div class="icon-box bg-primary bg-opacity-10 text-primary me-3 p-3 rounded-circle">
                    <i class="fa-solid fa-database"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0 small uppercase fw-bold">Total Dataset</h6>
                    <h4 class="fw-bold mb-0"><?= $total_transaksi ?> <span class="small text-muted fw-normal" style="font-size: 0.8rem;">Transaksi</span></h4>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3 mb-3" style="border-radius: 15px; border-left: 5px solid #ef4444 !important;">
            <div class="d-flex align-items-center">
                <div class="icon-box bg-danger bg-opacity-10 text-danger me-3 p-3 rounded-circle">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0 small uppercase fw-bold">Itemset Terdeteksi</h6>
                    <h4 class="fw-bold mb-0"><?= count($pola_terlambat) ?> <span class="small text-muted fw-normal" style="font-size: 0.8rem;">Pola</span></h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 15px;" data-aos="fade-up" data-aos-delay="100">
    <div class="card-header bg-white py-3 border-0" style="border-radius: 15px 15px 0 0;">
        <h6 class="m-0 fw-bold text-dark"><i class="fa-solid fa-magnifying-glass-chart me-2 text-primary"></i>Tabel Support (Frekuensi Muncul)</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted">No</th>
                        <th class="py-3 text-muted">Itemset (Penyewa & Unit)</th>
                        <th class="py-3 text-muted">Frekuensi Terlambat</th>
                        <th class="py-3 text-muted">Nilai Support</th>
                        <th class="py-3 text-muted text-center">Rekomendasi</th>
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
                        <td class="ps-4 text-muted"><?= $no++ ?></td>
                        <td><span class="fw-bold text-dark"><?= $itemset ?></span></td>
                        <td><span class="badge bg-danger bg-opacity-10 text-danger px-3"><?= $frekuensi ?> Kali</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                    <div class="progress-bar bg-primary" style="width: <?= $support ?>%"></div>
                                </div>
                                <span class="small fw-bold"><?= number_format($support, 1) ?>%</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <?php if ($support >= 50) : ?>
                                <span class="badge bg-danger rounded-pill px-3">Teguran Keras</span>
                            <?php else : ?>
                                <span class="badge bg-warning text-dark rounded-pill px-3">Pantau</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; else : ?>
                    <tr>
                        <td colspan="5" class="text-center p-5 text-muted">
                            <i class="fa-solid fa-shield-check d-block fs-1 mb-3 text-success opacity-50"></i>
                            Tidak ditemukan pola keterlambatan yang signifikan. Semua pembayaran lancar!
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .icon-box { width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; }
    .progress-bar { border-radius: 10px; }
    .table thead th { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; }
</style>
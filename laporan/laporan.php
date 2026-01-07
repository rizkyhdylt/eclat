<?php
// 1. Ambil filter dari URL. Default ke nama bulan saat ini dalam Bahasa Indonesia.
$bulan_sekarang = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', 
    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', 
    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
];

$filter_bulan = isset($_GET['bulan']) ? $_GET['bulan'] : $bulan_sekarang[date('m')];
$filter_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

// 2. Query Laporan dengan JOIN yang lebih lengkap agar tidak perlu query di dalam loop (mengurangi beban database)
$query = "SELECT p.*, s.nama_penyewa, k.tipe_kamar, k.harga 
          FROM pembayaran p
          JOIN penyewa s ON p.id_penyewa = s.id_penyewa
          JOIN kontrakan k ON p.id_kontrakan = k.id_kontrakan
          WHERE p.bulan = '$filter_bulan' AND p.tahun = '$filter_tahun'
          ORDER BY p.tanggal_bayar ASC";

$result = mysqli_query($conn, $query);

$total_pendapatan = 0;
?>

<div class="d-flex justify-content-between align-items-center mb-4 no-print" data-aos="fade-right">
    <div>
        <h4 class="fw-bold mb-1 text-dark">Laporan Pembayaran</h4>
        <p class="text-muted small mb-0">Periode: <strong><?= $filter_bulan ?> <?= $filter_tahun ?></strong></p>
    </div>
    <button onclick="window.print()" class="btn btn-dark px-4 shadow-sm rounded-pill">
        <i class="fa-solid fa-print me-2"></i>Cetak Laporan
    </button>
</div>

<div class="card border-0 shadow-sm mb-4 no-print" style="border-radius: 15px;" data-aos="fade-up">
    <div class="card-body">
        <form action="index.php" method="GET" class="row g-3 align-items-end">
            <input type="hidden" name="page" value="laporan">
            <div class="col-md-4">
                <label class="form-label small fw-bold">Pilih Bulan</label>
                <select name="bulan" class="form-select border-0 bg-light">
                    <?php
                    foreach ($bulan_sekarang as $b) {
                        $selected = ($filter_bulan == $b) ? 'selected' : '';
                        echo "<option value='$b' $selected>$b</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold">Pilih Tahun</label>
                <select name="tahun" class="form-select border-0 bg-light">
                    <?php
                    for ($i = date('Y'); $i >= 2022; $i--) {
                        $selected = ($filter_tahun == $i) ? 'selected' : '';
                        echo "<option value='$i' $selected>$i</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100 shadow-sm">
                    <i class="fa-solid fa-magnifying-glass me-2"></i>Tampilkan Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 15px;" data-aos="fade-up">
    <div class="card-body p-4">
        <div class="d-none d-print-block text-center mb-4">
            <h3 class="fw-bold">LAPORAN PEMBAYARAN KONTRAKAN</h3>
            <p>Periode: <?= $filter_bulan ?> <?= $filter_tahun ?></p>
            <hr>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Tgl Bayar</th>
                        <th>Nama Penyewa</th>
                        <th>Unit</th>
                        <th>Status</th>
                        <th class="text-end">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1; 
                    if (mysqli_num_rows($result) > 0) :
                        while ($row = mysqli_fetch_assoc($result)) : 
                            $jumlah = $row['harga'];
                            if($row['status'] == 'Lunas') $total_pendapatan += $jumlah;
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="text-center"><?= ($row['tanggal_bayar'] != '0000-00-00' && $row['tanggal_bayar'] != '') ? date('d/m/Y', strtotime($row['tanggal_bayar'])) : '-' ?></td>
                        <td class="fw-bold"><?= htmlspecialchars($row['nama_penyewa']) ?></td>
                        <td class="text-center small"><?= $row['tipe_kamar'] ?></td>
                        <td class="text-center">
                            <span class="badge bg-<?= $row['status'] == 'Lunas' ? 'success' : 'danger' ?> bg-opacity-10 text-<?= $row['status'] == 'Lunas' ? 'success' : 'danger' ?> px-3">
                                <?= $row['status'] ?>
                            </span>
                        </td>
                        <td class="text-end fw-bold">Rp <?= number_format($jumlah, 0, ',', '.') ?></td>
                    </tr>
                    <?php endwhile; ?>
                    <tr class="table-dark fw-bold">
                        <td colspan="5" class="text-end py-3">TOTAL PENDAPATAN (LUNAS)</td>
                        <td class="text-end pe-3 fs-5">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></td>
                    </tr>
                    <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center p-5 text-muted">Tidak ada data transaksi untuk periode ini.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="d-none d-print-block mt-5">
            <div class="row text-center">
                <div class="col-8"></div>
                <div class="col-4">
                    <p>Dicetak pada: <?= date('d/m/Y') ?></p>
                    <br><br><br>
                    <p class="fw-bold">( Pemilik Kontrakan )</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        /* Sembunyikan elemen yang ditandai .no-print */
        .no-print, .sidebar, .top-nav, .btn, form, nav {
            display: none !important;
        }
        .main-content {
            margin: 0 !important;
            padding: 0 !important;
        }
        .card {
            border: none !important;
        }
        .badge {
            color: black !important; /* Agar teks status terlihat jelas di printer hitam putih */
            border: 1px solid #ccc;
        }
    }
</style>
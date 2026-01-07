<?php
// Query disesuaikan dengan struktur tabel pembayaran Anda
// Kita hubungkan ke tabel penyewa (untuk nama) dan kontrakan (untuk tipe kamar)
$query = "SELECT pembayaran.*, penyewa.nama_penyewa, kontrakan.tipe_kamar 
          FROM pembayaran 
          LEFT JOIN penyewa ON pembayaran.id_penyewa = penyewa.id_penyewa 
          LEFT JOIN kontrakan ON pembayaran.id_kontrakan = kontrakan.id_kontrakan 
          ORDER BY pembayaran.id_pembayaran DESC";

$result = mysqli_query($conn, $query);

// Cek jika query gagal
if (!$result) {
    echo "<div class='alert alert-danger mt-3'>
            <strong>Kesalahan Query:</strong> " . mysqli_error($conn) . "
          </div>";
    exit;
}
?>

<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-right">
    <div>
        <h4 class="fw-bold mb-1 text-dark">Riwayat Pembayaran</h4>
        <p class="text-muted small mb-0">Kelola transaksi pembayaran bulanan penghuni.</p>
    </div>
    <a href="index.php?page=pembayaran_form" class="btn btn-primary px-4 shadow-sm rounded-pill">
        <i class="fa-solid fa-plus me-2"></i>Tambah Transaksi
    </a>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 15px;" data-aos="fade-up">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted">No</th>
                        <th class="py-3 text-muted">Penyewa & Unit</th>
                        <th class="py-3 text-muted">Periode</th>
                        <th class="py-3 text-muted">Jatuh Tempo</th>
                        <th class="py-3 text-muted">Tgl Bayar</th>
                        <th class="py-3 text-muted text-center">Status</th>
                        <th class="text-center pe-4 text-muted">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1; 
                    if (mysqli_num_rows($result) > 0) :
                        while ($row = mysqli_fetch_assoc($result)) : 
                            // Penentuan warna badge status berdasarkan database Anda
                            $status = $row['status'];
                            $bg_class = ($status == 'Lunas') ? 'success' : 'danger';
                    ?>
                    <tr>
                        <td class="ps-4 text-muted"><?= $no++ ?></td>
                        <td>
                            <div class="fw-bold text-dark"><?= htmlspecialchars($row['nama_penyewa'] ?? 'Tidak Dikenal') ?></div>
                            <div class="badge bg-secondary bg-opacity-10 text-secondary small fw-normal">
                                <i class="fa-solid fa-bed me-1"></i> <?= htmlspecialchars($row['tipe_kamar'] ?? 'N/A') ?>
                            </div>
                        </td>
                        <td>
                            <span class="text-dark small fw-semibold"><?= $row['bulan'] ?> <?= $row['tahun'] ?></span>
                        </td>
                        <td class="small text-muted">
                            <?= date('d/m/Y', strtotime($row['jatuh_tempo'])) ?>
                        </td>
                        <td class="small text-muted">
                            <?= ($row['tanggal_bayar'] != '0000-00-00') ? date('d/m/Y', strtotime($row['tanggal_bayar'])) : '-' ?>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-<?= $bg_class ?> bg-opacity-10 text-<?= $bg_class ?> px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.75rem;">
                                <?= $status ?>
                            </span>
                        </td>
                        <td class="text-center pe-4">
                            <div class="btn-group">
                                <a href="index.php?page=pembayaran_edit&id=<?= $row['id_pembayaran'] ?>" class="btn btn-sm btn-light text-warning hover-scale" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <a href="transaksi/pembayaran_proses.php?hapus=<?= $row['id_pembayaran'] ?>" class="btn btn-sm btn-light text-danger hover-scale" onclick="return confirm('Hapus transaksi?')" title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr>
                        <td colspan="7" class="text-center p-5 text-muted">
                            <i class="fa-solid fa-wallet d-block fs-1 mb-3 opacity-25"></i>
                            Belum ada riwayat transaksi.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .hover-scale { transition: transform 0.2s; border-radius: 8px; }
    .hover-scale:hover { transform: scale(1.1); background-color: white !important; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .badge { letter-spacing: 0.5px; }
</style>
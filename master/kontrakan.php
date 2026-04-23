<?php
// 1. QUERY MENGAMBIL DATA DENGAN JOIN PENYEWA
$query = "SELECT kontrakan.*, penyewa.nama_penyewa 
          FROM kontrakan 
          LEFT JOIN penyewa ON kontrakan.id_penyewa = penyewa.id_penyewa 
          ORDER BY id_kontrakan DESC";
$data = mysqli_query($conn, $query);

if (!$data) {
    echo "<div class='alert alert-danger'>Kesalahan Query: " . mysqli_error($conn) . "</div>";
}

// 2. LOGIKA STATISTIK
$total_unit = mysqli_num_rows($data);
$unit_terisi = 0;
$unit_kosong = 0;
$total_potensi_harga = 0;

while ($temp = mysqli_fetch_assoc($data)) {
    $total_potensi_harga += $temp['harga'];
    if (!empty($temp['id_penyewa'])) {
        $unit_terisi++;
    } else {
        $unit_kosong++;
    }
}
mysqli_data_seek($data, 0); 
?>

<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
        <div>
            <h3 class="fw-bold m-0 text-dark">
                <i class="fa-solid fa-building text-primary me-2"></i>Manajemen Unit
            </h3>
            <p class="text-muted small mb-0 mt-1">Status hunian dan pengelolaan harga sewa real-time</p>
        </div>
        <a href="index.php?page=kontrakan_form" class="btn btn-primary btn-modern px-4 shadow">
            <i class="fa-solid fa-plus me-2"></i>Tambah Unit Baru
        </a>
    </div>

    <div class="row g-3 mb-4" data-aos="fade-up">
        <div class="col-md-3">
            <div class="stats-card bg-gradient-primary">
                <div class="stats-icon"><i class="fa-solid fa-house"></i></div>
                <div class="stats-content">
                    <h3 class="stats-number"><?= $total_unit ?></h3>
                    <p class="stats-label">Total Unit</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #f87171, #dc2626);">
                <div class="stats-icon"><i class="fa-solid fa-user-check"></i></div>
                <div class="stats-content">
                    <h3 class="stats-number"><?= $unit_terisi ?></h3>
                    <p class="stats-label">Unit Terisi</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #34d399, #059669);">
                <div class="stats-icon"><i class="fa-solid fa-door-open"></i></div>
                <div class="stats-content">
                    <h3 class="stats-number"><?= $unit_kosong ?></h3>
                    <p class="stats-label">Unit Kosong</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card bg-gradient-info">
                <div class="stats-icon"><i class="fa-solid fa-money-bill-trend-up"></i></div>
                <div class="stats-content">
                    <h3 class="stats-number">Rp <?= number_format($total_potensi_harga, 0, ',', '.') ?></h3>
                    <p class="stats-label">Potensi Omzet</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-lg modern-card" data-aos="fade-up">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="m-0 fw-bold text-dark"><i class="fa-solid fa-list me-2 text-primary"></i>Daftar Kontrakan</h5>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 modern-table">
                    <thead>
                        <tr>
                            <th class="ps-4 py-3 text-muted fw-semibold">No</th>
                            <th class="py-3 text-muted fw-semibold">Unit & Penghuni</th>
                            <th class="py-3 text-muted fw-semibold">Jatuh Tempo</th>
                            <th class="py-3 text-muted fw-semibold">Harga</th>
                            <th class="py-3 text-muted fw-semibold text-center">Status</th>
                            <th class="text-center pe-4 py-3 text-muted fw-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1; 
                        if ($total_unit > 0) :
                            while ($row = mysqli_fetch_assoc($data)) : 
                                $is_occupied = !empty($row['id_penyewa']);
                        ?>
                        <tr class="table-row-hover">
                            <td class="ps-4">
                                <div class="number-badge"><?= $no++ ?></div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="unit-icon-wrapper">
                                        <i class="fa-solid <?= $is_occupied ? 'fa-house-lock' : 'fa-house-chimney' ?>"></i>
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark d-block"><?= htmlspecialchars($row['tipe_kamar']) ?></span>
                                        <small class="text-muted">
                                            <i class="fa-solid fa-user me-1"></i>
                                            <?= $is_occupied ? 'Penyewa: <b>'.$row['nama_penyewa'].'</b>' : 'Kamar Kosong' ?>
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark">Tgl <?= $row['jatuh_tempo'] ?></span>
                                    <small class="text-muted" style="font-size: 11px;">Setiap Bulan</small>
                                </div>
                            </td>
                            <td>
                                <div class="price-tag">
                                    <span class="price-amount">Rp <?= number_format($row['harga'], 0, ',', '.') ?></span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge <?= $is_occupied ? 'bg-danger' : 'bg-success' ?> px-3 py-2" style="border-radius: 8px;">
                                    <?= $is_occupied ? 'TERISI' : 'KOSONG' ?>
                                </span>
                            </td>
                            <td class="text-center pe-4">
                                <div class="action-buttons">
                                    <a href="index.php?page=kontrakan_edit&id=<?= $row['id_kontrakan'] ?>" class="action-btn btn-edit" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="javascript:void(0)" 
                                       class="action-btn btn-delete" 
                                       onclick="hapusUnit('<?= $row['id_kontrakan'] ?>', '<?= htmlspecialchars($row['tipe_kamar']) ?>')"
                                       title="Hapus">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada data unit kontrakan.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS ANDA TETAP SAMA (TIDAK BERUBAH) */
    .btn-modern { border-radius: 12px; font-weight: 600; transition: all 0.3s ease; border: none; }
    .btn-modern:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(13, 110, 253, 0.3) !important; }
    .stats-card { border-radius: 16px; padding: 24px; color: white; display: flex; align-items: center; gap: 20px; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); }
    .stats-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15); }
    .bg-gradient-primary { --gradient-start: #667eea; --gradient-end: #764ba2; background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end)); }
    .bg-gradient-info { --gradient-start: #4facfe; --gradient-end: #00f2fe; background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end)); }
    .stats-icon { width: 60px; height: 60px; background: rgba(255, 255, 255, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 28px; backdrop-filter: blur(10px); }
    .stats-number { font-size: 20px; font-weight: 700; margin: 0; }
    .stats-label { margin: 5px 0 0 0; font-size: 14px; opacity: 0.9; }
    .modern-card { border-radius: 20px; overflow: hidden; background: white; }
    .modern-table thead { background: linear-gradient(to right, #f8fafc, #f1f5f9); }
    .table-row-hover:hover { background: linear-gradient(to right, #f0f9ff, #ffffff); border-left: 3px solid #3b82f6; }
    .number-badge { width: 35px; height: 35px; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 700; }
    .unit-icon-wrapper { width: 50px; height: 50px; background: linear-gradient(135deg, #e0f2fe, #bfdbfe); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px; color: #3b82f6; }
    .price-tag { padding: 8px 16px; background: linear-gradient(135deg, #ecfdf5, #d1fae5); border-radius: 10px; border: 1px solid #a7f3d0; }
    .price-amount { font-weight: 700; color: #059669; }
    .action-buttons { display: flex; gap: 8px; justify-content: center; }
    .action-btn { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; text-decoration: none; border: 2px solid transparent; cursor: pointer; }
    .btn-edit { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #d97706; border-color: #fcd34d; }
    .btn-delete { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #dc2626; border-color: #fca5a5; }
</style>

<script>
function hapusUnit(id, name) {
    if (typeof Swal === 'undefined') {
        // Fallback jika SweetAlert gagal load
        if (confirm("Yakin ingin menghapus unit " + name + "?")) {
            window.location.href = "master/delete_kontrakan.php?id=" + id;
        }
    } else {
        Swal.fire({
            title: 'Hapus Unit?',
            text: "Yakin ingin menghapus unit " + name + "? Tindakan ini permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "master/delete_kontrakan.php?id=" + id;
            }
        });
    }
}

// Menangani Notifikasi Sukses
window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('status') === 'success_hapus' && typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Unit kontrakan telah dihapus.',
            timer: 2000,
            showConfirmButton: false
        });
    }
}
</script>

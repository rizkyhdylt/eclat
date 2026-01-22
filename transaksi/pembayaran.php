<?php
// ==========================================
// 1. LOGIKA HAPUS (Dijalankan di file ini)
// ==========================================
if (isset($_GET['action']) && $_GET['action'] == 'hapus') {
    $id_hapus = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Ambil ID penyewa sebelum hapus untuk redirect balik ke filter yang sama
    $cek_data = mysqli_query($conn, "SELECT id_penyewa FROM pembayaran WHERE id_pembayaran = '$id_hapus'");
    $data_lama = mysqli_fetch_assoc($cek_data);
    $id_balik = $data_lama['id_penyewa'] ?? '';

    $query_hapus = mysqli_query($conn, "DELETE FROM pembayaran WHERE id_pembayaran = '$id_hapus'");

    if ($query_hapus) {
        echo "<script>
                alert('Data pembayaran berhasil dihapus!');
                window.location.href='index.php?page=pembayaran" . ($id_balik ? "&id_penyewa=".$id_balik : "") . "';
              </script>";
        exit;
    }
}

// ==========================================
// 2. LOGIKA TAMPILAN DATA
// ==========================================
// Tangkap ID Penyewa dari URL jika ada
$id_filter = isset($_GET['id_penyewa']) ? mysqli_real_escape_string($conn, $_GET['id_penyewa']) : '';

// Query untuk mendapatkan nama penyewa jika difilter
$nama_header = "Riwayat Pembayaran";
if (!empty($id_filter)) {
    $q_penyewa = mysqli_query($conn, "SELECT nama_penyewa FROM penyewa WHERE id_penyewa = '$id_filter'");
    $d_penyewa = mysqli_fetch_assoc($q_penyewa);
    if ($d_penyewa) {
        $nama_header = "Riwayat: " . $d_penyewa['nama_penyewa'];
    }
}

// Query Utama Pembayaran
$sql = "SELECT p.*, s.nama_penyewa, k.tipe_kamar 
        FROM pembayaran p
        JOIN penyewa s ON p.id_penyewa = s.id_penyewa
        JOIN kontrakan k ON p.id_kontrakan = k.id_kontrakan";

if (!empty($id_filter)) {
    $sql .= " WHERE p.id_penyewa = '$id_filter'";
}
$sql .= " ORDER BY p.tahun DESC, p.bulan DESC";
$query = mysqli_query($conn, $sql);
?>

<!-- Header Section -->
<div class="header-gradient mb-4" data-aos="fade-down">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1 text-white">
                <i class="fa-solid fa-money-bill-transfer me-2"></i>
                <?= $nama_header ?>
            </h4>
            <p class="text-white-50 small mb-0">
                <i class="fa-solid fa-filter me-1"></i>
                <?= $id_filter ? 'Filter aktif - Penyewa spesifik' : 'Menampilkan semua transaksi pembayaran' ?>
            </p>
        </div>
        <div class="d-flex gap-2">
            <?php if($id_filter): ?>
                <a href="index.php?page=pembayaran" class="btn btn-light">
                    <i class="fa-solid fa-rotate-left me-1"></i>Lihat Semua
                </a>
            <?php endif; ?>
            <a href="index.php?page=pembayaran_form<?= $id_filter ? '&id_penyewa='.$id_filter : '' ?>" class="btn btn-success">
                <i class="fa-solid fa-plus me-2"></i>Tambah Transaksi
            </a>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="card border-0 shadow-lg colorful-card" data-aos="fade-up">
    <div class="card-header-gradient">
        <h6 class="mb-0 fw-bold text-white">
            <i class="fa-solid fa-table-list me-2"></i>
            Data Transaksi Pembayaran
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 colorful-table">
                <thead>
                    <tr>
                        <th class="ps-4 py-3">No</th>
                        <th class="py-3">
                            <i class="fa-regular fa-calendar me-1"></i>Periode
                        </th>
                        <th class="py-3">
                            <i class="fa-solid fa-user me-1"></i>Penyewa
                        </th>
                        <th class="py-3">
                            <i class="fa-solid fa-house me-1"></i>Unit
                        </th>
                        <th class="py-3">
                            <i class="fa-solid fa-calendar-xmark me-1"></i>Jatuh Tempo
                        </th>
                        <th class="py-3">
                            <i class="fa-solid fa-calendar-check me-1"></i>Tgl Bayar
                        </th>
                        <th class="py-3 text-center">
                            <i class="fa-solid fa-info-circle me-1"></i>Status
                        </th>
                        <th class="py-3 text-center">
                            <i class="fa-solid fa-gear me-1"></i>Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($query) > 0): ?>
                        <?php 
                        $no = 1;
                        $colors = ['#667eea'];
                        while($row = mysqli_fetch_assoc($query)): 
                            $color = $colors[($no - 1) % count($colors)];
                        ?>
                        <tr class="table-row-animated">
                            <td class="ps-4">
                                <div class="number-badge" style="background: linear-gradient(135deg, <?= $color ?>, <?= $color ?>dd);">
                                    <?= $no++ ?>
                                </div>
                            </td>
                            <td>
                                <div class="period-badge">
                                    <i class="fa-solid fa-calendar-days me-2"></i>
                                    <span class="fw-bold"><?= $row['bulan'] ?></span>
                                    <span class="ms-1"><?= $row['tahun'] ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="tenant-avatar">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <span class="fw-semibold"><?= $row['nama_penyewa'] ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="unit-badge">
                                    <i class="fa-solid fa-door-open me-1"></i><?= $row['tipe_kamar'] ?>
                                </span>
                            </td>
                            <td>
                                <div class="date-box date-danger">
                                    <i class="fa-regular fa-calendar-xmark me-1"></i>
                                    <?= date('d/m/Y', strtotime($row['jatuh_tempo'])) ?>
                                </div>
                            </td>
                            <td>
                                <?php if($row['tanggal_bayar']): ?>
                                    <div class="date-box date-success">
                                        <i class="fa-solid fa-check-circle me-1"></i>
                                        <?= date('d/m/Y', strtotime($row['tanggal_bayar'])) ?>
                                    </div>
                                <?php else: ?>
                                    <div class="date-box date-muted">
                                        <i class="fa-regular fa-circle-xmark me-1"></i>Belum bayar
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php 
                                if($row['status'] == 'Lunas') {
                                    $status_class = 'status-success';
                                    $icon = 'fa-circle-check';
                                } elseif($row['status'] == 'Terlambat') {
                                    $status_class = 'status-warning';
                                    $icon = 'fa-clock';
                                } else {
                                    $status_class = 'status-danger';
                                    $icon = 'fa-exclamation-circle';
                                }
                                ?>
                                <span class="status-badge <?= $status_class ?>">
                                    <i class="fa-solid <?= $icon ?> me-1"></i><?= $row['status'] ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="action-buttons">
                                    <a href="index.php?page=edit_pembayaran&id=<?= $row['id_pembayaran'] ?>" 
                                       class="action-btn btn-edit" 
                                       title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <a href="index.php?page=pembayaran&action=hapus&id=<?= $row['id_pembayaran'] ?>" 
                                       class="action-btn btn-delete" 
                                       onclick="return confirm('Hapus transaksi <?= $row['bulan'] ?> <?= $row['tahun'] ?>?')" 
                                       title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fa-solid fa-folder-open"></i>
                                    </div>
                                    <h6 class="text-muted mt-3">Belum Ada Data</h6>
                                    <p class="text-muted small mb-0">Belum ada transaksi pembayaran yang tercatat</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
/* Header Gradient */
.header-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
}

/* Colorful Card */
.colorful-card {
    border-radius: 16px;
    overflow: hidden;
}

.card-header-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 1rem 1.5rem;
}

/* Table Styling */
.colorful-table thead {
    background: linear-gradient(to right, #f8fafc, #e0e7ff);
}

.colorful-table thead th {
    font-weight: 600;
    font-size: 0.85rem;
    color: #4f46e5;
    border: none;
}

.table-row-animated {
    transition: all 0.3s ease;
}

.table-row-animated:hover {
    background: linear-gradient(to right, #faf5ff, #ffffff);
    transform: scale(1.01);
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
}

/* Number Badge */
.number-badge {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 0.9rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Period Badge */
.period-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background: linear-gradient(135deg, #ddd6fe, #e9d5ff);
    color: #6b21a8;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(107, 33, 168, 0.15);
}

/* Tenant Avatar */
.tenant-avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #4facfe, #00f2fe);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-right: 0.75rem;
    box-shadow: 0 4px 12px rgba(79, 172, 254, 0.3);
}

/* Unit Badge */
.unit-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(251, 191, 36, 0.2);
}

/* Date Box */
.date-box {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 500;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

.date-danger {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #991b1b;
}

.date-success {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #065f46;
}

.date-muted {
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    color: #64748b;
}

/* Status Badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.6rem 1.2rem;
    border-radius: 10px;
    font-size: 0.8rem;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
}

.status-success {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.status-warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

.status-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.status-badge:hover {
    transform: scale(1.05);
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.action-btn {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    text-decoration: none;
    font-size: 0.9rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.btn-edit {
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    color: white;
}

.btn-edit:hover {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
}

.btn-delete {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.btn-delete:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
}

/* Empty State */
.empty-state {
    padding: 3rem 0;
}

.empty-icon {
    width: 100px;
    height: 100px;
    margin: 0 auto;
    background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: #818cf8;
}

/* Button Styling */
.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
}

.btn-success {
    background: linear-gradient(135deg, #10b981, #059669);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #059669, #047857);
}

/* Responsive */
@media (max-width: 768px) {
    .header-gradient {
        padding: 1.5rem;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .d-flex.gap-2 .btn {
        width: 100%;
    }
    
    .table {
        font-size: 0.85rem;
    }
    
    .number-badge {
        width: 32px;
        height: 32px;
        font-size: 0.8rem;
    }
    
    .tenant-avatar {
        width: 35px;
        height: 35px;
    }
    
    .action-btn {
        width: 34px;
        height: 34px;
        font-size: 0.85rem;
    }
}
</style>
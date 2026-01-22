<?php
// 1. QUERY MENGAMBIL DATA DENGAN JOIN PENYEWA
// Kita hubungkan id_penyewa di kontrakan dengan id_penyewa di tabel penyewa
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

// Loop sementara untuk hitung angka statistik
while ($temp = mysqli_fetch_assoc($data)) {
    $total_potensi_harga += $temp['harga'];
    if (!empty($temp['id_penyewa'])) {
        $unit_terisi++;
    } else {
        $unit_kosong++;
    }
}
mysqli_data_seek($data, 0); // Reset pointer agar data bisa di-loop lagi di tabel bawah
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
                                       class="action-btn btn-delete btn-hapus-kontrakan" 
                                       data-id="<?= $row['id_kontrakan'] ?>" 
                                       data-name="<?= htmlspecialchars($row['tipe_kamar']) ?>" 
                                       title="Hapus">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada data unit kontrakan.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<style>
    /* Modern Button Style */
    .btn-modern {
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }
    
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(13, 110, 253, 0.3) !important;
    }

    /* Stats Cards */
    .stats-card {
        background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
        border-radius: 16px;
        padding: 24px;
        color: white;
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    .bg-gradient-primary {
        --gradient-start: #667eea;
        --gradient-end: #764ba2;
    }
    
    .bg-gradient-success {
        --gradient-start: #56ab2f;
        --gradient-end: #a8e063;
    }
    
    .bg-gradient-info {
        --gradient-start: #4facfe;
        --gradient-end: #00f2fe;
    }
    
    .stats-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        backdrop-filter: blur(10px);
    }
    
    .stats-content {
        flex: 1;
    }
    
    .stats-number {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
        line-height: 1;
    }
    
    .stats-label {
        margin: 5px 0 0 0;
        font-size: 14px;
        opacity: 0.9;
        font-weight: 500;
    }
    

    /* Modern Card */
    .modern-card {
        border-radius: 20px;
        overflow: hidden;
        background: white;
    }
    
    .card-header {
        border-bottom: 2px solid #f1f5f9 !important;
    }

    /* Modern Table */
    .modern-table thead {
        background: linear-gradient(to right, #f8fafc, #f1f5f9);
    }
    
    .modern-table thead th {
        border: none;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
    }
    
    .table-row-hover {
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }
    
    .table-row-hover:hover {
        background: linear-gradient(to right, #f0f9ff, #ffffff);
        border-left-color: #3b82f6;
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    /* Number Badge */
    .number-badge {
        width: 35px;
        height: 35px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }

    /* Unit Icon */
    .unit-icon-wrapper {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #e0f2fe, #bfdbfe);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 20px;
        color: #3b82f6;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
    }
    
    .unit-name {
        font-size: 15px;
        margin-bottom: 2px;
    }

    /* Price Tag */
    .price-tag {
        display: inline-flex;
        flex-direction: column;
        padding: 8px 16px;
        background: linear-gradient(135deg, #ecfdf5, #d1fae5);
        border-radius: 10px;
        border: 1px solid #a7f3d0;
    }
    
    .price-amount {
        font-weight: 700;
        font-size: 16px;
        color: #059669;
        line-height: 1;
    }
    
    .price-period {
        font-size: 11px;
        color: #10b981;
        margin-top: 2px;
        font-weight: 500;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
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
        font-size: 14px;
        border: 2px solid transparent;
    }
    
    .btn-edit {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #d97706;
        border-color: #fcd34d;
    }
    
    .btn-edit:hover {
        background: linear-gradient(135deg, #fde68a, #fbbf24);
        color: #92400e;
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 4px 12px rgba(217, 119, 6, 0.3);
    }
    
    .btn-delete {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #dc2626;
        border-color: #fca5a5;
    }
    
    .btn-delete:hover {
        background: linear-gradient(135deg, #fecaca, #fca5a5);
        color: #7f1d1d;
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    /* Empty State */
    .empty-state {
        padding: 40px 20px;
    }
    
    .empty-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        color: #cbd5e1;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-card {
            padding: 16px;
            gap: 12px;
        }
        
        .stats-icon {
            width: 50px;
            height: 50px;
            font-size: 22px;
        }
        
        .stats-number {
            font-size: 22px;
        }
        
        .stats-label {
            font-size: 12px;
        }
        
        .unit-icon-wrapper {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }
        
        .action-btn {
            width: 34px;
            height: 34px;
            font-size: 13px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Logika Konfirmasi Hapus
    const deleteButtons = document.querySelectorAll('.btn-delete-kontrakan');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');

            Swal.fire({
                title: 'Hapus Unit?',
                text: `Apakah Anda yakin ingin menghapus unit ${name}? Data tidak dapat dikembalikan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6e7d88',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Arahkan ke file proses hapus
                    window.location.href = `index.php?page=kontrakan_hapus&id=${id}`;
                }
            });
        });
    });

    // Cek jika ada notifikasi dari URL (Flash Message)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('status') === 'success_hapus') {
        Swal.fire({
            icon: 'success',
            title: 'Terhapus!',
            text: 'Unit kontrakan telah berhasil dihapus.',
            timer: 2000,
            showConfirmButton: false
        });
    }
});
</script>
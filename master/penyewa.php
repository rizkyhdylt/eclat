<?php
// Query mengambil data penyewa
// Menggunakan LEFT JOIN agar kita bisa menampilkan info unit di kartu penyewa jika perlu
$query_p = "SELECT penyewa.*, kontrakan.tipe_kamar 
            FROM penyewa 
            LEFT JOIN kontrakan ON penyewa.id_penyewa = kontrakan.id_penyewa 
            ORDER BY penyewa.id_penyewa DESC";
$result_p = mysqli_query($conn, $query_p);

if (!$result_p) {
    echo "<div class='alert alert-danger shadow-sm'><i class='fa-solid fa-circle-xmark me-2'></i>Error: " . mysqli_error($conn) . "</div>";
}

// Hitung statistik
$total_penyewa = mysqli_num_rows($result_p);
mysqli_data_seek($result_p, 0);
?>

<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
        <div>
            <h3 class="fw-bold m-0 text-dark">
                <i class="fa-solid fa-users text-primary me-2"></i>Data Penyewa
            </h3>
            <p class="text-muted small mb-0 mt-1">
                <i class="fa-solid fa-circle-info me-1"></i>Kelola identitas dan transaksi pembayaran penghuni
            </p>
        </div>
        <a href="index.php?page=penyewa_tambah" class="btn btn-primary btn-modern px-4 shadow">
            <i class="fa-solid fa-user-plus me-2"></i>Tambah Penyewa Baru
        </a>
    </div>

    <div class="row g-3 mb-4" data-aos="fade-up">
        <div class="col-lg-4 col-md-6">
            <div class="stats-card bg-gradient-primary">
                <div class="stats-icon"><i class="fa-solid fa-users"></i></div>
                <div class="stats-content">
                    <h3 class="stats-number"><?= $total_penyewa ?></h3>
                    <p class="stats-label">Total Penyewa Aktif</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6">
            <div class="stats-card bg-gradient-success">
                <div class="stats-icon"><i class="fa-solid fa-money-bill-wave"></i></div>
                <div class="stats-content">
                    <h3 class="stats-number">Aktif</h3>
                    <p class="stats-label">Status Pembayaran</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6">
            <div class="stats-card bg-gradient-info">
                <div class="stats-icon"><i class="fa-solid fa-id-card"></i></div>
                <div class="stats-content">
                    <h3 class="stats-number">100%</h3>
                    <p class="stats-label">Kelengkapan Data</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;" data-aos="fade-up">
        <div class="card-body py-3">
            <div class="input-group">
                <span class="input-group-text bg-light border-0">
                    <i class="fa-solid fa-magnifying-glass text-muted"></i>
                </span>
                <input type="text" class="form-control border-0 bg-light" 
                       placeholder="Cari nama penyewa, unit, atau nomor HP..." 
                       id="searchInput">
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-lg modern-card" data-aos="fade-up" data-aos-delay="100">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="m-0 fw-bold text-dark">
                    <i class="fa-solid fa-address-book me-2 text-primary"></i>Daftar Penyewa & Pembayaran
                </h5>
            </div>
        </div>
        
        <div class="card-body p-4">
            <?php if (mysqli_num_rows($result_p) > 0) : ?>
                <div class="row g-3" id="penyewaGrid">
                    <?php while ($row = mysqli_fetch_assoc($result_p)) : 
                        $wa_number = preg_replace('/[^0-9]/', '', $row['no_hp']);
                        if(substr($wa_number, 0, 1) == '0') $wa_number = '62' . substr($wa_number, 1);
                    ?>
                    <div class="col-xl-4 col-lg-6 col-md-6 penyewa-item">
                        <div class="penyewa-card">
                            <div class="penyewa-header">
                                <div class="penyewa-avatar-wrapper">
                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($row['nama_penyewa']) ?>&background=random&color=fff&bold=true&size=120" 
                                         class="penyewa-avatar" alt="Avatar">
                                </div>
                                <div class="penyewa-info">
                                    <h5 class="penyewa-name"><?= htmlspecialchars($row['nama_penyewa']) ?></h5>
                                    <div class="penyewa-id">
                                        <i class="fa-solid fa-house-user me-1"></i>Unit: <?= $row['tipe_kamar'] ?? 'Belum Pilih Unit' ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="penyewa-details">
                                <div class="mb-3">
                                    <a href="index.php?page=pembayaran&id_penyewa=<?= $row['id_penyewa'] ?>" class="btn btn-primary w-100 fw-bold py-2 shadow-sm" style="border-radius: 10px;">
                                        <i class="fa-solid fa-receipt me-2"></i>Bayar & History
                                    </a>
                                </div>

                                <div class="detail-item">
                                    <div class="detail-icon phone"><i class="fa-solid fa-phone"></i></div>
                                    <div class="detail-content">
                                        <div class="detail-label">Nomor Telepon</div>
                                        <div class="detail-value"><?= htmlspecialchars($row['no_hp']) ?></div>
                                    </div>
                                    <a href="https://wa.me/<?= $wa_number ?>" target="_blank" class="whatsapp-btn"><i class="fa-brands fa-whatsapp"></i></a>
                                </div>
                            </div>
                            
                            <div class="penyewa-actions">
                                <a href="index.php?page=penyewa_edit&id=<?= $row['id_penyewa'] ?>" class="action-btn btn-edit" title="Edit Data">
                                    <i class="fa-solid fa-user-pen"></i><span>Edit</span>
                                </a>
                                <a href="master/delete_penyewa.php?hapus=<?= $row['id_penyewa'] ?>" 
                                   class="action-btn btn-delete"
                                   onclick="return confirm('Hapus data <?= htmlspecialchars($row['nama_penyewa']) ?>?')"
                                   title="Hapus Penyewa">
                                    <i class="fa-solid fa-user-minus"></i><span>Hapus</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">...</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    /* Modern Button */
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
        height: 100%;
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
        --gradient-start: #11998e;
        --gradient-end: #38ef7d;
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
    }

    /* Penyewa Card */
    .penyewa-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        border: 2px solid #f1f5f9;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .penyewa-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border-color: #3b82f6;
    }

    /* Penyewa Header */
    .penyewa-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 24px;
        text-align: center;
        position: relative;
    }
    
    .penyewa-avatar-wrapper {
        position: relative;
        display: inline-block;
        margin-bottom: 12px;
    }
    
    .penyewa-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 4px solid white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    
    .status-indicator {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 20px;
        height: 20px;
        background: #10b981;
        border-radius: 50%;
        border: 3px solid white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 8px;
        color: white;
    }
    
    .penyewa-info {
        color: white;
    }
    
    .penyewa-name {
        font-size: 18px;
        font-weight: 700;
        margin: 0 0 6px 0;
        color: white;
    }
    
    .penyewa-id {
        display: inline-block;
        padding: 4px 12px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }

    /* Penyewa Details */
    .penyewa-details {
        padding: 20px;
        flex: 1;
    }
    
    .detail-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px;
        background: #f8fafc;
        border-radius: 10px;
        margin-bottom: 10px;
        transition: all 0.2s ease;
    }
    
    .detail-item:hover {
        background: #f1f5f9;
    }
    
    .detail-item:last-child {
        margin-bottom: 0;
    }
    
    .detail-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }
    
    .detail-icon.phone {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1e40af;
    }
    
    .detail-icon.location {
        background: linear-gradient(135deg, #fce7f3, #fbcfe8);
        color: #be123c;
    }
    
    .detail-content {
        flex: 1;
        min-width: 0;
    }
    
    .detail-label {
        font-size: 11px;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 3px;
    }
    
    .detail-value {
        font-size: 13px;
        color: #1e293b;
        font-weight: 600;
        word-break: break-word;
    }
    
    .whatsapp-btn {
        width: 38px;
        height: 38px;
        background: linear-gradient(135deg, #10b981, #059669);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        text-decoration: none;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }
    
    .whatsapp-btn:hover {
        background: linear-gradient(135deg, #059669, #047857);
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
    }

    /* Penyewa Actions */
    .penyewa-actions {
        display: flex;
        gap: 8px;
        padding: 16px 20px;
        background: #f8fafc;
        border-top: 2px solid #f1f5f9;
    }
    
    .action-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .btn-edit {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
        border-color: #fcd34d;
    }
    
    .btn-edit:hover {
        background: linear-gradient(135deg, #fde68a, #fbbf24);
        color: #78350f;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(251, 191, 36, 0.4);
    }
    
    .btn-delete {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
        border-color: #fca5a5;
    }
    
    .btn-delete:hover {
        background: linear-gradient(135deg, #fecaca, #fca5a5);
        color: #7f1d1d;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(252, 165, 165, 0.4);
    }

    /* Empty State */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }
    
    .empty-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 56px;
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
        
        .penyewa-header {
            padding: 20px;
        }
        
        .penyewa-avatar {
            width: 70px;
            height: 70px;
        }
        
        .penyewa-name {
            font-size: 16px;
        }
        
        .penyewa-details {
            padding: 16px;
        }
        
        .action-btn span {
            display: none;
        }
        
        .action-btn {
            width: 44px;
            height: 44px;
            padding: 0;
        }
    }
</style>

<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const penyewaItems = document.querySelectorAll('.penyewa-item');
        
        searchInput.addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            
            penyewaItems.forEach(function(item) {
                const text = item.textContent.toLowerCase();
                
                if (text.includes(searchValue)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
</script>
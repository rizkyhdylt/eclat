<?php
// 1. Ambil ID Transaksi dari URL
$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

// 2. Logika Update Data
if (isset($_POST['update'])) {
    $id_penyewa     = $_POST['id_penyewa'];
    $id_kontrakan   = $_POST['id_kontrakan'];
    $bulan          = $_POST['bulan'];
    $tahun          = $_POST['tahun'];
    $jatuh_tempo    = $_POST['jatuh_tempo'];
    $tanggal_bayar  = $_POST['tanggal_bayar'];
    
    // Logika penentuan status otomatis
    if (empty($tanggal_bayar) || $tanggal_bayar == '0000-00-00') {
        $status = "Belum Lunas";
    } elseif (strtotime($tanggal_bayar) > strtotime($jatuh_tempo)) {
        $status = "Terlambat";
    } else {
        $status = "Lunas";
    }

    $query_update = "UPDATE pembayaran SET 
                        id_penyewa = '$id_penyewa', 
                        id_kontrakan = '$id_kontrakan', 
                        bulan = '$bulan', 
                        tahun = '$tahun', 
                        jatuh_tempo = '$jatuh_tempo', 
                        tanggal_bayar = '$tanggal_bayar', 
                        status = '$status' 
                     WHERE id_pembayaran = '$id'";
    
    if (mysqli_query($conn, $query_update)) {
        echo "<script>
                alert('✅ Transaksi periode $bulan $tahun berhasil diperbarui!');
                window.location.href='index.php?page=pembayaran&id_penyewa=$id_penyewa';
              </script>";
    } else {
        echo "<div class='alert alert-danger'>Gagal memperbarui: " . mysqli_error($conn) . "</div>";
    }
}

// 3. Ambil data lama untuk ditampilkan di Form
$query_get = mysqli_query($conn, "SELECT p.*, s.nama_penyewa, k.tipe_kamar, k.harga 
                                  FROM pembayaran p 
                                  JOIN penyewa s ON p.id_penyewa = s.id_penyewa
                                  JOIN kontrakan k ON p.id_kontrakan = k.id_kontrakan
                                  WHERE p.id_pembayaran = '$id'");
$data_lama = mysqli_fetch_assoc($query_get);

if (!$data_lama) {
    echo "<script>alert('Data transaksi tidak ditemukan!'); window.location.href='index.php?page=pembayaran';</script>";
    exit;
}

// Determine current status style
$status_class = '';
$status_icon = '';
switch($data_lama['status']) {
    case 'Lunas':
        $status_class = 'status-lunas';
        $status_icon = 'fa-circle-check';
        break;
    case 'Terlambat':
        $status_class = 'status-terlambat';
        $status_icon = 'fa-exclamation-triangle';
        break;
    default:
        $status_class = 'status-pending';
        $status_icon = 'fa-clock';
}
?>

<div class="container-fluid px-4 py-3">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
        <div>
            <h3 class="fw-bold m-0 text-dark">
                <i class="fa-solid fa-pen-to-square text-warning me-2"></i>Edit Transaksi Pembayaran
            </h3>
            <p class="text-muted small mb-0 mt-1">
                <i class="fa-solid fa-circle-info me-1"></i>Ubah detail pembayaran untuk 
                <strong class="text-primary"><?= htmlspecialchars($data_lama['nama_penyewa']) ?></strong>
            </p>
        </div>
        <a href="index.php?page=pembayaran&id_penyewa=<?= $data_lama['id_penyewa'] ?>" 
           class="btn btn-outline-secondary btn-modern px-4">
            <i class="fa-solid fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <!-- Main Content -->
    <div class="row justify-content-center" data-aos="fade-up">
        <div class="col-lg-10">
            <!-- Transaction Info Banner -->
            <div class="transaction-banner mb-4">
                <div class="transaction-icon">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div class="transaction-info">
                    <div class="transaction-id">
                        <i class="fa-solid fa-hashtag me-1"></i>
                        Transaction ID: <strong><?= $id ?></strong>
                    </div>
                    <div class="transaction-status">
                        <span class="status-badge-large <?= $status_class ?>">
                            <i class="fa-solid <?= $status_icon ?> me-2"></i>
                            <?= $data_lama['status'] ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="card border-0 shadow-lg modern-card">
                <div class="card-header bg-gradient-header border-0 py-4">
                    <div class="d-flex align-items-center">
                        <div class="header-icon">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                        </div>
                        <div>
                            <h5 class="m-0 fw-bold text-white">Detail Transaksi</h5>
                            <p class="m-0 text-white-50 small">Update informasi pembayaran periode <?= $data_lama['bulan'] ?> <?= $data_lama['tahun'] ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <form action="" method="POST" id="editPaymentForm">
                        <input type="hidden" name="id_penyewa" value="<?= $data_lama['id_penyewa'] ?>">
                        <input type="hidden" name="id_kontrakan" value="<?= $data_lama['id_kontrakan'] ?>">
                        <input type="hidden" name="bulan" value="<?= $data_lama['bulan'] ?>">
                        <input type="hidden" name="tahun" value="<?= $data_lama['tahun'] ?>">

                        <!-- Existing Info Section -->
                        <div class="info-section mb-4">
                            <div class="section-title">
                                <i class="fa-solid fa-info-circle me-2"></i>Informasi Transaksi
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="info-card">
                                        <div class="info-card-icon tenant">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                        <div class="info-card-content">
                                            <label class="info-card-label">Penyewa</label>
                                            <div class="info-card-value"><?= htmlspecialchars($data_lama['nama_penyewa']) ?></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-card">
                                        <div class="info-card-icon unit">
                                            <i class="fa-solid fa-door-open"></i>
                                        </div>
                                        <div class="info-card-content">
                                            <label class="info-card-label">Unit Kamar</label>
                                            <div class="info-card-value"><?= htmlspecialchars($data_lama['tipe_kamar']) ?></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-card">
                                        <div class="info-card-icon price">
                                            <i class="fa-solid fa-money-bill-wave"></i>
                                        </div>
                                        <div class="info-card-content">
                                            <label class="info-card-label">Harga Sewa</label>
                                            <div class="info-card-value">Rp <?= number_format($data_lama['harga'], 0, ',', '.') ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Editable Fields Section -->
                        <div class="edit-section">
                            <div class="section-title">
                                <i class="fa-solid fa-pen me-2"></i>Update Tanggal Pembayaran
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label-custom">
                                        <i class="fa-solid fa-calendar-day text-danger me-2"></i>
                                        Jatuh Tempo
                                    </label>
                                    <div class="input-group-custom input-danger">
                                        <span class="input-icon">
                                            <i class="fa-solid fa-bell"></i>
                                        </span>
                                        <input type="date" 
                                               name="jatuh_tempo" 
                                               class="form-control-custom" 
                                               value="<?= $data_lama['jatuh_tempo'] ?>" 
                                               required
                                               onchange="checkPaymentStatus()">
                                    </div>
                                    <div class="form-hint">
                                        <i class="fa-solid fa-info-circle me-1"></i>
                                        Tenggat waktu pembayaran
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label-custom">
                                        <i class="fa-solid fa-hand-holding-dollar text-success me-2"></i>
                                        Tanggal Bayar
                                    </label>
                                    <div class="input-group-custom input-success">
                                        <span class="input-icon">
                                            <i class="fa-solid fa-calendar-check"></i>
                                        </span>
                                        <input type="date" 
                                               name="tanggal_bayar" 
                                               class="form-control-custom" 
                                               value="<?= $data_lama['tanggal_bayar'] ?>"
                                               onchange="checkPaymentStatus()">
                                    </div>
                                    <div class="form-hint">
                                        <i class="fa-solid fa-clock me-1"></i>
                                        Tanggal pembayaran dilakukan (kosongkan jika belum bayar)
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Preview -->
                        <div class="status-preview-section mt-4">
                            <div class="status-preview <?= $status_class ?>" id="statusPreview">
                                <div class="status-icon">
                                    <i class="fa-solid <?= $status_icon ?>" id="statusIcon"></i>
                                </div>
                                <div class="status-content">
                                    <label class="status-label">Status Akan Berubah Menjadi:</label>
                                    <div class="status-value" id="statusText"><?= $data_lama['status'] ?></div>
                                </div>
                                <div class="status-indicator" id="statusIndicator">
                                    <i class="fa-solid fa-circle-notch fa-spin"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="form-actions">
                            <button type="submit" name="update" class="btn btn-warning btn-action btn-lg">
                                <i class="fa-solid fa-save me-2"></i>
                                Simpan Perubahan
                            </button>
                            <a href="index.php?page=pembayaran&id_penyewa=<?= $data_lama['id_penyewa'] ?>" 
                               class="btn btn-outline-secondary btn-action btn-lg">
                                <i class="fa-solid fa-times-circle me-2"></i>
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change History Info -->
            <div class="card border-0 shadow-sm mt-4" style="border-radius: 16px; background: linear-gradient(135deg, #fff7ed 0%, #fed7aa 100%);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="warning-icon">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-warning mb-2">
                                <i class="fa-solid fa-exclamation-circle me-1"></i>Perhatian Saat Mengubah Data
                            </h6>
                            <ul class="warning-list">
                                <li>Perubahan tanggal bayar akan mempengaruhi status pembayaran secara otomatis</li>
                                <li>Jika tanggal bayar dikosongkan, status akan menjadi <strong>"Belum Lunas"</strong></li>
                                <li>Jika tanggal bayar melebihi jatuh tempo, status akan menjadi <strong>"Terlambat"</strong></li>
                                <li>Jika tanggal bayar sebelum/sama dengan jatuh tempo, status akan menjadi <strong>"Lunas"</strong></li>
                                <li>Pastikan data yang diubah sudah benar sebelum menyimpan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Modern Button */
    .btn-modern {
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 2px solid;
    }
    
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15) !important;
    }

    /* Transaction Banner */
    .transaction-banner {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        border-radius: 16px;
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 20px;
        color: white;
        box-shadow: 0 8px 24px rgba(245, 158, 11, 0.3);
    }
    
    .transaction-icon {
        width: 70px;
        height: 70px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        backdrop-filter: blur(10px);
        flex-shrink: 0;
    }
    
    .transaction-info {
        flex: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }
    
    .transaction-id {
        font-size: 16px;
        opacity: 0.95;
    }
    
    .status-badge-large {
        display: inline-flex;
        align-items: center;
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .status-badge-large.status-lunas {
        background: rgba(255, 255, 255, 0.95);
        color: #059669;
        border: 2px solid rgba(255, 255, 255, 0.5);
    }
    
    .status-badge-large.status-terlambat {
        background: rgba(255, 255, 255, 0.95);
        color: #dc2626;
        border: 2px solid rgba(255, 255, 255, 0.5);
    }
    
    .status-badge-large.status-pending {
        background: rgba(255, 255, 255, 0.95);
        color: #d97706;
        border: 2px solid rgba(255, 255, 255, 0.5);
    }

    /* Modern Card */
    .modern-card {
        border-radius: 20px;
        overflow: hidden;
    }
    
    .bg-gradient-header {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    }
    
    .header-icon {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-right: 16px;
        backdrop-filter: blur(10px);
    }

    /* Section Title */
    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        padding-bottom: 16px;
        margin-bottom: 20px;
        border-bottom: 2px solid #e2e8f0;
    }

    /* Info Section */
    .info-section {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 16px;
        padding: 24px;
    }
    
    .info-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .info-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .info-card-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }
    
    .info-card-icon.tenant {
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        color: #4f46e5;
    }
    
    .info-card-icon.unit {
        background: linear-gradient(135deg, #ddd6fe, #e9d5ff);
        color: #7c3aed;
    }
    
    .info-card-icon.price {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #059669;
    }
    
    .info-card-content {
        flex: 1;
        min-width: 0;
    }
    
    .info-card-label {
        display: block;
        font-size: 11px;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    
    .info-card-value {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Edit Section */
    .edit-section {
        margin-top: 32px;
        padding-top: 32px;
        border-top: 2px dashed #e2e8f0;
    }

    /* Form Elements */
    .form-label-custom {
        display: flex;
        align-items: center;
        font-size: 14px;
        font-weight: 700;
        color: #475569;
        margin-bottom: 10px;
    }

    .input-group-custom {
        position: relative;
        display: flex;
        align-items: center;
    }
    
    .input-icon {
        position: absolute;
        left: 18px;
        z-index: 10;
        font-size: 18px;
        pointer-events: none;
    }
    
    .form-control-custom {
        width: 100%;
        padding: 14px 18px 14px 52px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 600;
        color: #1e293b;
        transition: all 0.3s ease;
        background: white;
    }
    
    .form-control-custom:focus {
        outline: none;
        transform: scale(1.01);
    }
    
    .input-danger .input-icon {
        color: #dc2626;
    }
    
    .input-danger .form-control-custom {
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
        border-color: #fca5a5;
        color: #991b1b;
    }
    
    .input-danger .form-control-custom:focus {
        border-color: #dc2626;
        box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
    }
    
    .input-success .input-icon {
        color: #059669;
    }
    
    .input-success .form-control-custom {
        border-color: #6ee7b7;
    }
    
    .input-success .form-control-custom:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }

    .form-hint {
        margin-top: 8px;
        font-size: 12px;
        color: #64748b;
        display: flex;
        align-items: center;
    }

    /* Status Preview Section */
    .status-preview-section {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 16px;
        padding: 24px;
    }
    
    .status-preview {
        background: white;
        border: 3px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px 24px;
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.3s ease;
    }
    
    .status-preview .status-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }
    
    .status-preview .status-content {
        flex: 1;
    }
    
    .status-label {
        display: block;
        font-size: 12px;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    
    .status-value {
        font-size: 20px;
        font-weight: 700;
        transition: all 0.3s ease;
    }
    
    .status-indicator {
        display: none;
        font-size: 24px;
        color: #3b82f6;
    }
    
    /* Status Variants */
    .status-preview.status-lunas {
        border-color: #6ee7b7;
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    }
    
    .status-preview.status-lunas .status-icon {
        background: white;
        color: #059669;
    }
    
    .status-preview.status-lunas .status-value {
        color: #065f46;
    }
    
    .status-preview.status-terlambat {
        border-color: #fca5a5;
        background: linear-gradient(135deg, #fee2e2, #fecaca);
    }
    
    .status-preview.status-terlambat .status-icon {
        background: white;
        color: #dc2626;
    }
    
    .status-preview.status-terlambat .status-value {
        color: #991b1b;
    }
    
    .status-preview.status-pending {
        border-color: #fcd34d;
        background: linear-gradient(135deg, #fef3c7, #fde68a);
    }
    
    .status-preview.status-pending .status-icon {
        background: white;
        color: #d97706;
    }
    
    .status-preview.status-pending .status-value {
        color: #92400e;
    }

    /* Form Actions */
    .form-actions {
        margin-top: 32px;
        padding-top: 24px;
        border-top: 2px solid #e2e8f0;
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }
    
    .btn-action {
        padding: 14px 36px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 15px;
        transition: all 0.3s ease;
        border: 2px solid;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }
    
    .btn-warning.btn-action {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-color: #f59e0b;
        color: white;
    }
    
    .btn-warning.btn-action:hover {
        background: linear-gradient(135deg, #d97706, #b45309);
        box-shadow: 0 6px 16px rgba(245, 158, 11, 0.3);
    }

    /* Warning Icon & List */
    .warning-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #d97706;
        flex-shrink: 0;
    }
    
    .warning-list {
        margin: 0;
        padding-left: 20px;
        list-style: none;
    }
    
    .warning-list li {
        position: relative;
        padding-left: 24px;
        margin-bottom: 8px;
        color: #92400e;
        font-size: 14px;
        line-height: 1.6;
    }
    
    .warning-list li::before {
        content: "⚠";
        position: absolute;
        left: 0;
        color: #f59e0b;
        font-weight: 700;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .transaction-banner {
            flex-direction: column;
            text-align: center;
        }
        
        .transaction-info {
            justify-content: center;
        }
        
        .info-card {
            padding: 16px;
        }
        
        .info-card-icon {
            width: 45px;
            height: 45px;
            font-size: 20px;
        }
        
        .status-preview {
            flex-direction: column;
            text-align: center;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn-action {
            width: 100%;
        }
    }
</style>

<script>
    function checkPaymentStatus() {
        const jatuhTempo = document.querySelector('input[name="jatuh_tempo"]').value;
        const tanggalBayar = document.querySelector('input[name="tanggal_bayar"]').value;
        const statusPreview = document.getElementById('statusPreview');
        const statusText = document.getElementById('statusText');
        const statusIcon = document.getElementById('statusIcon');
        const statusIndicator = document.getElementById('statusIndicator');
        
        // Show loading indicator
        statusIndicator.style.display = 'block';
        
        setTimeout(() => {
            statusIndicator.style.display = 'none';
            
            if (!tanggalBayar || tanggalBayar === '0000-00-00') {
                // Belum Lunas / Pending
                statusPreview.className = 'status-preview status-pending';
                statusText.textContent = 'Belum Lunas';
                statusIcon.className = 'fa-solid fa-clock';
            } else if (new Date(tanggalBayar) > new Date(jatuhTempo)) {
                // Terlambat
                statusPreview.className = 'status-preview status-terlambat';
                statusText.textContent = 'Terlambat';
                statusIcon.className = 'fa-solid fa-exclamation-triangle';
            } else {
                // Lunas
                statusPreview.className = 'status-preview status-lunas';
                statusText.textContent = 'Lunas';
                statusIcon.className = 'fa-solid fa-circle-check';
            }   
        }, 500); // Simulate processing delay
    }
</script>
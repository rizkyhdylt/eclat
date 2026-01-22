<?php
// ==========================================
// 1. LOGIKA SIMPAN (Dijalankan saat tombol diklik)
// ==========================================
if (isset($_POST['simpan'])) {
    $id_penyewa    = mysqli_real_escape_string($conn, $_POST['id_penyewa']);
    $id_kontrakan  = mysqli_real_escape_string($conn, $_POST['id_kontrakan']);
    $bulan         = mysqli_real_escape_string($conn, $_POST['bulan']);
    $tahun         = mysqli_real_escape_string($conn, $_POST['tahun']);
    $jatuh_tempo   = mysqli_real_escape_string($conn, $_POST['jatuh_tempo']);
    $tanggal_bayar = mysqli_real_escape_string($conn, $_POST['tanggal_bayar']);

    // Penentuan Status
    if (strtotime($tanggal_bayar) > strtotime($jatuh_tempo)) {
        $status = "Terlambat";
    } else {
        $status = "Lunas";
    }

    $query_simpan = "INSERT INTO pembayaran (id_penyewa, id_kontrakan, bulan, tahun, jatuh_tempo, tanggal_bayar, status) 
                     VALUES ('$id_penyewa', '$id_kontrakan', '$bulan', '$tahun', '$jatuh_tempo', '$tanggal_bayar', '$status')";

    if (mysqli_query($conn, $query_simpan)) {
        echo "<script>
                alert('✅ Berhasil! Pembayaran periode $bulan $tahun telah disimpan.');
                window.location.href='index.php?page=pembayaran&id_penyewa=$id_penyewa';
              </script>";
        exit;
    } else {
        echo "<div class='alert alert-danger'>Gagal menyimpan: " . mysqli_error($conn) . "</div>";
    }
}

// ==========================================
// 2. LOGIKA TAMPILAN (Ambil data otomatis)
// ==========================================
$id_auto = isset($_GET['id_penyewa']) ? mysqli_real_escape_string($conn, $_GET['id_penyewa']) : '';
$data_p = null;
$jatuh_tempo_otomatis = "";
$bulan_db = "";
$tahun_db = "";

if (!empty($id_auto)) {
    // Ambil info unit dan aturan tanggal
    $sql_info = "SELECT p.nama_penyewa, k.id_kontrakan, k.tipe_kamar, k.harga, k.jatuh_tempo as tgl_jt
                 FROM penyewa p
                 JOIN kontrakan k ON p.id_kontrakan = k.id_kontrakan 
                 WHERE p.id_penyewa = '$id_auto'";
    $query_info = mysqli_query($conn, $sql_info);
    $data_p = mysqli_fetch_assoc($query_info);

    if ($data_p) {
        // Cari transaksi terakhir untuk menentukan bulan selanjutnya
        $sql_terakhir = "SELECT bulan, tahun FROM pembayaran 
                         WHERE id_penyewa = '$id_auto' 
                         ORDER BY id_pembayaran DESC LIMIT 1";
        $query_terakhir = mysqli_query($conn, $sql_terakhir);
        $data_terakhir = mysqli_fetch_assoc($query_terakhir);

        $tgl_aturan = str_pad($data_p['tgl_jt'], 2, '0', STR_PAD_LEFT);
        $bulan_array = [
            "Januari"=>1, "Februari"=>2, "Maret"=>3, "April"=>4, "Mei"=>5, "Juni"=>6,
            "Juli"=>7, "Agustus"=>8, "September"=>9, "Oktober"=>10, "November"=>11, "Desember"=>12
        ];

        if ($data_terakhir) {
            $bln_angka = $bulan_array[$data_terakhir['bulan']];
            $thn_angka = $data_terakhir['tahun'];
            $next_time = mktime(0, 0, 0, $bln_angka + 1, $tgl_aturan, $thn_angka);
        } else {
            $next_time = mktime(0, 0, 0, (int)date('m'), $tgl_aturan, (int)date('Y'));
        }

        $bulan_db = array_search((int)date('n', $next_time), $bulan_array);
        $tahun_db = date('Y', $next_time);
        $jatuh_tempo_otomatis = date('Y-m-d', $next_time);
    }
}
?>

<div class="container-fluid px-4 py-3">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
        <div>
            <h3 class="fw-bold m-0 text-dark">
                <i class="fa-solid fa-money-bill-transfer text-primary me-2"></i>Tambah Pembayaran
            </h3>
            <p class="text-muted small mb-0 mt-1">
                <i class="fa-solid fa-circle-info me-1"></i>Input transaksi pembayaran bulanan penyewa
            </p>
        </div>
        <a href="index.php?page=pembayaran" class="btn btn-outline-secondary btn-modern px-4">
            <i class="fa-solid fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <?php if (!$data_p): ?>
        <!-- No Tenant Selected State -->
        <div class="row justify-content-center" data-aos="fade-up">
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                    <div class="card-body text-center py-5">
                        <div class="empty-icon warning mb-4">
                            <i class="fa-solid fa-user-slash"></i>
                        </div>
                        <h4 class="text-warning fw-bold mb-3">Penyewa Belum Dipilih</h4>
                        <p class="text-muted mb-4">Silakan pilih penyewa terlebih dahulu melalui menu Data Penyewa untuk melanjutkan input pembayaran.</p>
                        <a href="index.php?page=penyewa" class="btn btn-primary btn-modern px-5">
                            <i class="fa-solid fa-users me-2"></i>Pilih Penyewa
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Form Section -->
        <div class="row justify-content-center" data-aos="fade-up">
            <div class="col-lg-10">
                <!-- Tenant Info Card -->
                <div class="info-banner mb-4">
                    <div class="info-icon-wrapper">
                        <div class="info-icon">
                            <i class="fa-solid fa-user-check"></i>
                        </div>
                    </div>
                    <div class="info-content">
                        <h5 class="info-title"><?= htmlspecialchars($data_p['nama_penyewa']) ?></h5>
                        <p class="info-text">
                            <i class="fa-solid fa-door-open me-2"></i><?= htmlspecialchars($data_p['tipe_kamar']) ?>
                            <span class="mx-2">•</span>
                            <i class="fa-solid fa-money-bill-wave me-2"></i>Rp <?= number_format($data_p['harga'], 0, ',', '.') ?>/bulan
                        </p>
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
                                <h5 class="m-0 fw-bold text-white">Form Input Pembayaran</h5>
                                <p class="m-0 text-white-50 small">Periode: <?= $bulan_db ?> <?= $tahun_db ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <form action="" method="POST" id="paymentForm">
                            <input type="hidden" name="id_penyewa" value="<?= $id_auto ?>">
                            <input type="hidden" name="id_kontrakan" value="<?= $data_p['id_kontrakan'] ?>">
                            <input type="hidden" name="bulan" value="<?= $bulan_db ?>">
                            <input type="hidden" name="tahun" value="<?= $tahun_db ?>">

                            <div class="row g-4">
                                <!-- Info Section -->
                                <div class="col-12">
                                    <div class="section-title">
                                        <i class="fa-solid fa-info-circle me-2"></i>Informasi Penyewa
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label-custom">
                                        <i class="fa-solid fa-user me-2"></i>Nama Penghuni
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="text" 
                                               class="form-control-custom readonly" 
                                               value="<?= htmlspecialchars($data_p['nama_penyewa']) ?>" 
                                               readonly>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label-custom">
                                        <i class="fa-solid fa-home me-2"></i>Unit & Harga Sewa
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="text" 
                                               class="form-control-custom readonly" 
                                               value="<?= htmlspecialchars($data_p['tipe_kamar']) ?> • Rp <?= number_format($data_p['harga'], 0, ',', '.') ?>" 
                                               readonly>
                                    </div>
                                </div>

                                <!-- Payment Details Section -->
                                <div class="col-12 mt-4">
                                    <div class="section-title">
                                        <i class="fa-solid fa-calendar-check me-2"></i>Detail Pembayaran
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label-custom required">
                                        <i class="fa-solid fa-bell text-danger me-2"></i>Jatuh Tempo
                                    </label>
                                    <div class="input-group-custom input-danger">
                                        <span class="input-icon">
                                            <i class="fa-solid fa-calendar-day"></i>
                                        </span>
                                        <input type="date" 
                                               name="jatuh_tempo" 
                                               class="form-control-custom" 
                                               value="<?= $jatuh_tempo_otomatis ?>" 
                                               readonly>
                                    </div>
                                    <div class="form-hint">
                                        <i class="fa-solid fa-info-circle me-1"></i>
                                        Periode: <strong><?= $bulan_db ?> <?= $tahun_db ?></strong>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label-custom required">
                                        <i class="fa-solid fa-hand-holding-dollar text-success me-2"></i>Tanggal Bayar
                                    </label>
                                    <div class="input-group-custom input-success">
                                        <span class="input-icon">
                                            <i class="fa-solid fa-calendar-check"></i>
                                        </span>
                                        <input type="date" 
                                               name="tanggal_bayar" 
                                               class="form-control-custom" 
                                               value="<?= date('Y-m-d') ?>" 
                                               required
                                               onchange="checkPaymentStatus()">
                                    </div>
                                    <div class="form-hint">
                                        <i class="fa-solid fa-clock me-1"></i>
                                        Sistem akan otomatis menentukan status
                                    </div>
                                </div>

                                <!-- Status Preview -->
                                <div class="col-12">
                                    <div class="status-preview" id="statusPreview">
                                        <div class="status-icon">
                                            <i class="fa-solid fa-info-circle"></i>
                                        </div>
                                        <div class="status-text">
                                            <strong>Status Pembayaran:</strong>
                                            <span id="statusText">Akan ditentukan otomatis</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="form-actions">
                                <button type="submit" name="simpan" class="btn btn-primary btn-action btn-lg">
                                    <i class="fa-solid fa-check-circle me-2"></i>
                                    Simpan Pembayaran
                                </button>
                                <a href="index.php?page=pembayaran" class="btn btn-outline-secondary btn-action btn-lg">
                                    <i class="fa-solid fa-times-circle me-2"></i>
                                    Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="card border-0 shadow-sm mt-4" style="border-radius: 16px; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="help-icon">
                                <i class="fa-solid fa-lightbulb"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-primary mb-2">
                                    <i class="fa-solid fa-circle-info me-1"></i>Informasi Penting
                                </h6>
                                <ul class="help-list">
                                    <li>Sistem akan otomatis menentukan status <strong>Lunas</strong> atau <strong>Terlambat</strong> berdasarkan tanggal bayar</li>
                                    <li>Jika tanggal bayar melebihi jatuh tempo, status akan menjadi <strong class="text-danger">Terlambat</strong></li>
                                    <li>Periode pembayaran ditentukan otomatis berdasarkan transaksi terakhir</li>
                                    <li>Pastikan tanggal bayar sudah benar sebelum menyimpan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
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

    /* Empty State */
    .empty-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 60px;
    }
    
    .empty-icon.warning {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #d97706;
        box-shadow: 0 8px 24px rgba(217, 119, 6, 0.2);
    }

    /* Info Banner */
    .info-banner {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 20px;
        color: white;
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
    }
    
    .info-icon-wrapper {
        flex-shrink: 0;
    }
    
    .info-icon {
        width: 70px;
        height: 70px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        backdrop-filter: blur(10px);
    }
    
    .info-content {
        flex: 1;
    }
    
    .info-title {
        margin: 0 0 8px 0;
        font-size: 24px;
        font-weight: 700;
    }
    
    .info-text {
        margin: 0;
        opacity: 0.95;
        font-size: 15px;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
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
        padding-bottom: 12px;
        margin-bottom: 8px;
        border-bottom: 2px solid #e2e8f0;
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
    
    .form-label-custom.required::after {
        content: "*";
        color: #dc2626;
        margin-left: 4px;
    }

    .input-wrapper {
        position: relative;
    }

    .form-control-custom {
        width: 100%;
        padding: 14px 18px;
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
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }
    
    .form-control-custom.readonly {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        cursor: not-allowed;
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
    
    .input-group-custom .form-control-custom {
        padding-left: 52px;
    }
    
    .input-danger .input-icon {
        color: #dc2626;
    }
    
    .input-danger .form-control-custom {
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
        border-color: #fca5a5;
        color: #991b1b;
    }
    
    .input-success .input-icon {
        color: #059669;
    }
    
    .input-success .form-control-custom {
        background: white;
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

    /* Status Preview */
    .status-preview {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 18px 24px;
        display: flex;
        align-items: center;
        gap: 16px;
        margin-top: 8px;
    }
    
    .status-icon {
        width: 50px;
        height: 50px;
        background: white;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #3b82f6;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    
    .status-text {
        font-size: 14px;
        color: #475569;
    }
    
    .status-text strong {
        color: #1e293b;
        margin-right: 8px;
    }
    
    .status-preview.lunas {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        border-color: #6ee7b7;
    }
    
    .status-preview.lunas .status-icon {
        background: white;
        color: #059669;
    }
    
    .status-preview.lunas .status-text #statusText {
        color: #065f46;
        font-weight: 700;
    }
    
    .status-preview.terlambat {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        border-color: #fca5a5;
    }
    
    .status-preview.terlambat .status-icon {
        background: white;
        color: #dc2626;
    }
    
    .status-preview.terlambat .status-text #statusText {
        color: #991b1b;
        font-weight: 700;
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
        padding: 12px 32px;
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

    /* Help Card */
    .help-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #3b82f6;
        flex-shrink: 0;
    }
    
    .help-list {
        margin: 0;
        padding-left: 20px;
        list-style: none;
    }
    
    .help-list li {
        position: relative;
        padding-left: 24px;
        margin-bottom: 8px;
        color: #475569;
        font-size: 14px;
        line-height: 1.6;
    }
    
    .help-list li::before {
        content: "✓";
        position: absolute;
        left: 0;
        color: #3b82f6;
        font-weight: 700;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .info-banner {
            flex-direction: column;
            text-align: center;
        }
        
        .info-icon {
            width: 60px;
            height: 60px;
            font-size: 28px;
        }
        
        .info-title {
            font-size: 20px;
        }
        
        .info-text {
            justify-content: center;
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
        const statusIcon = statusPreview.querySelector('.status-icon i');
        
        if (jatuhTempo && tanggalBayar) {
            const jt = new Date(jatuhTempo);
            const tb = new Date(tanggalBayar);
            
            if (tb > jt) {
                // Terlambat
                statusPreview.classList.remove('lunas');
                statusPreview.classList.add('terlambat');
                statusText.innerHTML = '<span class="badge bg-danger">TERLAMBAT</span> Pembayaran melewati jatuh tempo';
                statusIcon.className = 'fa-solid fa-exclamation-triangle';
            } else {
                // Lunas
                statusPreview.classList.remove('terlambat');
                statusPreview.classList.add('lunas');
                statusText.innerHTML = '<span class="badge bg-success">LUNAS</span> Pembayaran tepat waktu';
                statusIcon.className = 'fa-solid fa-check-circle';
            }
        }
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        checkPaymentStatus();
    });
</script>
<?php
// 1. Inisialisasi Filter
$bulan_sekarang = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', 
    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', 
    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
];

// Kita buat defaultnya kosong agar muncul semua
$filter_bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';
$filter_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';

// 2. Query Laporan dengan Kondisi Dinamis
$sql_where = "";
if (!empty($filter_bulan) && !empty($filter_tahun)) {
    $sql_where = " WHERE p.bulan = '$filter_bulan' AND p.tahun = '$filter_tahun'";
}

$query = "SELECT p.*, s.nama_penyewa, k.tipe_kamar, k.harga 
          FROM pembayaran p
          JOIN penyewa s ON p.id_penyewa = s.id_penyewa
          JOIN kontrakan k ON p.id_kontrakan = k.id_kontrakan
          $sql_where
          ORDER BY p.tahun DESC, p.bulan DESC, p.tanggal_bayar ASC";

$result = mysqli_query($conn, $query);
$total_pendapatan = 0;
$jumlah_data = mysqli_num_rows($result);

// Label untuk Header
$label_periode = ($filter_bulan == '') ? "Semua Periode" : "$filter_bulan $filter_tahun";
?>

<!-- Header Section -->
<div class="report-header no-print mb-4 hide-on-print" data-aos="fade-down">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-2">
                <i class="fa-solid fa-file-invoice-dollar text-primary me-2"></i>
                Laporan Pembayaran
            </h4>
            <p class="text-muted mb-0">
                <i class="fa-regular fa-calendar me-1"></i>
                Periode <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2"><?= $label_periode ?></span>
            </p>
        </div>
        <button onclick="window.print()" class="btn btn-dark btn-lg px-4">
            <i class="fa-solid fa-print me-2"></i>Cetak PDF
        </button>
    </div>
</div>

<!-- Filter Section -->
<div class="card border-0 shadow-sm mb-4 no-print" data-aos="fade-up">
    <div class="card-header bg-white py-3 border-0">
        <h6 class="mb-0 fw-bold">
            <i class="fa-solid fa-filter text-primary me-2"></i>
            Filter Periode
        </h6>
    </div>
    <div class="card-body">
        <form action="index.php" method="GET">
            <input type="hidden" name="page" value="laporan">
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label fw-semibold small text-secondary">
                        <i class="fa-regular fa-calendar-days me-1"></i>Pilih Bulan
                    </label>
                    <select name="bulan" class="form-select form-select-lg shadow-sm border-0">
                        <option value="">-- Semua Bulan --</option> <?php foreach ($bulan_sekarang as $b) : ?>
                            <option value="<?= $b ?>" <?= ($filter_bulan == $b) ? 'selected' : '' ?>><?= $b ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-semibold small text-secondary">
                        <i class="fa-regular fa-calendar me-1"></i>Pilih Tahun
                    </label>
                    <select name="tahun" class="form-select form-select-lg shadow-sm border-0">
                        <?php for ($i = date('Y'); $i >= 2022; $i--) : ?>
                            <option value="<?= $i ?>" <?= ($filter_tahun == $i) ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-white small">.</label>
                    <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm">
                        <i class="fa-solid fa-magnifying-glass me-2"></i>Cari
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Report Table -->
<div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="100">
    <!-- Print Header -->
    <div class="d-none d-print-block print-header">
        <div class="text-center p-4 border-bottom">
            <h2 class="fw-bold text-uppercase mb-2">Laporan Pembayaran Kontrakan</h2>
            <p class="mb-1">Sistem Informasi Manajemen Pembayaran</p>
            <p class="small mb-0 text-secondary">Periode: <?= $filter_bulan ?> <?= $filter_tahun ?></p>
        </div>
    </div>

    <!-- Screen Header -->
    <div class="card-header bg-gradient-primary text-white py-3 border-0 no-print">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">
                <i class="fa-solid fa-table-list me-2"></i>
                Rincian Transaksi
            </h6>
            <span class="badge bg-white text-primary px-3 py-2">
                <?= $jumlah_data ?> Transaksi
            </span>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="reportTable">
                <thead>
                    <tr>
                        <th class="ps-4 py-3" width="5%">No</th>
                        <th class="py-3" width="15%">
                            <i class="fa-regular fa-calendar-check me-1"></i>Tanggal Bayar
                        </th>
                        <th class="py-3">
                            <i class="fa-solid fa-user me-1"></i>Penyewa & Unit
                        </th>
                        <th class="py-3 text-center" width="15%">
                            <i class="fa-solid fa-circle-check me-1"></i>Status
                        </th>
                        <th class="py-3 text-end pe-4" width="18%">
                            <i class="fa-solid fa-money-bill-wave me-1"></i>Nominal
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1; 
                    if ($jumlah_data > 0) :
                        while ($row = mysqli_fetch_assoc($result)) : 
                            $jumlah = $row['harga'];
                            $total_pendapatan += $jumlah;
                    ?>
                    <tr class="data-row">
                        <td class="ps-4">
                            <span class="number-badge"><?= $no++ ?></span>
                        </td>
                        <td>
                            <div class="date-display">
                                <i class="fa-regular fa-calendar text-muted me-2"></i>
                                <span class="fw-medium">
                                    <?= ($row['tanggal_bayar'] != '0000-00-00' && !empty($row['tanggal_bayar'])) ? date('d/m/Y', strtotime($row['tanggal_bayar'])) : '-' ?>
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="tenant-info">
                                <div class="fw-semibold text-dark"><?= htmlspecialchars($row['nama_penyewa']) ?></div>
                                <small class="text-muted">
                                    <i class="fa-solid fa-house-user me-1"></i><?= $row['tipe_kamar'] ?>
                                </small>
                            </div>
                        </td>
                        <td class="text-center">
                            <?php if($row['status'] == 'Lunas'): ?>
                                <span class="status-badge status-success">
                                    <i class="fa-solid fa-circle-check me-1"></i>Lunas
                                </span>
                            <?php else: ?>
                                <span class="status-badge status-warning">
                                    <i class="fa-solid fa-clock me-1"></i>Terlambat
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-4">
                            <span class="amount-text">Rp <?= number_format($jumlah, 0, ',', '.') ?></span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    
                    <!-- Total Row -->
                    <tr class="total-row">
                        <td colspan="4" class="text-end py-4 ps-4">
                            <strong class="text-uppercase">Total Pendapatan:</strong>
                        </td>
                        <td class="text-end pe-4 py-4">
                            <span class="total-amount">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></span>
                        </td>
                    </tr>
                    <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="empty-state">
                                <i class="fa-solid fa-folder-open display-1 text-muted mb-3"></i>
                                <h6 class="text-muted">Tidak Ada Data</h6>
                                <p class="text-muted small mb-0">Belum ada transaksi untuk periode ini</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Print Footer -->
    <div class="d-none d-print-block p-4 border-top">
        <div class="row">
            <div class="col-7">
                <div class="print-notes">
                    <strong>Catatan:</strong><br>
                    • Laporan ini dihasilkan secara otomatis oleh sistem<br>
                    • Dicetak pada: <?= date('d/m/Y H:i') ?> WIB
                </div>
            </div>
            <div class="col-5 text-center">
                <p class="mb-5 small">Tangerang Selatan, <?= date('d F Y') ?><br><strong>Pengelola</strong></p>
                <div class="signature-line">
                    <strong>ADMINISTRATOR</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Header Styling */
.report-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    color: white;
}

.report-header h4 {
    color: white;
}

.report-header .text-muted {
    color: rgba(255,255,255,0.9) !important;
}

.report-header .btn-dark {
    background: white;
    color: #667eea;
    border: none;
}

.report-header .btn-dark:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Card Styling */
.card {
    border-radius: 12px;
    overflow: hidden;
}

.card-header.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Form Styling */
.form-select {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.75rem 1rem;
}

.form-select:focus {
    background-color: white;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* Table Styling */
.table thead {
    background: linear-gradient(to bottom, #f8fafc, #f1f5f9);
}

.table thead th {
    font-weight: 600;
    font-size: 0.85rem;
    color: #475569;
    border: none;
}

.table tbody .data-row {
    transition: all 0.2s ease;
}

.table tbody .data-row:hover {
    background-color: #f8fafc;
    transform: scale(1.01);
}

/* Number Badge */
.number-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
    color: #4338ca;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
}

/* Date Display */
.date-display {
    display: flex;
    align-items: center;
}

/* Tenant Info */
.tenant-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

/* Status Badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.8rem;
}

.status-success {
    background-color: #d1fae5;
    color: #065f46;
}

.status-warning {
    background-color: #fef3c7;
    color: #92400e;
}

/* Amount Text */
.amount-text {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.95rem;
}

/* Total Row */
.total-row {
    background: linear-gradient(to bottom, #fef3c7, #fde68a);
    font-weight: 700;
}

.total-amount {
    font-size: 1.5rem;
    font-weight: 800;
    color: #0369a1;
}

/* Empty State */
.empty-state {
    padding: 3rem 0;
}

.empty-state i {
    opacity: 0.2;
}

/* Print Styles */
@media print {
    @page { 
        size: A4 portrait; 
        margin: 1.5cm; 
    }
    
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    body { 
        background: white !important; 
        font-family: 'Times New Roman', serif !important; 
    }
    
    /* Hide all navigation and header elements */
    .no-print,
    .hide-on-print,
    .sidebar,
    .navbar,
    header,
    .header,
    .top-bar,
    .topbar,
    .menu,
    .breadcrumb,
    nav,
    button,
    .btn,
    form {
        display: none !important;
        visibility: hidden !important;
    }
    
    /* Reset containers */
    .main-content,
    .content,
    .container-fluid,
    .container,
    body,
    html { 
        margin: 0 !important; 
        padding: 0 !important; 
        max-width: 100% !important;
        width: 100% !important;
    }
    
    .card { 
        border: 1px solid #000 !important; 
        box-shadow: none !important; 
        border-radius: 0 !important;
    }
    
    .print-header h2 {
        margin: 0;
        padding: 1rem 0;
    }
    
    #reportTable { 
        border-collapse: collapse !important; 
        width: 100% !important;
    }
    
    #reportTable th, 
    #reportTable td { 
        border: 1px solid #000 !important; 
        padding: 8px 12px !important; 
        color: #000 !important;
    }
    
    #reportTable thead {
        background-color: #e5e7eb !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    .status-badge {
        background: none !important;
        color: #000 !important;
        border: 1px solid #000 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    .total-row {
        background-color: #f3f4f6 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    .total-amount {
        color: #000 !important;
    }
    
    .number-badge {
        background: none !important;
        color: #000 !important;
        border: 1px solid #000 !important;
    }
    
    .text-muted,
    small {
        color: #666 !important;
    }
    
    .print-notes {
        font-size: 0.85rem;
        line-height: 1.6;
    }
    
    .signature-line {
        margin-top: 4rem;
        padding-top: 1rem;
        border-top: 2px solid #000;
        display: inline-block;
        min-width: 200px;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .report-header .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .report-header .btn {
        width: 100%;
    }
    
    .table {
        font-size: 0.85rem;
    }
    
    .status-badge {
        font-size: 0.7rem;
        padding: 0.4rem 0.8rem;
    }
}
</style>
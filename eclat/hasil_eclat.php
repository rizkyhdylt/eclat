<?php
/**
 * Halaman Hasil Analisis ECLAT dengan UI Dashboard Analysis
 */

// 1. Ambil data transaksi
$query = "SELECT p.bulan, p.tahun, p.status, s.nama_penyewa, k.tipe_kamar 
          FROM pembayaran p
          JOIN penyewa s ON p.id_penyewa = s.id_penyewa
          JOIN kontrakan k ON p.id_kontrakan = k.id_kontrakan
          ORDER BY p.tahun DESC, p.bulan DESC";
$result = mysqli_query($conn, $query);

$pola_terlambat = [];
$total_transaksi = 0;

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $total_transaksi++;
        // ECLAT sederhana: Menghitung kemunculan (Support) status Terlambat
        if ($row['status'] == 'Terlambat') {
            $key = $row['nama_penyewa'] . " [" . $row['tipe_kamar'] . "]";
            if (!isset($pola_terlambat[$key])) {
                $pola_terlambat[$key] = 0;
            }
            $pola_terlambat[$key]++;
        }
    }
    arsort($pola_terlambat); 
}
?>

<!-- Header Section with Gradient Background -->
<div class="analysis-header mb-4" data-aos="fade-down">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-2">
                    <div class="icon-box-large me-3">
                        <i class="fa-solid fa-diagram-project text-primary"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1 gradient-text">ECLAT Algorithm Analysis</h3>
                        <p class="text-muted mb-0">Vertical Data Mining untuk Pattern Discovery Keterlambatan Pembayaran</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="eclat/cetak_eclat.php" target="_blank" class="btn btn-export">
                    <i class="fa-solid fa-file-export me-2"></i>Export Report
                    <span class="btn-shine"></span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4" data-aos="fade-up">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-card-blue">
            <div class="stat-card-inner">
                <div class="stat-icon">
                    <i class="fa-solid fa-database"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Total Dataset</div>
                    <div class="stat-value"><?= number_format($total_transaksi) ?></div>
                    <div class="stat-subtext">Transaksi diproses</div>
                </div>
            </div>
            <div class="stat-wave stat-wave-blue"></div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-card-red">
            <div class="stat-card-inner">
                <div class="stat-icon">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Itemset Pattern</div>
                    <div class="stat-value"><?= count($pola_terlambat) ?></div>
                    <div class="stat-subtext">Pola terdeteksi</div>
                </div>
            </div>
            <div class="stat-wave stat-wave-red"></div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-card-green">
            <div class="stat-card-inner">
                <div class="stat-icon">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Algorithm Status</div>
                    <div class="stat-value">Active</div>
                    <div class="stat-subtext">ECLAT Engine Running</div>
                </div>
            </div>
            <div class="stat-wave stat-wave-green"></div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-card-orange">
            <div class="stat-card-inner">
                <div class="stat-icon">
                    <i class="fa-solid fa-sliders"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Min. Support</div>
                    <div class="stat-value">10%</div>
                    <div class="stat-subtext">Threshold default</div>
                </div>
            </div>
            <div class="stat-wave stat-wave-orange"></div>
        </div>
    </div>
</div>

<!-- Main Analysis Table -->
<div class="analysis-table-container" data-aos="fade-up" data-aos-delay="200">
    <div class="table-header">
        <div class="d-flex align-items-center">
            <div class="table-icon">
                <i class="fa-solid fa-table-cells"></i>
            </div>
            <div>
                <h5 class="mb-1 fw-bold">Vertical Itemset Mining Results</h5>
                <p class="mb-0 text-muted small">Analisis pola keterlambatan berdasarkan penyewa dan unit kamar</p>
            </div>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table analysis-table mb-0">
            <thead>
                <tr>
                    <th width="80">
                        <div class="th-content">Rank</div>
                    </th>
                    <th>
                        <div class="th-content">
                            <i class="fa-solid fa-user-tag me-2"></i>Itemset (Penyewa & Unit)
                        </div>
                    </th>
                    <th width="180">
                        <div class="th-content">
                            <i class="fa-solid fa-clock me-2"></i>Keterlambatan
                        </div>
                    </th>
                    <th width="280">
                        <div class="th-content">
                            <i class="fa-solid fa-chart-line me-2"></i>Nilai Persentase (%)
                        </div>
                    </th>
                    <th width="180" class="text-center">
                        <div class="th-content">
                            <i class="fa-solid fa-bolt me-2"></i>Action Plan
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1; 
                if (count($pola_terlambat) > 0) :
                    foreach ($pola_terlambat as $itemset => $frekuensi) : 
                        $support = ($frekuensi / $total_transaksi) * 100;
                        
                        // Logika Warna Bar
                        $bar_color = "bar-primary";
                        $badge_color = "badge-info";
                        if ($support > 30) {
                            $bar_color = "bar-warning";
                            $badge_color = "badge-warning";
                        }
                        if ($support > 60) {
                            $bar_color = "bar-danger";
                            $badge_color = "badge-danger";
                        }
                ?>
                <tr class="table-row-hover">
                    <td>
                        <div class="rank-badge rank-<?= $no ?>"><?= $no++ ?></div>
                    </td>
                    <td>
                        <div class="itemset-info">
                            <div class="itemset-name"><?= htmlspecialchars($itemset) ?></div>
                            <div class="itemset-category">
                                <span class="category-badge">
                                    <i class="fa-solid fa-tag"></i> Pola Keterlambatan
                                </span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="frequency-box">
                            <span class="frequency-value"><?= $frekuensi ?></span>
                            <span class="frequency-label">kali</span>
                        </div>
                    </td>
                    <td>
                        <div class="progress-container">
                            <div class="progress-track">
                                <div class="progress-fill <?= $bar_color ?>" style="width: <?= $support ?>%">
                                    <span class="progress-glow"></span>
                                </div>
                            </div>
                            <span class="progress-label"><?= number_format($support, 1) ?>%</span>
                        </div>
                    </td>
                    <td class="text-center">
                        <?php if ($support >= 50) : ?>
                            <div class="action-badge action-critical">
                                <i class="fa-solid fa-gavel"></i>
                                <span>SP 1 Keluar</span>
                            </div>
                        <?php else : ?>
                            <div class="action-badge action-review">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                                <span>Review Bulanan</span>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; else : ?>
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fa-solid fa-face-smile"></i>
                            </div>
                            <h5 class="empty-title">Tidak Ada Pola Negatif Terdeteksi</h5>
                            <p class="empty-text">Semua penyewa memiliki tingkat kepatuhan pembayaran yang sangat baik.</p>
                            <div class="empty-decoration"></div>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
/* ===== VARIABLES ===== */
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --blue-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --red-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --green-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --orange-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
    --shadow-md: 0 4px 16px rgba(0,0,0,0.12);
    --shadow-lg: 0 8px 32px rgba(0,0,0,0.16);
    --radius-lg: 20px;
    --radius-xl: 24px;
}

/* ===== HEADER SECTION ===== */
.analysis-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);padding: 2rem;border-radius: var(--radius-xl);box-shadow: var(--shadow-lg);margin-bottom: 2rem;position: relative;overflow: hidden;
}

.analysis-header::before {
    content: '';position: absolute;top: -50%;right: -20%;width: 400px;height: 400px;background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);border-radius: 50%;
}

.analysis-header .container-fluid {
    position: relative;z-index: 1;
}

.icon-box-large {
    width: 70px;height: 70px;background: rgba(255,255,255,0.2);backdrop-filter: blur(10px);border-radius: 18px;display: flex;align-items: center;justify-content: center;font-size: 2rem;color: white;box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}

.gradient-text {
    background: linear-gradient(to right, #ffffff, #f0f0f0);-webkit-background-clip: text;-webkit-text-fill-color: transparent;background-clip: text;
}

.analysis-header h3 {
    color: white;font-size: 1.8rem;
}

.analysis-header p {
    color: rgba(255,255,255,0.9);font-size: 0.95rem;
}

/* ===== EXPORT BUTTON ===== */
.btn-export {
    background: white;color: #667eea;border: none;padding: 12px 28px;border-radius: 50px;font-weight: 600;box-shadow: 0 4px 16px rgba(0,0,0,0.15);transition: all 0.3s ease;position: relative;overflow: hidden;
}

.btn-export:hover {
    transform: translateY(-2px);box-shadow: 0 6px 24px rgba(0,0,0,0.2);color: #667eea;
}

.btn-shine {
    position: absolute;top: 0;left: -100%;width: 100%;height: 100%;background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
}

.btn-export:hover .btn-shine {
    animation: shine 0.6s;
}

@keyframes shine {
    to { left: 100%; }
}

/* ===== STAT CARDS ===== */
.stat-card {
    background: white;border-radius: var(--radius-lg);padding: 1.8rem;box-shadow: var(--shadow-md);transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);position: relative;overflow: hidden;border: 1px solid rgba(0,0,0,0.05);
}

.stat-card:hover {
    transform: translateY(-8px);box-shadow: var(--shadow-lg);
}

.stat-card-inner {
    display: flex;
    align-items: center;
    gap: 1.2rem;
    position: relative;
    z-index: 2;
}

.stat-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    flex-shrink: 0;
    transition: all 0.3s ease;
}

.stat-card:hover .stat-icon {
    transform: scale(1.1) rotate(5deg);
}

.stat-card-blue .stat-icon {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
    box-shadow: 0 8px 16px rgba(79, 172, 254, 0.3);
}

.stat-card-red .stat-icon {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    box-shadow: 0 8px 16px rgba(245, 87, 108, 0.3);
}

.stat-card-green .stat-icon {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    color: white;
    box-shadow: 0 8px 16px rgba(67, 233, 123, 0.3);
}

.stat-card-orange .stat-icon {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    color: white;
    box-shadow: 0 8px 16px rgba(250, 112, 154, 0.3);
}

.stat-content {
    flex: 1;
}

.stat-label {
    font-size: 0.75rem;
    color: #94a3b8;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: #1e293b;
    line-height: 1;
    margin-bottom: 0.3rem;
}

.stat-subtext {
    font-size: 0.8rem;
    color: #64748b;
}

.stat-wave {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, transparent, var(--wave-color), transparent);
}

.stat-wave-blue { --wave-color: #4facfe; }
.stat-wave-red { --wave-color: #f5576c; }
.stat-wave-green { --wave-color: #43e97b; }
.stat-wave-orange { --wave-color: #fa709a; }

/* ===== ANALYSIS TABLE ===== */
.analysis-table-container {
    background: white;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    border: 1px solid rgba(0,0,0,0.05);
}

.table-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 1.8rem 2rem;
    color: white;
}

.table-icon {
    width: 48px;
    height: 48px;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    margin-right: 1rem;
}

.table-header h5 {
    color: white;
    margin: 0;
}

.table-header p {
    color: rgba(255,255,255,0.9);
    margin: 0;
}

/* ===== TABLE STYLES ===== */
.analysis-table {
    margin: 0;
}

.analysis-table thead {
    background: linear-gradient(to bottom, #f8fafc, #f1f5f9);
}

.analysis-table thead th {
    padding: 1.2rem 1.5rem;
    border: none;
    font-weight: 700;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
}

.th-content {
    display: flex;
    align-items: center;
}

.analysis-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #f1f5f9;
}

.table-row-hover:hover {
    background: linear-gradient(to right, #f8fafc, white);
    transform: scale(1.01);
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.analysis-table tbody td {
    padding: 1.5rem 1.5rem;
    vertical-align: middle;
}

/* ===== RANK BADGE ===== */
.rank-badge {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 1rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.rank-1 { background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); box-shadow: 0 4px 12px rgba(255, 215, 0, 0.4); }
.rank-2 { background: linear-gradient(135deg, #C0C0C0 0%, #A8A8A8 100%); box-shadow: 0 4px 12px rgba(192, 192, 192, 0.4); }
.rank-3 { background: linear-gradient(135deg, #CD7F32 0%, #B87333 100%); box-shadow: 0 4px 12px rgba(205, 127, 50, 0.4); }

/* ===== ITEMSET INFO ===== */
.itemset-info {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}

.itemset-name {
    font-weight: 700;
    color: #1e293b;
    font-size: 0.95rem;
}

.itemset-category {
    display: flex;
    gap: 0.5rem;
}

.category-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.25rem 0.75rem;
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}

/* ===== FREQUENCY BOX ===== */
.frequency-box {
    display: inline-flex;
    align-items: baseline;
    gap: 0.4rem;
    padding: 0.5rem 1rem;
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    border-radius: 10px;
}

.frequency-value {
    font-size: 1.3rem;
    font-weight: 800;
    color: #1e293b;
}

.frequency-label {
    font-size: 0.8rem;
    color: #64748b;
    font-weight: 500;
}

/* ===== PROGRESS BAR ===== */
.progress-container {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.progress-track {
    flex: 1;
    height: 12px;
    background: #f1f5f9;
    border-radius: 20px;
    overflow: hidden;
    position: relative;
}

.progress-fill {
    height: 100%;
    border-radius: 20px;
    position: relative;
    transition: width 0.6s cubic-bezier(0.65, 0, 0.35, 1);
}

.bar-primary {
    background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
    box-shadow: 0 2px 8px rgba(79, 172, 254, 0.4);
}

.bar-warning {
    background: linear-gradient(90deg, #fa709a 0%, #fee140 100%);
    box-shadow: 0 2px 8px rgba(250, 112, 154, 0.4);
}

.bar-danger {
    background: linear-gradient(90deg, #f093fb 0%, #f5576c 100%);
    box-shadow: 0 2px 8px rgba(245, 87, 108, 0.4);
}

.progress-glow {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
    animation: progress-glow 2s infinite;
}

@keyframes progress-glow {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.progress-label {
    font-weight: 800;
    color: #1e293b;
    font-size: 0.95rem;
    min-width: 50px;
    text-align: right;
}

/* ===== ACTION BADGES ===== */
.action-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.2rem;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.75rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.action-critical {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(245, 87, 108, 0.3);
}

.action-critical:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(245, 87, 108, 0.4);
}

.action-review {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
    box-shadow: 0 4px 12px rgba(250, 204, 21, 0.2);
}

.action-review:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(250, 204, 21, 0.3);
}

/* ===== EMPTY STATE ===== */
.empty-state {
    padding: 3rem;
    position: relative;
}

.empty-icon {
    font-size: 5rem;
    color: #e2e8f0;
    margin-bottom: 1.5rem;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.empty-title {
    color: #64748b;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.empty-text {
    color: #94a3b8;
    font-size: 0.95rem;
}

.empty-decoration {
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 200px;
    height: 4px;
    background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
    border-radius: 2px;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .analysis-header {
        padding: 1.5rem;
    }
    
    .icon-box-large {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
    }
    
    .analysis-header h3 {
        font-size: 1.3rem;
    }
    
    .stat-value {
        font-size: 1.5rem;
    }
    
    .analysis-table thead th {
        padding: 1rem;
        font-size: 0.7rem;
    }
    
    .analysis-table tbody td {
        padding: 1rem;
    }
}
</style>
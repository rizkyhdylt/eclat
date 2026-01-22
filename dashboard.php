<?php
// Cek jika diakses langsung tanpa index.php
if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}

// Ambil data statistik
$jumlah_kontrakan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM kontrakan"))['total'];
$jumlah_penyewa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM penyewa"))['total'];
$jumlah_transaksi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pembayaran"))['total'];
$jumlah_terlambat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pembayaran WHERE status='Terlambat'"))['total'];

// Hitung persentase keterlambatan
$persen_terlambat = ($jumlah_transaksi > 0) ? ($jumlah_terlambat / $jumlah_transaksi) * 100 : 0;

// Ambil nama user untuk greeting
$nama_user = explode(' ', $_SESSION['user']['nama'])[0];
?>

<style>
    /* Stats Card Styles */
    .stats-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        height: 100%;
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--accent-color);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .stats-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-color: var(--accent-color);
    }

    .stats-card:hover::before {
        opacity: 1;
    }

    .stats-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 16px;
        background: var(--icon-bg);
        color: var(--icon-color);
    }

    .stats-label {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .stats-value {
        font-size: 2.25rem;
        font-weight: 800;
        color: #1e293b;
        line-height: 1;
        margin-bottom: 4px;
    }

    .stats-unit {
        font-size: 1rem;
        font-weight: 500;
        color: #94a3b8;
        margin-left: 6px;
    }

    .stats-progress {
        height: 6px;
        background: #f1f5f9;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 12px;
    }

    .stats-progress-bar {
        height: 100%;
        background: var(--accent-color);
        border-radius: 10px;
        transition: width 0.5s ease;
    }

    .stats-note {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-top: 8px;
    }

    /* Color Variations */
    .stats-card.blue {
        --accent-color: #3b82f6;
        --icon-bg: #dbeafe;
        --icon-color: #3b82f6;
    }

    .stats-card.green {
        --accent-color: #10b981;
        --icon-bg: #d1fae5;
        --icon-color: #10b981;
    }

    .stats-card.purple {
        --accent-color: #8b5cf6;
        --icon-bg: #ede9fe;
        --icon-color: #8b5cf6;
    }

    .stats-card.orange {
        --accent-color: #f59e0b;
        --icon-bg: #fef3c7;
        --icon-color: #f59e0b;
    }

    /* Welcome Banner */
    .welcome-banner {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border-radius: 20px;
        padding: 32px;
        color: white;
        margin-bottom: 28px;
        box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
        position: relative;
        overflow: hidden;
    }

    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1), transparent);
        border-radius: 50%;
    }

    .welcome-banner h2 {
        font-size: 1.75rem;
        font-weight: 800;
        margin-bottom: 8px;
        position: relative;
    }

    .welcome-banner p {
        opacity: 0.95;
        font-size: 1rem;
        margin: 0;
        position: relative;
    }

    /* Info Cards */
    .info-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 28px;
        height: 100%;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .info-card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* ECLAT Status Box */
    .eclat-status {
        display: flex;
        align-items: start;
        gap: 20px;
        padding: 24px;
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border-radius: 16px;
        border-left: 5px solid #06b6d4;
    }

    .eclat-icon {
        width: 56px;
        height: 56px;
        background: #06b6d4;
        color: white;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);
    }

    .eclat-text {
        flex: 1;
    }

    .eclat-text p {
        margin: 0;
        color: #475569;
        line-height: 1.7;
        font-size: 0.95rem;
    }

    .eclat-text strong {
        color: #1e293b;
        font-weight: 700;
    }

    .btn-eclat {
        background: #06b6d4;
        color: white;
        border: none;
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9rem;
        margin-top: 16px;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-eclat:hover {
        background: #0891b2;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(6, 182, 212, 0.3);
        color: white;
    }

    /* Quick Actions */
    .quick-action {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px;
        background: #f8fafc;
        border-radius: 12px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        color: #1e293b;
        border: 1px solid transparent;
    }

    .quick-action:hover {
        background: white;
        border-color: #e2e8f0;
        transform: translateX(6px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    .quick-action-icon {
        width: 44px;
        height: 44px;
        background: white;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .quick-action-text {
        font-weight: 600;
        font-size: 0.95rem;
        color: #1e293b;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .welcome-banner h2 {
            font-size: 1.4rem;
        }

        .stats-value {
            font-size: 1.75rem;
        }

        .eclat-status {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<!-- Welcome Banner -->
<div class="welcome-banner" data-aos="fade-down">
    <h2>Selamat Datang, <?= $nama_user ?>! 👋</h2>
    <p>Pantau kesehatan keuangan kontrakan Anda dalam satu layar.</p>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <!-- Total Kontrakan -->
    <div class="col-12 col-sm-6 col-xl-3" data-aos="zoom-in" data-aos-delay="100">
        <div class="stats-card blue">
            <div class="stats-icon">
                <i class="fa-solid fa-home"></i>
            </div>
            <div class="stats-label">Total Kontrakan</div>
            <div class="stats-value">
                <?= $jumlah_kontrakan ?><span class="stats-unit">Unit</span>
            </div>
        </div>
    </div>

    <!-- Penyewa Aktif -->
    <div class="col-12 col-sm-6 col-xl-3" data-aos="zoom-in" data-aos-delay="200">
        <div class="stats-card green">
            <div class="stats-icon">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="stats-label">Penyewa Aktif</div>
            <div class="stats-value">
                <?= $jumlah_penyewa ?><span class="stats-unit">Orang</span>
            </div>
        </div>
    </div>

    <!-- Total Transaksi -->
    <div class="col-12 col-sm-6 col-xl-3" data-aos="zoom-in" data-aos-delay="300">
        <div class="stats-card purple">
            <div class="stats-icon">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <div class="stats-label">Total Transaksi</div>
            <div class="stats-value">
                <?= $jumlah_transaksi ?><span class="stats-unit">Data</span>
            </div>
        </div>
    </div>

    <!-- Keterlambatan -->
    <div class="col-12 col-sm-6 col-xl-3" data-aos="zoom-in" data-aos-delay="400">
        <div class="stats-card orange">
            <div class="stats-icon">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div class="stats-label">Keterlambatan</div>
            <div class="stats-value">
                <?= $jumlah_terlambat ?><span class="stats-unit">Kasus</span>
            </div>
            <div class="stats-progress">
                <div class="stats-progress-bar" style="width: <?= $persen_terlambat ?>%"></div>
            </div>
            <div class="stats-note">
                Rasio: <?= number_format($persen_terlambat, 1) ?>% dari total
            </div>
        </div>
    </div>
</div>

<!-- Info Section -->
<div class="row g-4">
    <!-- ECLAT Status -->
    <div class="col-12 col-lg-8" data-aos="fade-right" data-aos-delay="500">
        <div class="info-card">
            <div class="info-card-title">
                <i class="fa-solid fa-microchip" style="color: #06b6d4;"></i>
                Status Kecerdasan Sistem (ECLAT)
            </div>
            
            <div class="eclat-status">
                <div class="eclat-icon">
                    <i class="fa-solid fa-brain"></i>
                </div>
                <div class="eclat-text">
                    <p>
                        Sistem saat ini sedang mengolah <strong><?= $jumlah_transaksi ?> dataset</strong> pembayaran. 
                        Algoritma ECLAT mendeteksi pola berdasarkan irisan ID Transaksi untuk 
                        memetakan penyewa mana yang paling berisiko tinggi terlambat dalam pembayaran   .
                    </p>
                    <a href="index.php?page=hasil_eclat" class="btn-eclat">
                        <i class="fa-solid fa-chart-bar"></i>
                        Lihat Hasil Mining
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-12 col-lg-4" data-aos="fade-left" data-aos-delay="600">
        <div class="info-card">
            <div class="info-card-title">
                <i class="fa-solid fa-bolt" style="color: #f59e0b;"></i>
                Aksi Cepat
            </div>

            <a href="index.php?page=pembayaran_form" class="quick-action">
                <div class="quick-action-icon" style="color: #3b82f6;">
                    <i class="fa-solid fa-plus-circle"></i>
                </div>
                <div class="quick-action-text">Input Pembayaran Baru</div>
            </a>

            <a href="index.php?page=penyewa" class="quick-action">
                <div class="quick-action-icon" style="color: #10b981;">
                    <i class="fa-solid fa-users-gear"></i>
                </div>
                <div class="quick-action-text">Kelola Data Penyewa</div>
            </a>

            <a href="index.php?page=laporan" class="quick-action">
                <div class="quick-action-icon" style="color: #8b5cf6;">
                    <i class="fa-solid fa-file-export"></i>
                </div>
                <div class="quick-action-text">Export Laporan Bulanan</div>
            </a>
        </div>
    </div>
</div>
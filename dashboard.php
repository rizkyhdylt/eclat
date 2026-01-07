<?php
// Cek jika diakses langsung tanpa index.php
if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}

$jumlah_kontrakan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM kontrakan"))['total'];
$jumlah_penyewa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM penyewa"))['total'];
$jumlah_transaksi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pembayaran"))['total'];
$jumlah_terlambat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pembayaran WHERE status='Terlambat'"))['total'];
?>

<div class="mb-4" data-aos="fade-down">
    <h4 class="fw-bold">Selamat Datang, <?= explode(' ', $_SESSION['user']['nama'])[0]; ?>! 👋</h4>
    <p class="text-muted">Berikut adalah ringkasan sistem kontrakan Anda hari ini.</p>
</div>

<div class="row g-4">
    <div class="col-md-3" data-aos="zoom-in" data-aos-delay="100">
        <div class="card stat-card shadow-sm h-100 bg-white border-0">
            <div class="card-body">
                <div class="icon-box bg-primary bg-opacity-10 text-primary">
                    <i class="fa-solid fa-house"></i>
                </div>
                <h6 class="text-muted mb-1 small">Total Kontrakan</h6>
                <h3 class="fw-bold mb-0"><?= $jumlah_kontrakan ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3" data-aos="zoom-in" data-aos-delay="200">
        <div class="card stat-card shadow-sm h-100 bg-white border-0">
            <div class="card-body">
                <div class="icon-box bg-success bg-opacity-10 text-success">
                    <i class="fa-solid fa-user-group"></i>
                </div>
                <h6 class="text-muted mb-1 small">Penyewa Aktif</h6>
                <h3 class="fw-bold mb-0"><?= $jumlah_penyewa ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3" data-aos="zoom-in" data-aos-delay="300">
        <div class="card stat-card shadow-sm h-100 bg-white border-0">
            <div class="card-body">
                <div class="icon-box bg-info bg-opacity-10 text-info">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <h6 class="text-muted mb-1 small">Total Transaksi</h6>
                <h3 class="fw-bold mb-0"><?= $jumlah_transaksi ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3" data-aos="zoom-in" data-aos-delay="400">
        <div class="card stat-card shadow-sm h-100 bg-white border-0 border-start border-danger border-4">
            <div class="card-body">
                <div class="icon-box bg-danger bg-opacity-10 text-danger">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <h6 class="text-muted mb-1 small">Keterlambatan</h6>
                <h3 class="fw-bold mb-0 text-danger"><?= $jumlah_terlambat ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="mt-5" data-aos="fade-up" data-aos-offset="0">
    <div class="alert alert-custom d-flex align-items-center p-4 shadow-sm" style="background: white; border-radius: 15px;">
        <div class="me-3 fs-3 text-primary animate__animated animate__pulse animate__infinite">
            <i class="fa-solid fa-circle-info"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-1">Analisis Algoritma ECLAT</h6>
            <p class="text-muted mb-0 small">
                Data di atas merupakan variabel input untuk mendeteksi pola asosiasi keterlambatan. 
                Silakan cek menu <strong>Analisis ECLAT</strong> untuk melihat hasil mining data.
            </p>
        </div>
    </div>
</div>

<div class="row mt-4" data-aos="fade-up" data-aos-delay="500">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="m-0 fw-bold">Aksi Cepat</h6>
            </div>
            <div class="card-body pt-0">
                <div class="d-flex gap-2">
                    <a href="index.php?page=pembayaran" class="btn btn-primary btn-sm px-3 shadow-sm rounded-pill">Input Pembayaran</a>
                    <a href="index.php?page=penyewa" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">Cek Penyewa</a>
                </div>
            </div>
        </div>
    </div>
</div>
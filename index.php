<?php
session_start();
include "config/config.php";

// Proteksi login
if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}

// Mengambil parameter halaman dari URL
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI-KONTRAK | <?= ucfirst($page) ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #e2e8f0; }
        .sidebar { min-height: 100vh; background: #1e293b; color: white; position: fixed; width: 260px; z-index: 1000; }
        .main-content { margin-left: 260px; min-height: 100vh; }
        .sidebar-heading { padding: 1.5rem 1rem; font-size: 1.2rem; font-weight: 700; background: #0f172a; text-align: center; }
        .nav-link { color: #94a3b8; padding: 12px 20px; display: flex; align-items: center; gap: 10px; transition: 0.2s; }
        .nav-link:hover { color: #fff; background: rgba(255,255,255,0.05); }
        .nav-link.active { color: #fff; background: #3b82f6; border-radius: 8px; margin: 5px 10px; }
        .top-nav { background: white; border-bottom: 1px solid #e2e8f0; padding: 15px 30px; position: sticky; top: 0; z-index: 999; }
    </style>
</head>
<body>

    <aside class="sidebar d-none d-md-block">
        <div class="sidebar-heading">
            <i class="fa-solid fa-house-chimney me-2 text-primary"></i> SI-KONTRAK
        </div>
        <div class="mt-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="index.php?page=dashboard" class="nav-link <?= $page == 'dashboard' ? 'active' : '' ?>">
                        <i class="fa-solid fa-gauge"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=kontrakan" class="nav-link <?= $page == 'kontrakan' ? 'active' : '' ?>">
                        <i class="fa-solid fa-door-open"></i> Data Kontrakan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=penyewa" class="nav-link <?= $page == 'penyewa' ? 'active' : '' ?>">
                        <i class="fa-solid fa-users"></i> Data Penyewa
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=pembayaran" class="nav-link <?= $page == 'pembayaran' ? 'active' : '' ?>">
                        <i class="fa-solid fa-money-bill-transfer"></i> Pembayaran
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=hasil_eclat" class="nav-link <?= $page == 'hasil_eclat' ? 'active' : '' ?>">
                        <i class="fa-solid fa-chart-line"></i> Analisis ECLAT
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=laporan" class="nav-link <?= $page == 'laporan' ? 'active' : '' ?>">
                        <i class="fa-solid fa-file-export"></i> Laporan
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <div class="main-content">
        <header class="top-nav d-flex justify-content-between align-items-center shadow-sm">
    <div class="d-flex align-items-center">
        <div class="me-3 d-md-none">
            <button class="btn btn-light border" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.75rem;">
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">SI-KONTRAK</a></li>
                    <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">
                        <?= ucfirst(str_replace('_', ' ', $page)) ?>
                    </li>
                </ol>
            </nav>
            <h5 class="m-0 fw-bold text-dark mt-1"><?= strtoupper(str_replace('_', ' ', $page)) ?></h5>
        </div>
    </div>

    <div class="d-flex align-items-center gap-3">
        <div class="text-end d-none d-lg-block border-end pe-3 me-2">
            <div class="fw-bold text-dark small" id="clock"></div>
            <div class="text-muted" style="font-size: 0.7rem;"><?= date('D, d M Y') ?></div>
        </div>

        <!-- <div class="dropdown">
            <button class="btn btn-light position-relative rounded-circle shadow-sm" style="width: 40px; height: 40px;" data-bs-toggle="dropdown">
                <i class="fa-regular fa-bell"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.5rem;">
                    2
                </span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-3 p-2" style="width: 280px; border-radius: 12px;">
                <li class="p-2 fw-bold small border-bottom mb-2">Notifikasi Terbaru</li>
                <li><a class="dropdown-item rounded p-2" href="#">
                    <div class="small fw-bold">Tagihan Jatuh Tempo</div>
                    <div class="text-muted small" style="font-size: 0.7rem;">Penyewa Budi (Kamar A) belum bayar.</div>
                </a></li>
                <li><a class="dropdown-item rounded p-2" href="#">
                    <div class="small fw-bold">ECLAT Selesai</div>
                    <div class="text-muted small" style="font-size: 0.7rem;">Analisis pola bulan ini siap dilihat.</div>
                </a></li>
            </ul>
        </div> -->

        <div class="dropdown">
            <button class="btn btn-white d-flex align-items-center gap-2 p-1 pe-3 rounded-pill border shadow-sm" type="button" data-bs-toggle="dropdown">
                <img src="https://ui-avatars.com/api/?name=Admin&background=3b82f6&color=fff" class="rounded-circle" width="32" alt="Avatar">
                <div class="text-start d-none d-sm-block">
                    <div class="fw-bold mb-0" style="font-size: 0.85rem; line-height: 1;">Administrator</div>
                    <small class="text-success fw-semibold" style="font-size: 0.7rem;">Online</small>
                </div>
                <i class="fa-solid fa-chevron-down ms-1 text-muted" style="font-size: 0.7rem;"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-3" style="border-radius: 12px; min-width: 200px;">
                <li><a class="dropdown-item py-2" href="index.php?page=pengaturan_akun"><i class="fa-solid fa-user-gear me-2 text-muted"></i> Pengaturan Akun</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item py-2 text-danger" href="auth/logout.php"><i class="fa-solid fa-power-off me-2"></i> Keluar</a></li>
            </ul>
        </div>
    </div>
</header>

        <div class="p-4">
            <?php 
                // Logika Routing berdasarkan susunan folder Anda
                switch ($page) {
                    case 'dashboard':
                        include "dashboard.php";
                        break;
                    case 'kontrakan':
                        include "master/kontrakan.php";
                        break;
                    case 'penyewa':
                        include "master/penyewa.php";
                        break;
                    case 'pembayaran':
                        include "transaksi/pembayaran.php";
                        break;
                    case 'hasil_eclat':
                        include "eclat/hasil_eclat.php";
                        break;
                    case 'laporan':
                        include "laporan/laporan.php";
                        break;
                    case 'kontrakan_form':
                        include "master/tambah_unit.php";
                        break;
                    case 'kontrakan_edit':
                        include "master/edit_kontrakan.php";
                        break;
                    case 'penyewa_tambah':
                        include "master/tambah_penyewa.php";
                        break;
                    case 'penyewa_edit':
                        include "master/edit_penyewa.php";
                        break;
                    case 'pembayaran_form':
                        include "transaksi/tambah_pembayaran.php";
                        break;
                    case 'edit_pembayaran':
                        include "transaksi/edit_pembayaran.php";
                        break;    
                    case 'pengaturan_akun':
                        include "auth/pengaturan_akun.php";
                        break;
                    case 'delete_kontrakan':
                        include "master/delete_kontrakan.php";
                        break;     
                    case 'delete_penyewa':
                        include "master/delete_penyewa.php";
                        break;     
                    default:
                        include "dashboard.php";
                        break;
                        
                }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('clock').textContent = `${hours}:${minutes}:${seconds}`;
    }
    setInterval(updateClock, 1000);
    updateClock();
        AOS.init({
            duration: 800, // durasi animasi 0.8 detik
            once: true     // animasi hanya jalan sekali saat scroll
        });
    </script>
</body>
</html>
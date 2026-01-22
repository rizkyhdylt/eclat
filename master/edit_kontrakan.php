<?php
// 1. Ambil ID dari URL
$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

// 2. Logika Update Data (Dijalankan saat tombol simpan diklik)
if (isset($_POST['update'])) {
    $tipe_kamar = mysqli_real_escape_string($conn, $_POST['tipe_kamar']);
    $harga      = mysqli_real_escape_string($conn, $_POST['harga']);

    $query_update = "UPDATE kontrakan SET tipe_kamar = '$tipe_kamar', harga = '$harga' WHERE id_kontrakan = '$id'";
    
    if (mysqli_query($conn, $query_update)) {
        echo "<script>
                alert('Data unit berhasil diperbarui!');
                window.location.href='index.php?page=kontrakan';
              </script>";
    } else {
        echo "<div class='alert alert-danger'>Gagal memperbarui data: " . mysqli_error($conn) . "</div>";
    }
}

// 3. Ambil data lama untuk ditampilkan di Form
$query_get = mysqli_query($conn, "SELECT * FROM kontrakan WHERE id_kontrakan = '$id'");
$data_lama = mysqli_fetch_assoc($query_get);

// Jika ID tidak ditemukan
if (!$data_lama) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='index.php?page=kontrakan';</script>";
    exit;
}
?>

<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-right">
    <div>
        <h4 class="fw-bold mb-1">
            <i class="fa-solid fa-edit text-warning me-2"></i>
            Edit Unit Kontrakan
        </h4>
        <p class="text-muted small mb-0">
            <i class="fa-solid fa-hashtag me-1"></i>
            ID Unit: <span class="badge bg-secondary"><?= $id ?></span>
        </p>
    </div>
    <a href="index.php?page=kontrakan" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<!-- Form Card -->
<div class="row justify-content-center" data-aos="fade-up">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="fa-solid fa-pen-to-square text-warning me-2"></i>
                    Form Edit Data
                </h6>
            </div>
            <div class="card-body p-4">
                <form action="" method="POST">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fa-solid fa-bed text-warning me-1"></i>
                            Tipe Kamar / Nama Unit
                        </label>
                        <input 
                            type="text" 
                            name="tipe_kamar" 
                            class="form-control form-control-lg" 
                            value="<?= htmlspecialchars($data_lama['tipe_kamar']) ?>" 
                            required
                        >
                        <small class="text-muted">Ubah nama atau kode identifikasi kamar</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fa-solid fa-money-bill-wave text-success me-1"></i>
                            Harga Sewa (Per Bulan)
                        </label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text">Rp</span>
                            <input 
                                type="number" 
                                name="harga" 
                                class="form-control" 
                                value="<?= $data_lama['harga'] ?>" 
                                required
                            >
                        </div>
                        <small class="text-muted">Ubah harga sewa bulanan dalam rupiah</small>
                    </div>

                    <div class="alert alert-warning bg-warning bg-opacity-10 border-0 mb-4">
                        <i class="fa-solid fa-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong> Perubahan harga akan mempengaruhi pembayaran periode berikutnya.
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" name="update" class="btn btn-warning btn-lg flex-fill text-white">
                            <i class="fa-solid fa-check me-2"></i>Update Data
                        </button>
                        <a href="index.php?page=kontrakan" class="btn btn-outline-secondary btn-lg">
                            <i class="fa-solid fa-times me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Preview Card -->
        <div class="card border-0 shadow-sm mt-4 bg-light">
            <div class="card-body p-3">
                <h6 class="fw-bold mb-3">
                    <i class="fa-solid fa-info-circle text-primary me-2"></i>
                    Data Sebelumnya
                </h6>
                <div class="row">
                    <div class="col-6">
                        <small class="text-muted d-block">Tipe Kamar</small>
                        <p class="mb-2 fw-semibold"><?= htmlspecialchars($data_lama['tipe_kamar']) ?></p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Harga Sewa</small>
                        <p class="mb-2 fw-semibold">Rp <?= number_format($data_lama['harga'], 0, ',', '.') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Card Styling */
.card {
    border-radius: 12px;
}

/* Form Controls */
.form-control,
.input-group-text {
    border-radius: 8px;
}

.form-control:focus {
    border-color: #f59e0b;
    box-shadow: 0 0 0 0.2rem rgba(245, 158, 11, 0.15);
}

.form-control-lg {
    padding: 0.75rem 1rem;
}

.input-group-lg .input-group-text {
    font-weight: 600;
    background-color: #f8fafc;
}

/* Button Styling */
.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-lg {
    padding: 0.75rem 1.5rem;
}

.btn-warning {
    background-color: #f59e0b;
    border-color: #f59e0b;
}

.btn-warning:hover {
    background-color: #d97706;
    border-color: #d97706;
}

/* Alert Styling */
.alert {
    border-radius: 8px;
}

/* Badge */
.badge {
    font-weight: 500;
    padding: 0.35rem 0.75rem;
}

/* Labels */
.form-label {
    margin-bottom: 0.5rem;
    color: #475569;
}

/* Small text */
small.text-muted {
    font-size: 0.8rem;
    display: block;
    margin-top: 0.25rem;
}

/* Responsive */
@media (max-width: 768px) {
    .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .d-flex.gap-2 .btn {
        width: 100%;
    }
}
</style>
<?php
// Logika simpan data
if (isset($_POST['simpan'])) {
    $tipe_kamar  = mysqli_real_escape_string($conn, $_POST['tipe_kamar']);
    $harga       = mysqli_real_escape_string($conn, $_POST['harga']);
    $jatuh_tempo = mysqli_real_escape_string($conn, $_POST['jatuh_tempo']);

    // Menambahkan data baru (Termasuk kolom jatuh_tempo)
    $query = "INSERT INTO kontrakan (tipe_kamar, harga, jatuh_tempo) 
              VALUES ('$tipe_kamar', '$harga', '$jatuh_tempo')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Data unit berhasil ditambahkan!');
                window.location.href='index.php?page=kontrakan';
              </script>";
    } else {
        echo "<div class='alert alert-danger'>Gagal: " . mysqli_error($conn) . "</div>";
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-right">
    <div>
        <h4 class="fw-bold mb-1">
            <i class="fa-solid fa-plus-circle text-primary me-2"></i>
            Tambah Unit Kontrakan
        </h4>
        <p class="text-muted small mb-0">Masukkan informasi unit kamar dan aturan jatuh tempo</p>
    </div>
    <a href="index.php?page=kontrakan" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row justify-content-center" data-aos="fade-up">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="fa-solid fa-edit text-primary me-2"></i>
                    Form Input Data Unit
                </h6>
            </div>
            <div class="card-body p-4">
                <form action="" method="POST">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fa-solid fa-bed text-primary me-1"></i>
                            Tipe Kamar / Nama Unit
                        </label>
                        <input type="text" name="tipe_kamar" class="form-control form-control-lg" placeholder="Contoh: Kamar A-01" required>
                        <small class="text-muted">Masukkan nama atau kode identifikasi kamar</small>
                    </div>

                    <div class="row">
                        <div class="col-md-7 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-money-bill-wave text-success me-1"></i>
                                Harga Sewa (Per Bulan)
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga" class="form-control" placeholder="1000000" required>
                            </div>
                        </div>

                        <div class="col-md-5 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-calendar-check text-danger me-1"></i>
                                Tanggal Jatuh Tempo
                            </label>
                            <input type="number" name="jatuh_tempo" class="form-control form-control-lg" min="1" max="31" placeholder="Misal: 10" required>
                            <small class="text-muted">Isi angka tanggal (1-31)</small>
                        </div>
                    </div>

                    <div class="alert alert-info bg-info bg-opacity-10 border-0 mb-4 small">
                        <i class="fa-solid fa-info-circle me-2"></i>
                        Tanggal jatuh tempo digunakan sebagai patokan denda atau status keterlambatan penyewa setiap bulannya.
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" name="simpan" class="btn btn-primary btn-lg flex-fill">
                            <i class="fa-solid fa-save me-2"></i>Simpan Data Unit
                        </button>
                        <a href="index.php?page=kontrakan" class="btn btn-outline-secondary btn-lg">
                            <i class="fa-solid fa-times me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-4 bg-light">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary">
                            <i class="fa-solid fa-lightbulb"></i>
                        </div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-1 fw-bold">Tips Pengisian</h6>
                        <small class="text-muted">
                            • <strong>Jatuh Tempo:</strong> Jika diisi 10, maka tagihan muncul setiap tanggal 10.<br>
                            • <strong>Konsistensi:</strong> Gunakan pola nama unit yang rapi agar mudah dianalisis.<br>
                            • <strong>Harga:</strong> Masukkan hanya angka tanpa titik atau koma.
                        </small>
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
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
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

/* Icon Box */
.icon-box {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    font-size: 1.25rem;
}

/* Alert Styling */
.alert {
    border-radius: 8px;
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
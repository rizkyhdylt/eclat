<?php
// 1. QUERY UNTUK MENGAMBIL UNIT YANG KOSONG (id_penyewa IS NULL)
$kamar_kosong = mysqli_query($conn, "SELECT * FROM kontrakan WHERE id_penyewa IS NULL");

if (isset($_POST['simpan'])) {
    $nama         = mysqli_real_escape_string($conn, $_POST['nama_penyewa']);
    $no_hp        = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $alamat       = mysqli_real_escape_string($conn, $_POST['alamat']);
    $id_kontrakan = mysqli_real_escape_string($conn, $_POST['id_kontrakan']);

    // Mulai Transaksi
    mysqli_begin_transaction($conn);

    try {
        // A. Insert ke tabel penyewa (SEKARANG DISERTAI id_kontrakan)
        $query_penyewa = "INSERT INTO penyewa (nama_penyewa, no_hp, alamat, id_kontrakan) 
                          VALUES ('$nama', '$no_hp', '$alamat', '$id_kontrakan')";
        mysqli_query($conn, $query_penyewa);
        
        // Ambil ID penyewa yang baru saja masuk
        $id_penyewa_baru = mysqli_insert_id($conn);

        // B. Update tabel kontrakan (Tetap dilakukan agar status unit berubah)
        $query_update_unit = "UPDATE kontrakan SET id_penyewa = '$id_penyewa_baru' WHERE id_kontrakan = '$id_kontrakan'";
        mysqli_query($conn, $query_update_unit);

        // Simpan perubahan
        mysqli_commit($conn);

        echo "<script>alert('Penyewa berhasil ditambahkan!'); window.location.href='index.php?page=penyewa';</script>";
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "<div class='alert alert-danger'>Gagal menyimpan data: " . $e->getMessage() . "</div>";
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-right">
    <div>
        <h4 class="fw-bold mb-1">
            <i class="fa-solid fa-user-plus text-primary me-2"></i>
            Tambah Penyewa Baru
        </h4>
        <p class="text-muted small mb-0">Lengkapi identitas dan pilih unit kontrakan</p>
    </div>
    <a href="index.php?page=penyewa" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row justify-content-center" data-aos="fade-up">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="fa-solid fa-id-card text-primary me-2"></i>
                    Form Pendaftaran Penghuni
                </h6>
            </div>
            <div class="card-body p-4">
                <form action="" method="POST">
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fa-solid fa-user text-primary me-1"></i> Nama Lengkap
                        </label>
                        <input type="text" name="nama_penyewa" class="form-control form-control-lg" placeholder="Masukkan nama sesuai KTP" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fa-brands fa-whatsapp text-success me-1"></i> Nomor WhatsApp/HP
                        </label>
                        <input type="tel" name="no_hp" class="form-control form-control-lg" placeholder="08xxxxxxxxxx" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fa-solid fa-building-circle-check text-info me-1"></i> Pilih Unit Kontrakan
                        </label>
                        <select name="id_kontrakan" class="form-select form-control-lg" required>
                            <option value="">-- Pilih Unit yang Tersedia --</option>
                            <?php if(mysqli_num_rows($kamar_kosong) > 0): ?>
                                <?php while($k = mysqli_fetch_assoc($kamar_kosong)): ?>
                                    <option value="<?= $k['id_kontrakan'] ?>">
                                        <?= htmlspecialchars($k['tipe_kamar']) ?> - (Rp <?= number_format($k['harga'], 0, ',', '.') ?>)
                                    </option>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <option value="" disabled>Maaf, semua unit sudah penuh</option>
                            <?php endif; ?>
                        </select>
                        <small class="text-muted">Hanya menampilkan unit yang berstatus "Kosong"</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fa-solid fa-location-dot text-danger me-1"></i> Alamat Asal
                        </label>
                        <textarea name="alamat" class="form-control form-control-lg" rows="3" placeholder="Alamat lengkap sesuai KTP" required></textarea>
                    </div>

                    <div class="alert alert-info bg-info bg-opacity-10 border-0 mb-4">
                        <i class="fa-solid fa-circle-info me-2"></i>
                        Status unit akan otomatis berubah menjadi <strong>"Terisi"</strong> setelah data disimpan.
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" name="simpan" class="btn btn-primary btn-lg flex-fill">
                            <i class="fa-solid fa-save me-2"></i>Simpan & Tempati Unit
                        </button>
                        <a href="index.php?page=penyewa" class="btn btn-outline-secondary btn-lg">
                            <i class="fa-solid fa-times me-2"></i>Batal
                        </a>
                    </div>
                </form>
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
.form-control {
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
}

.form-control-lg {
    padding: 0.75rem 1rem;
}

/* Textarea */
textarea.form-control {
    resize: vertical;
    min-height: 100px;
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
    flex-shrink: 0;
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
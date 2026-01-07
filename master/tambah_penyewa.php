<?php
if (isset($_POST['simpan'])) {
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_penyewa']);
    $no_hp  = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    $query = "INSERT INTO penyewa (nama_penyewa, no_hp, alamat) VALUES ('$nama', '$no_hp', '$alamat')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Penyewa berhasil ditambahkan!'); window.location.href='index.php?page=penyewa';</script>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-right">
    <div>
        <h4 class="fw-bold mb-1 text-dark">Tambah Penyewa Baru</h4>
        <p class="text-muted small mb-0">Lengkapi identitas calon penghuni.</p>
    </div>
    <a href="index.php?page=penyewa" class="btn btn-light px-4 shadow-sm rounded-pill border">
        <i class="fa-solid fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row justify-content-center" data-aos="fade-up">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4">
                <form action="" method="POST">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small uppercase">Nama Lengkap</label>
                        <input type="text" name="nama_penyewa" class="form-control bg-light border-0 p-3" placeholder="Masukkan nama sesuai KTP" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small uppercase">Nomor WhatsApp/HP</label>
                        <input type="number" name="no_hp" class="form-control bg-light border-0 p-3" placeholder="08xxxxxxxx" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small uppercase">Alamat Asal</label>
                        <textarea name="alamat" class="form-control bg-light border-0 p-3" rows="3" placeholder="Alamat lengkap penyewa" required></textarea>
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="simpan" class="btn btn-primary p-3 shadow-sm rounded-3 fw-bold">
                            <i class="fa-solid fa-save me-2"></i>Simpan Data Penyewa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
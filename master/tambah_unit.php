<?php
// 1. Logika Pemrosesan Data (Dijalankan saat tombol simpan diklik)
if (isset($_POST['simpan'])) {
    $tipe_kamar = mysqli_real_escape_string($conn, $_POST['tipe_kamar']);
    $harga      = mysqli_real_escape_string($conn, $_POST['harga']);

    $query = "INSERT INTO kontrakan (tipe_kamar, harga) VALUES ('$tipe_kamar', '$harga')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Data berhasil ditambahkan!');
                window.location.href='index.php?page=kontrakan';
              </script>";
    } else {
        echo "<div class='alert alert-danger'>Gagal menambahkan data: " . mysqli_error($conn) . "</div>";
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-right">
    <div>
        <h4 class="fw-bold mb-1 text-dark">Tambah Unit Kontrakan</h4>
        <p class="text-muted small mb-0">Masukkan detail tipe kamar dan harga sewa baru.</p>
    </div>
    <a href="index.php?page=kontrakan" class="btn btn-light px-4 shadow-sm rounded-pill border">
        <i class="fa-solid fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row justify-content-center" data-aos="fade-up">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4">
                <form action="" method="POST">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small uppercase">Tipe Kamar / Nama Unit</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="fa-solid fa-bed text-primary"></i></span>
                            <input type="text" name="tipe_kamar" class="form-control bg-light border-0 p-3" placeholder="Contoh: Kamar A-01" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small uppercase">Harga Sewa (Per Bulan)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 text-success fw-bold">Rp</span>
                            <input type="number" name="harga" class="form-control bg-light border-0 p-3" placeholder="Contoh: 1000000" required>
                        </div>
                    </div>

                    <hr class="my-4 opacity-50">

                    <div class="d-grid">
                        <button type="submit" name="simpan" class="btn btn-primary p-3 shadow-sm rounded-3 fw-bold">
                            <i class="fa-solid fa-save me-2"></i>Simpan Unit Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
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

<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-right">
    <div>
        <h4 class="fw-bold mb-1 text-dark">Edit Unit Kontrakan</h4>
        <p class="text-muted small mb-0">Perbarui informasi tipe kamar atau harga sewa unit ID: #<?= $id ?></p>
    </div>
    <a href="index.php?page=kontrakan" class="btn btn-light px-4 shadow-sm rounded-pill border">
        <i class="fa-solid fa-arrow-left me-2"></i>Batal
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
                            <span class="input-group-text bg-light border-0"><i class="fa-solid fa-bed text-warning"></i></span>
                            <input type="text" name="tipe_kamar" class="form-control bg-light border-0 p-3" 
                                   value="<?= htmlspecialchars($data_lama['tipe_kamar']) ?>" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small uppercase">Harga Sewa (Per Bulan)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 text-success fw-bold">Rp</span>
                            <input type="number" name="harga" class="form-control bg-light border-0 p-3" 
                                   value="<?= $data_lama['harga'] ?>" required>
                        </div>
                    </div>

                    <hr class="my-4 opacity-50">

                    <div class="d-grid">
                        <button type="submit" name="update" class="btn btn-warning p-3 shadow-sm rounded-3 fw-bold text-white">
                            <i class="fa-solid fa-pen-to-square me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
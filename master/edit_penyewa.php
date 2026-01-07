<?php
// 1. Ambil ID Penyewa dari URL
$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

// 2. Logika Update Data (Dijalankan saat tombol simpan diklik)
if (isset($_POST['update'])) {
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_penyewa']);
    $no_hp  = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    $query_update = "UPDATE penyewa SET 
                        nama_penyewa = '$nama', 
                        no_hp = '$no_hp', 
                        alamat = '$alamat' 
                     WHERE id_penyewa = '$id'";
    
    if (mysqli_query($conn, $query_update)) {
        echo "<script>
                alert('Data penyewa berhasil diperbarui!');
                window.location.href='index.php?page=penyewa';
              </script>";
    } else {
        echo "<div class='alert alert-danger'>Gagal memperbarui data: " . mysqli_error($conn) . "</div>";
    }
}

// 3. Ambil data lama untuk ditampilkan di Form
$query_get = mysqli_query($conn, "SELECT * FROM penyewa WHERE id_penyewa = '$id'");
$data_lama = mysqli_fetch_assoc($query_get);

// Jika ID tidak ditemukan di database
if (!$data_lama) {
    echo "<script>alert('Data penyewa tidak ditemukan!'); window.location.href='index.php?page=penyewa';</script>";
    exit;
}
?>

<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-right">
    <div>
        <h4 class="fw-bold mb-1 text-dark">Edit Data Penyewa</h4>
        <p class="text-muted small mb-0">Perbarui informasi identitas penyewa ID: #PNY-<?= $id ?></p>
    </div>
    <a href="index.php?page=penyewa" class="btn btn-light px-4 shadow-sm rounded-pill border">
        <i class="fa-solid fa-arrow-left me-2"></i>Batal
    </a>
</div>

<div class="row justify-content-center" data-aos="fade-up">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4">
                <form action="" method="POST">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small uppercase">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="fa-solid fa-user text-warning"></i></span>
                            <input type="text" name="nama_penyewa" class="form-control bg-light border-0 p-3" 
                                   value="<?= htmlspecialchars($data_lama['nama_penyewa']) ?>" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small uppercase">Nomor WhatsApp/HP</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="fa-solid fa-phone text-warning"></i></span>
                            <input type="number" name="no_hp" class="form-control bg-light border-0 p-3" 
                                   value="<?= $data_lama['no_hp'] ?>" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small uppercase">Alamat Asal</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="fa-solid fa-location-dot text-warning"></i></span>
                            <textarea name="alamat" class="form-control bg-light border-0 p-3" rows="3" required><?= htmlspecialchars($data_lama['alamat']) ?></textarea>
                        </div>
                    </div>

                    <hr class="my-4 opacity-50">

                    <div class="d-grid">
                        <button type="submit" name="update" class="btn btn-warning p-3 shadow-sm rounded-3 fw-bold text-white">
                            <i class="fa-solid fa-user-check me-2"></i>Perbarui Data Penyewa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
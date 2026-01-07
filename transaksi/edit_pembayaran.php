<?php
// 1. Ambil ID Transaksi dari URL
$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

// 2. Logika Update Data
if (isset($_POST['update'])) {
    $id_penyewa     = $_POST['id_penyewa'];
    $id_kontrakan   = $_POST['id_kontrakan'];
    $bulan          = $_POST['bulan'];
    $tahun          = $_POST['tahun'];
    $jatuh_tempo    = $_POST['jatuh_tempo'];
    $tanggal_bayar  = $_POST['tanggal_bayar'];
    
    // Logika penentuan status otomatis
    if (empty($tanggal_bayar) || $tanggal_bayar == '0000-00-00') {
        $status = "Belum Bayar";
    } elseif ($tanggal_bayar > $jatuh_tempo) {
        $status = "Terlambat";
    } else {
        $status = "Lunas";
    }

    $query_update = "UPDATE pembayaran SET 
                        id_penyewa = '$id_penyewa', 
                        id_kontrakan = '$id_kontrakan', 
                        bulan = '$bulan', 
                        tahun = '$tahun', 
                        jatuh_tempo = '$jatuh_tempo', 
                        tanggal_bayar = '$tanggal_bayar', 
                        status = '$status' 
                     WHERE id_pembayaran = '$id'";
    
    if (mysqli_query($conn, $query_update)) {
        echo "<script>
                alert('Transaksi berhasil diperbarui! Status menjadi: $status');
                window.location.href='index.php?page=pembayaran';
              </script>";
    } else {
        echo "<div class='alert alert-danger'>Gagal memperbarui: " . mysqli_error($conn) . "</div>";
    }
}

// 3. Ambil data lama untuk ditampilkan di Form
$query_get = mysqli_query($conn, "SELECT * FROM pembayaran WHERE id_pembayaran = '$id'");
$data_lama = mysqli_fetch_assoc($query_get);

if (!$data_lama) {
    echo "<script>alert('Data transaksi tidak ditemukan!'); window.location.href='index.php?page=pembayaran';</script>";
    exit;
}

// 4. Ambil data pendukung untuk Dropdown
$penyewa_list = mysqli_query($conn, "SELECT * FROM penyewa ORDER BY nama_penyewa ASC");
$kontrakan_list = mysqli_query($conn, "SELECT * FROM kontrakan ORDER BY tipe_kamar ASC");
?>

<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-right">
    <div>
        <h4 class="fw-bold mb-1 text-dark">Edit Transaksi Pembayaran</h4>
        <p class="text-muted small mb-0">Sesuaikan data pembayaran atau ubah tanggal bayar.</p>
    </div>
    <a href="index.php?page=pembayaran" class="btn btn-light px-4 shadow-sm rounded-pill border">
        <i class="fa-solid fa-arrow-left me-2"></i>Batal
    </a>
</div>

<div class="row" data-aos="fade-up">
    <div class="col-md-10 mx-auto">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4">
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold text-muted small uppercase">Penyewa</label>
                            <select name="id_penyewa" class="form-select bg-light border-0 p-3" required>
                                <?php while($p = mysqli_fetch_assoc($penyewa_list)): ?>
                                    <option value="<?= $p['id_penyewa'] ?>" <?= ($p['id_penyewa'] == $data_lama['id_penyewa']) ? 'selected' : '' ?>>
                                        <?= $p['nama_penyewa'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold text-muted small uppercase">Unit Kontrakan</label>
                            <select name="id_kontrakan" class="form-select bg-light border-0 p-3" required>
                                <?php while($k = mysqli_fetch_assoc($kontrakan_list)): ?>
                                    <option value="<?= $k['id_kontrakan'] ?>" <?= ($k['id_kontrakan'] == $data_lama['id_kontrakan']) ? 'selected' : '' ?>>
                                        <?= $k['tipe_kamar'] ?> - Rp <?= number_format($k['harga'],0,',','.') ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="col-md-3 mb-4">
                            <label class="form-label fw-bold text-muted small uppercase">Bulan</label>
                            <select name="bulan" class="form-select bg-light border-0 p-3">
                                <?php
                                $bulan_array = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                                foreach($bulan_array as $bln) {
                                    $selected = ($bln == $data_lama['bulan']) ? 'selected' : '';
                                    echo "<option value='$bln' $selected>$bln</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-3 mb-4">
                            <label class="form-label fw-bold text-muted small uppercase">Tahun</label>
                            <input type="number" name="tahun" class="form-control bg-light border-0 p-3" value="<?= $data_lama['tahun'] ?>" required>
                        </div>

                        <div class="col-md-3 mb-4">
                            <label class="form-label fw-bold text-muted small uppercase">Jatuh Tempo</label>
                            <input type="date" name="jatuh_tempo" class="form-control bg-light border-0 p-3" value="<?= $data_lama['jatuh_tempo'] ?>" required>
                        </div>

                        <div class="col-md-3 mb-4">
                            <label class="form-label fw-bold text-muted small uppercase">Tanggal Bayar</label>
                            <input type="date" name="tanggal_bayar" class="form-control bg-light border-0 p-3" value="<?= $data_lama['tanggal_bayar'] ?>">
                            <small class="text-muted">Status saat ini: **<?= $data_lama['status'] ?>**</small>
                        </div>
                    </div>

                    <hr class="my-4 opacity-50">

                    <div class="d-grid">
                        <button type="submit" name="update" class="btn btn-warning p-3 shadow-sm rounded-3 fw-bold text-white">
                            <i class="fa-solid fa-pen-to-square me-2"></i>Perbarui Data Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
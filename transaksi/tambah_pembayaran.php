<?php
// 1. Logika Simpan Data
if (isset($_POST['simpan'])) {
    $id_penyewa     = $_POST['id_penyewa'];
    $id_kontrakan   = $_POST['id_kontrakan'];
    $bulan          = $_POST['bulan'];
    $tahun          = $_POST['tahun'];
    $jatuh_tempo    = $_POST['jatuh_tempo'];
    $tanggal_bayar  = $_POST['tanggal_bayar'];
    
    // Logika penentuan status otomatis
    // Jika tanggal bayar kosong atau lebih besar dari jatuh tempo, status = Terlambat/Belum Bayar
    if (empty($tanggal_bayar)) {
        $status = "Belum Bayar";
    } elseif ($tanggal_bayar > $jatuh_tempo) {
        $status = "Terlambat";
    } else {
        $status = "Lunas";
    }

    $query = "INSERT INTO pembayaran (id_penyewa, id_kontrakan, bulan, tahun, jatuh_tempo, tanggal_bayar, status) 
              VALUES ('$id_penyewa', '$id_kontrakan', '$bulan', '$tahun', '$jatuh_tempo', '$tanggal_bayar', '$status')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Transaksi berhasil dicatat! Status: $status');
                window.location.href='index.php?page=pembayaran';
              </script>";
    } else {
        echo "<div class='alert alert-danger'>Gagal menyimpan: " . mysqli_error($conn) . "</div>";
    }
}

// 2. Ambil data untuk Dropdown
$penyewa_list = mysqli_query($conn, "SELECT * FROM penyewa ORDER BY nama_penyewa ASC");
$kontrakan_list = mysqli_query($conn, "SELECT * FROM kontrakan ORDER BY tipe_kamar ASC");
?>

<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-right">
    <div>
        <h4 class="fw-bold mb-1 text-dark">Tambah Transaksi Baru</h4>
        <p class="text-muted small mb-0">Catat pembayaran sewa bulanan penyewa.</p>
    </div>
    <a href="index.php?page=pembayaran" class="btn btn-light px-4 shadow-sm rounded-pill border">
        <i class="fa-solid fa-arrow-left me-2"></i>Kembali
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
                                <option value="">-- Pilih Penyewa --</option>
                                <?php while($p = mysqli_fetch_assoc($penyewa_list)): ?>
                                    <option value="<?= $p['id_penyewa'] ?>"><?= $p['nama_penyewa'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold text-muted small uppercase">Unit Kontrakan</label>
                            <select name="id_kontrakan" class="form-select bg-light border-0 p-3" required>
                                <option value="">-- Pilih Kamar --</option>
                                <?php while($k = mysqli_fetch_assoc($kontrakan_list)): ?>
                                    <option value="<?= $k['id_kontrakan'] ?>"><?= $k['tipe_kamar'] ?> - Rp <?= number_format($k['harga'],0,',','.') ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="col-md-3 mb-4">
                            <label class="form-label fw-bold text-muted small uppercase">Bulan</label>
                            <select name="bulan" class="form-select bg-light border-0 p-3" required>
                                <?php
                                $bulan_array = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                                foreach($bulan_array as $bln) echo "<option value='$bln'>$bln</option>";
                                ?>
                            </select>
                        </div>

                        <div class="col-md-3 mb-4">
                            <label class="form-label fw-bold text-muted small uppercase">Tahun</label>
                            <input type="number" name="tahun" class="form-control bg-light border-0 p-3" value="<?= date('Y') ?>" required>
                        </div>

                        <div class="col-md-3 mb-4">
                            <label class="form-label fw-bold text-muted small uppercase">Jatuh Tempo</label>
                            <input type="date" name="jatuh_tempo" class="form-control bg-light border-0 p-3" required>
                        </div>

                        <div class="col-md-3 mb-4">
                            <label class="form-label fw-bold text-muted small uppercase">Tanggal Bayar</label>
                            <input type="date" name="tanggal_bayar" class="form-control bg-light border-0 p-3">
                            <small class="text-muted">Kosongkan jika belum bayar.</small>
                        </div>
                    </div>

                    <hr class="my-4 opacity-50">

                    <div class="d-grid">
                        <button type="submit" name="simpan" class="btn btn-primary p-3 shadow-sm rounded-3 fw-bold">
                            <i class="fa-solid fa-save me-2"></i>Simpan Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
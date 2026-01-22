<?php
// Pastikan koneksi $conn sudah ada dari index.php
// Query disesuaikan dengan kolom tipe_kamar sesuai struktur tabel Anda
$data = mysqli_query($conn, "SELECT * FROM kontrakan ORDER BY id_kontrakan DESC");

// Cek jika query gagal
if (!$data) {
    echo "<div class='alert alert-danger'>Kesalahan Query: " . mysqli_error($conn) . "</div>";
}
?>

<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-right">
    <div>
        <h4 class="fw-bold m-0 text-dark">Daftar Kontrakan</h4>
        <p class="text-muted small mb-0">Kelola unit kontrakan dan informasi harga sewa.</p>
    </div>
    <a href="index.php?page=kontrakan_form" class="btn btn-primary px-4 shadow-sm rounded-pill">
        <i class="fa-solid fa-plus me-2"></i>Tambah Unit
    </a>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 15px;" data-aos="fade-up">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted">No</th>
                        <th class="py-3 text-muted">Tipe Kamar</th>
                        <th class="py-3 text-muted">Harga / Bulan</th>
                        <th class="text-center pe-4 text-muted">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1; 
                    if (mysqli_num_rows($data) > 0) :
                        while ($row = mysqli_fetch_assoc($data)) : 
                    ?>
                    <tr class="stat-card">
                        <td class="ps-4 text-muted"><?= $no++ ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-primary bg-opacity-10 text-primary me-3" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                    <i class="fa-solid fa-bed"></i>
                                </div>
                                <div>
                                    <span class="fw-bold text-dark d-block"><?= htmlspecialchars($row['tipe_kamar']) ?></span>
                                    <span class="text-muted small">ID: #<?= $row['id_kontrakan'] ?></span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="fw-bold text-success">
                                Rp <?= number_format($row['harga'], 0, ',', '.') ?>
                            </span>
                        </td>
                        <td class="text-center pe-4">
                            <div class="btn-group">
                                <a href="index.php?page=kontrakan_edit&id=<?= $row['id_kontrakan'] ?>" 
                                   class="btn btn-sm btn-light text-warning hover-scale" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <a href="master/delete_unit.php?hapus=<?= $row['id_kontrakan'] ?>" 
                                   class="btn btn-sm btn-light text-danger hover-scale" 
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus unit ini?')" title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        endwhile; 
                    else : 
                    ?>
                    <tr>
                        <td colspan="4" class="text-center p-5 text-muted">
                            <i class="fa-solid fa-house-circle-xmark d-block fs-1 mb-3 opacity-25"></i>
                            Belum ada data unit kontrakan.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Style tambahan agar senada dengan dashboard */
    .hover-scale {
        transition: transform 0.2s;
        border-radius: 8px;
        margin: 0 2px;
    }
    .hover-scale:hover {
        transform: scale(1.1);
        background-color: white !important;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .table-hover tbody tr:hover {
        background-color: rgba(59, 130, 246, 0.02);
    }
</style>
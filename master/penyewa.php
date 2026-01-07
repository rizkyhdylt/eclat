<?php
// Query diperbaiki: Hanya mengambil dari tabel penyewa karena di tabel penyewa Anda tidak ada id_kontrakan
$query_p = "SELECT * FROM penyewa ORDER BY id_penyewa DESC";
$result_p = mysqli_query($conn, $query_p);

if (!$result_p) {
    echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
}
?>

<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-right">
    <div>
        <h4 class="fw-bold mb-1 text-dark">Data Penyewa</h4>
        <p class="text-muted small mb-0">Kelola informasi identitas penghuni kontrakan.</p>
    </div>
    <a href="index.php?page=penyewa_tambah" class="btn btn-primary px-4 shadow-sm rounded-pill">
        <i class="fa-solid fa-user-plus me-2"></i>Tambah Penyewa
    </a>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 15px;" data-aos="fade-up">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted">No</th>
                        <th class="py-3 text-muted">Nama Penyewa</th>
                        <th class="py-3 text-muted">No. Telepon</th>
                        <th class="py-3 text-muted">Alamat Asal</th>
                        <th class="text-center pe-4 text-muted">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1; 
                    if (mysqli_num_rows($result_p) > 0) :
                        while ($row = mysqli_fetch_assoc($result_p)) : 
                    ?>
                    <tr>
                        <td class="ps-4 text-muted"><?= $no++ ?></td>
                        <td>
                            <div class="fw-bold text-dark"><?= htmlspecialchars($row['nama_penyewa']) ?></div>
                            <div class="text-muted small">ID: #PNY-<?= $row['id_penyewa'] ?></div>
                        </td>
                        <td><i class="fa-solid fa-phone me-2 text-primary small"></i><?= $row['no_hp'] ?></td>
                        <td class="small text-muted"><?= htmlspecialchars($row['alamat']) ?></td>
                        <td class="text-center pe-4">
                            <div class="btn-group shadow-sm rounded">
                                <a href="index.php?page=penyewa_edit&id=<?= $row['id_penyewa'] ?>" class="btn btn-sm btn-white text-warning border-end">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <a href="master/penyewa_proses.php?hapus=<?= $row['id_penyewa'] ?>" class="btn btn-sm btn-white text-danger" onclick="return confirm('Hapus data penyewa ini?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr>
                        <td colspan="5" class="text-center p-5 text-muted">Belum ada data penyewa.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
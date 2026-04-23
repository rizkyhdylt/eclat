<?php
include "../config/config.php"; // Sesuaikan path koneksi Anda

if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);

    // LANGKAH 1: Amankan Integritas Data
    // Set id_penyewa menjadi NULL di tabel kontrakan agar unit otomatis berstatus KOSONG
    $update_unit = "UPDATE kontrakan SET id_penyewa = NULL WHERE id_penyewa = '$id'";
    mysqli_query($conn, $update_unit);

    // LANGKAH 2: Hapus data penyewa
    $hapus_penyewa = "DELETE FROM penyewa WHERE id_penyewa = '$id'";
    
    if (mysqli_query($conn, $hapus_penyewa)) {
        // Berhasil: Alihkan kembali ke halaman utama penyewa
        echo "<script>
                window.location.href='../index.php?page=penyewa&status=success_hapus';
              </script>";
    } else {
        // Gagal
        echo "<script>
                alert('Gagal menghapus data: " . mysqli_error($conn) . "');
                window.location.href='../index.php?page=penyewa';
              </script>";
    }
} else {
    header("Location: ../index.php?page=penyewa");
}
?>

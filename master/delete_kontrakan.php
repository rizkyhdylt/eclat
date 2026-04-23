<?php
// Pastikan koneksi tersedia - sesuaikan path jika folder berbeda
include "../config/config.php"; 

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // 1. VALIDASI: Cek apakah unit masih terisi penyewa
    $check_query = "SELECT id_penyewa FROM kontrakan WHERE id_kontrakan = '$id'";
    $check_result = mysqli_query($conn, $check_query);
    $data_unit = mysqli_fetch_assoc($check_result);

    if (!empty($data_unit['id_penyewa'])) {
        // Jika ada penyewa, batalkan hapus (keamanan data)
        echo "<script>
                alert('Gagal! Unit masih terisi. Silakan hapus atau pindahkan penyewa terlebih dahulu.');
                window.location.href='../index.php?page=kontrakan';
              </script>";
        exit;
    }

    // 2. PROSES HAPUS
    $query_hapus = "DELETE FROM kontrakan WHERE id_kontrakan = '$id'";
    
    if (mysqli_query($conn, $query_hapus)) {
        // Berhasil: Redirect menggunakan JS untuk menghindari error "Headers Already Sent"
        echo "<script>
                window.location.href='../index.php?page=kontrakan&status=success_hapus';
              </script>";
    } else {
        // Jika gagal karena constraint database
        echo "<script>
                alert('Gagal menghapus data: Unit ini mungkin memiliki riwayat transaksi.');
                window.location.href='../index.php?page=kontrakan';
              </script>";
    }
} else {
    // Jika akses tanpa ID
    echo "<script>window.location.href='../index.php?page=kontrakan';</script>";
}
?>

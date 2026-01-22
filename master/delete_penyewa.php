<?php
// Hubungkan ke file koneksi database Anda
include "../config/config.php"; 

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    // 1. Jalankan perintah hapus data berdasarkan ID
    $query_hapus = "DELETE FROM penyewa WHERE id_penyewa = '$id'";
    $eksekusi = mysqli_query($conn, $query_hapus);

    if ($eksekusi) {
        // 2. LOGIKA AGAR ID TETAP URUT: 
        // Mereset Auto Increment agar ID baru nantinya mengisi celah angka yang hilang
        mysqli_query($conn, "ALTER TABLE penyewa AUTO_INCREMENT = 1");

        echo "<script>
                alert('Data penyewa berhasil dihapus dan urutan ID telah diperbarui!');
                window.location.href='../index.php?page=penyewa';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data: " . mysqli_error($conn) . "');
                window.location.href='../index.php?page=penyewa';
              </script>";
    }
} else {
    // Jika file diakses tanpa parameter hapus, kembalikan ke halaman utama
    header("Location: ../index.php?page=penyewa");
}
?>
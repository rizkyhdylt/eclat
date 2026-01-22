<?php
// Koneksi ke database (sesuaikan path-nya jika perlu)
include "../config/config.php"; 

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    // Gunakan prepared statement untuk keamanan (mencegah SQL Injection)
    $query = "DELETE FROM kontrakan WHERE id_kontrakan = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        // Jika berhasil, arahkan kembali ke halaman daftar kontrakan dengan pesan sukses
        echo "<script>
                alert('Data berhasil dihapus!');
                window.location.href='../index.php?page=kontrakan';
              </script>";
    } else {
        // Jika gagal
        echo "<script>
                alert('Gagal menghapus data: " . mysqli_error($conn) . "');
                window.location.href='../index.php?page=kontrakan';
              </script>";
    }
    
    mysqli_stmt_close($stmt);
} else {
    // Jika mencoba akses langsung tanpa parameter hapus
    header("Location: ../index.php?page=kontrakan");
}
?>
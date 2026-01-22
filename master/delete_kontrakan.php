<?php
include "../config/config.php"; 

// --- LOGIKA HAPUS ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    $del = mysqli_query($conn, "DELETE FROM kontrakan WHERE id_kontrakan = '$id'");
    
    if ($del) {
        // Reset Auto Increment agar ID berikutnya mengisi celah yang kosong
        mysqli_query($conn, "ALTER TABLE kontrakan AUTO_INCREMENT = 1");
        
        echo "<script>alert('Data Berhasil Dihapus'); window.location.href='../index.php?page=kontrakan';</script>";
    } else {
        echo "<script>alert('Gagal Hapus'); window.location.href='../index.php?page=kontrakan';</script>";
    }
}

// --- LOGIKA TAMBAH (Jika dipisah ke file ini) ---
if (isset($_POST['simpan'])) {
    $tipe = mysqli_real_escape_string($conn, $_POST['tipe_kamar']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);

    // Reset sebelum insert untuk memastikan urutan paling optimal
    mysqli_query($conn, "ALTER TABLE kontrakan AUTO_INCREMENT = 1");
    
    $ins = mysqli_query($conn, "INSERT INTO kontrakan (tipe_kamar, harga) VALUES ('$tipe', '$harga')");
    
    if ($ins) {
        echo "<script>alert('Data Berhasil Ditambah'); window.location.href='../index.php?page=kontrakan';</script>";
    } else {
        echo "<script>alert('Gagal Simpan'); window.location.href='../index.php?page=kontrakan';</script>";
    }
}
?>
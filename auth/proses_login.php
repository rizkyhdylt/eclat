<?php
session_start();
include "../config/config.php";

// pastikan request dari form
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

// ambil data dari form
$username = mysqli_real_escape_string($conn, trim($_POST['username']));
$password = trim($_POST['password']);

// cek user
$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
$user  = mysqli_fetch_assoc($query);

if ($user) {
    // verifikasi password hash
    if (password_verify($password, $user['password'])) {

        $_SESSION['login'] = true;
        $_SESSION['user']  = [
            'id_user'  => $user['id_user'],
            'username' => $user['username'],
            'nama'     => $user['nama_lengkap'], // Ini akan dipanggil di index.php & dashboard.php
            'role'     => $user['role']
        ];

        // ARAHKAN KE index.php (Bukan dashboard.php lagi)
        header("Location: ../index.php");
        exit;
    }
}

// jika gagal
echo "<script>
    alert('Username atau password salah!');
    window.location='login.php';
</script>";
exit;
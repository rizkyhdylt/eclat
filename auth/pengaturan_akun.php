<?php
// 1. Ambil ID dari array session 'user'
if (!isset($_SESSION['user']['id_user'])) {
    echo "<div class='alert alert-danger m-4'>Sesi telah berakhir. Silakan login kembali.</div>";
    exit;
}

$id_user = $_SESSION['user']['id_user'];

// 2. Query menggunakan kolom 'nama_lengkap' sesuai database
$query_user = mysqli_query($conn, "SELECT * FROM users WHERE id_user = '$id_user'");
$user_data = mysqli_fetch_assoc($query_user);

// 3. Proses Update Profil
if (isset($_POST['update_profil'])) {
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    
    // Update ke kolom 'nama_lengkap'
    $update = mysqli_query($conn, "UPDATE users SET nama_lengkap = '$nama', username = '$username' WHERE id_user = '$id_user'");
    
    if ($update) {
        // Update session agar navbar ikut berubah
        $_SESSION['user']['nama'] = $nama;
        $_SESSION['user']['username'] = $username;
        echo "<script>alert('Profil berhasil diperbarui!'); window.location='index.php?page=pengaturan';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui profil: " . mysqli_error($conn) . "');</script>";
    }
}

// 4. Proses Update Password
if (isset($_POST['update_password'])) {
    $pass_lama  = $_POST['pass_lama'];
    $pass_baru  = $_POST['pass_baru'];
    $konfirmasi = $_POST['konfirmasi'];

    // Cek password lama (menggunakan data dari database)
    if (password_verify($pass_lama, $user_data['password'])) {
        if ($pass_baru === $konfirmasi) {
            $pass_hash = password_hash($pass_baru, PASSWORD_DEFAULT);
            $update_pass = mysqli_query($conn, "UPDATE users SET password = '$pass_hash' WHERE id_user = '$id_user'");
            
            if($update_pass) {
                echo "<script>alert('Password berhasil diubah!'); window.location='index.php?page=pengaturan';</script>";
            }
        } else {
            echo "<script>alert('Konfirmasi password baru tidak cocok!');</script>";
        }
    } else {
        echo "<script>alert('Password lama Anda salah!');</script>";
    }
}
?>

<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-right">
    <div>
        <h4 class="fw-bold mb-1">
            <i class="fa-solid fa-gear text-primary me-2"></i>
            Pengaturan Akun
        </h4>
        <p class="text-muted small mb-0">Kelola informasi profil dan keamanan akun Anda</p>
    </div>
</div>

<!-- Main Content -->
<div class="row g-4" data-aos="fade-up">
    <!-- Edit Profil Card -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="fa-solid fa-user-pen text-primary me-2"></i>
                    Edit Profil
                </h6>
            </div>
            <div class="card-body p-4">
                <form action="" method="POST">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fa-solid fa-user text-primary me-1"></i>
                            Nama Lengkap
                        </label>
                        <input 
                            type="text" 
                            name="nama" 
                            class="form-control form-control-lg" 
                            value="<?= htmlspecialchars($user_data['nama_lengkap'] ?? '') ?>" 
                            required
                        >
                        <small class="text-muted">Nama akan ditampilkan di dashboard</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fa-solid fa-at text-primary me-1"></i>
                            Username
                        </label>
                        <input 
                            type="text" 
                            name="username" 
                            class="form-control form-control-lg" 
                            value="<?= htmlspecialchars($user_data['username'] ?? '') ?>" 
                            required
                        >
                        <small class="text-muted">Username untuk login ke sistem</small>
                    </div>

                    <div class="alert alert-info bg-info bg-opacity-10 border-0 mb-4">
                        <i class="fa-solid fa-info-circle me-2"></i>
                        <strong>Info:</strong> Perubahan akan langsung tersimpan setelah klik tombol simpan.
                    </div>

                    <button type="submit" name="update_profil" class="btn btn-primary btn-lg px-4">
                        <i class="fa-solid fa-check me-2"></i>Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Keamanan Card -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="fa-solid fa-shield-halved text-warning me-2"></i>
                    Keamanan Akun
                </h6>
            </div>
            <div class="card-body p-4">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fa-solid fa-lock text-secondary me-1"></i>
                            Password Lama
                        </label>
                        <input 
                            type="password" 
                            name="pass_lama" 
                            class="form-control form-control-lg" 
                            placeholder="Masukkan password saat ini" 
                            required
                        >
                    </div>

                    <hr class="my-4">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fa-solid fa-key text-warning me-1"></i>
                            Password Baru
                        </label>
                        <input 
                            type="password" 
                            name="pass_baru" 
                            class="form-control form-control-lg" 
                            placeholder="Minimal 6 karakter" 
                            minlength="6"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fa-solid fa-check-circle text-success me-1"></i>
                            Konfirmasi Password Baru
                        </label>
                        <input 
                            type="password" 
                            name="konfirmasi" 
                            class="form-control form-control-lg" 
                            placeholder="Ulangi password baru" 
                            minlength="6"
                            required
                        >
                    </div>

                    <div class="alert alert-warning bg-warning bg-opacity-10 border-0 mb-4">
                        <i class="fa-solid fa-exclamation-triangle me-2"></i>
                        <small><strong>Peringatan:</strong> Pastikan Anda mengingat password baru!</small>
                    </div>

                    <button type="submit" name="update_password" class="btn btn-warning btn-lg w-100 text-white">
                        <i class="fa-solid fa-rotate me-2"></i>Update Password
                    </button>
                </form>
            </div>
        </div>

        <!-- Security Tips Card -->
        <div class="card border-0 shadow-sm mt-4 bg-light">
            <div class="card-body p-3">
                <h6 class="fw-bold mb-2">
                    <i class="fa-solid fa-lightbulb text-warning me-2"></i>
                    Tips Keamanan
                </h6>
                <small class="text-muted">
                    • Gunakan password minimal 6 karakter<br>
                    • Kombinasikan huruf, angka, dan simbol<br>
                    • Jangan gunakan password yang mudah ditebak<br>
                    • Ganti password secara berkala
                </small>
            </div>
        </div>
    </div>
</div>

<style>
/* Card Styling */
.card {
    border-radius: 12px;
}

/* Form Controls */
.form-control {
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
}

.form-control-lg {
    padding: 0.75rem 1rem;
}

/* Button Styling */
.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-lg {
    padding: 0.75rem 1.5rem;
}

.btn-warning {
    background-color: #f59e0b;
    border-color: #f59e0b;
}

.btn-warning:hover {
    background-color: #d97706;
    border-color: #d97706;
}

/* Alert Styling */
.alert {
    border-radius: 8px;
}

/* Labels */
.form-label {
    margin-bottom: 0.5rem;
    color: #475569;
}

/* Small text */
small.text-muted {
    font-size: 0.8rem;
    display: block;
    margin-top: 0.25rem;
}

/* Horizontal Rule */
hr {
    opacity: 0.1;
}

/* Responsive */
@media (max-width: 992px) {
    .col-lg-5 {
        margin-top: 0 !important;
    }
}
</style>
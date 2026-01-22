<?php
session_start();
// kalau sudah login, langsung ke dashboard
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: ../dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | SI-KONTRAK Premium</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #7c3aed;
            --glass-bg: rgba(255, 255, 255, 0.85);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-box {
            width: 100%;
            max-width: 420px;
            perspective: 1000px;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transition: transform 0.3s ease;
        }

        .logo-area {
            margin-bottom: 30px;
        }

        .logo-icon {
            font-size: 45px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }

        .brand-name {
            font-weight: 800;
            font-size: 1.8rem;
            color: #1f2937;
            letter-spacing: -1px;
        }

        .form-floating > .form-control {
            border-radius: 12px;
            border: 2px solid transparent;
            background-color: rgba(0, 0, 0, 0.03);
            transition: all 0.3s;
        }

        .form-floating > .form-control:focus {
            background-color: #fff;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .btn-modern {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            color: white;
            width: 100%;
            margin-top: 10px;
            transition: all 0.3s;
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4);
        }

        .btn-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 25px -5px rgba(79, 70, 229, 0.5);
            filter: brightness(1.1);
        }

        /* Floating decoration objects */
        .shape {
            position: absolute;
            z-index: -1;
            filter: blur(80px);
            border-radius: 50%;
        }
        .shape-1 { width: 300px; height: 300px; background: rgba(79, 70, 229, 0.4); top: -100px; left: -100px; }
        .shape-2 { width: 250px; height: 250px; background: rgba(231, 60, 126, 0.4); bottom: -100px; right: -100px; }
    </style>
</head>
<body>

<div class="shape shape-1"></div>
<div class="shape shape-2"></div>

<div class="login-box" data-aos="fade-up">
    <div class="glass-card">
        <div class="logo-area text-center">
            <i class="fa-solid fa-key logo-icon"></i>
            <h1 class="brand-name">SI-KONTRAK</h1>
            <p class="text-muted small">Analytics & Management System</p>
        </div>

        <form action="proses_login.php" method="POST">
            <div class="form-floating mb-3">
                <input type="text" name="username" class="form-control" id="userInput" placeholder="Username" required autocomplete="off">
                <label for="userInput" class="text-muted"><i class="fa-regular fa-user me-2"></i>Username</label>
            </div>

            <div class="form-floating mb-4">
                <input type="password" name="password" class="form-control" id="passInput" placeholder="Password" required>
                <label for="passInput" class="text-muted"><i class="fa-regular fa-lock me-2"></i>Password</label>
            </div>

            <button type="submit" class="btn btn-modern">
                Mulai Sesi <i class="fa-solid fa-arrow-right ms-2"></i>
            </button>
        </form>

        <div class="mt-4 text-center">
            <a href="#" class="text-decoration-none small text-muted hover-primary">Lupa Password?</a>
        </div>
    </div>
    
    <div class="text-center mt-4 text-white small opacity-75">
        &copy; 2026 Powered by <strong>ECLAT Engine</strong>
    </div>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 1000, once: true });
</script>

</body>
</html>
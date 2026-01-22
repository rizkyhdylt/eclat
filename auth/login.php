<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin | SI-KONTRAK</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0f0f1e;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 20px;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3), transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(99, 102, 241, 0.3), transparent 50%),
                        radial-gradient(circle at 40% 20%, rgba(139, 92, 246, 0.2), transparent 50%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Floating particles */
        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 15s infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) translateX(0); }
            25% { transform: translateY(-100px) translateX(50px); }
            50% { transform: translateY(-50px) translateX(100px); }
            75% { transform: translateY(-150px) translateX(-50px); }
        }

        .container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 1000px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-wrapper {
            display: flex;
            width: 100%;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 30px;
            overflow: hidden;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        }

        .login-left {
            flex: 1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -100px;
            right: -100px;
        }

        .login-left::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            bottom: -50px;
            left: -50px;
        }

        .brand-logo {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
        }

        .login-left h2 {
            font-weight: 800;
            font-size: 2.5rem;
            margin-bottom: 15px;
            letter-spacing: -1px;
            position: relative;
            z-index: 1;
        }

        .login-left p {
            opacity: 0.9;
            font-size: 1.1rem;
            text-align: center;
            max-width: 300px;
            position: relative;
            z-index: 1;
        }

        .secure-badge {
            margin-top: 50px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .secure-badge i {
            font-size: 3rem;
            opacity: 0.2;
            margin-bottom: 10px;
        }

        .secure-badge p {
            font-size: 0.9rem;
            opacity: 0.7;
        }

        .login-right {
            flex: 1;
            padding: 60px 50px;
            background: rgba(15, 15, 30, 0.8);
            backdrop-filter: blur(20px);
            position: relative;
        }

        .login-header {
            margin-bottom: 40px;
        }

        .login-header h3 {
            color: white;
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 8px;
        }

        .login-header p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.95rem;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 25px;
        }

        .input-group-custom label {
            display: block;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
            font-size: 1.1rem;
            z-index: 10;
        }

        .form-control {
            width: 100%;
            padding: 15px 15px 15px 50px;
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            color: white !important;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            -webkit-text-fill-color: white !important;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .form-control:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.08);
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            color: white !important;
            -webkit-text-fill-color: white !important;
        }

        /* Autofill styling */
        .form-control:-webkit-autofill,
        .form-control:-webkit-autofill:hover,
        .form-control:-webkit-autofill:focus,
        .form-control:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px rgba(255, 255, 255, 0.08) inset !important;
            -webkit-text-fill-color: white !important;
            caret-color: white;
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 15px;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        .footer-text {
            text-align: center;
            margin-top: 40px;
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.85rem;
        }

        /* Decorative circles */
        .decorative-circle {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.1), transparent);
            pointer-events: none;
        }

        .circle-1 {
            top: -200px;
            right: -200px;
        }

        .circle-2 {
            bottom: -200px;
            left: -200px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .login-left {
                display: none;
            }

            .login-right {
                padding: 40px 30px;
            }

            .login-wrapper {
                margin: 0;
            }

            .login-header h3 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<!-- Floating particles -->
<div class="particle" style="width: 10px; height: 10px; top: 10%; left: 20%; animation-delay: 0s;"></div>
<div class="particle" style="width: 15px; height: 15px; top: 60%; left: 80%; animation-delay: 2s;"></div>
<div class="particle" style="width: 8px; height: 8px; top: 30%; left: 70%; animation-delay: 4s;"></div>
<div class="particle" style="width: 12px; height: 12px; top: 80%; left: 30%; animation-delay: 1s;"></div>
<div class="particle" style="width: 6px; height: 6px; top: 50%; left: 10%; animation-delay: 3s;"></div>

<div class="container">
    <div class="login-wrapper" data-aos="fade-up" data-aos-duration="1000">
        
        <!-- Left Side -->
        <div class="login-left" data-aos="fade-right" data-aos-delay="200">
            <div class="brand-logo">
                <i class="fa-solid fa-house-lock"></i>
            </div>
            <h2>SI-KONTRAK</h2>
            <p>Sistem Informasi Manajemen Kontrak ECLAT</p>
            
            <div class="secure-badge">
                <div>
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <p>Secure & Reliable</p>
            </div>
        </div>

        <!-- Right Side -->
        <div class="login-right">
            <div class="decorative-circle circle-1"></div>
            <div class="decorative-circle circle-2"></div>
            
            <div class="login-header" data-aos="fade-left" data-aos-delay="400">
                <h3>Selamat Datang Kembali</h3>
                <p>Masuk ke dashboard admin untuk mengelola sistem</p>
            </div>

            <form method="POST" action="proses_login.php">
                <div class="input-group-custom" data-aos="fade-left" data-aos-delay="500">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username" required autofocus>
                    </div>
                </div>

                <div class="input-group-custom" data-aos="fade-left" data-aos-delay="600">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                </div>

                <button type="submit" class="btn-login" data-aos="fade-left" data-aos-delay="700">
                    MASUK KE DASHBOARD <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>
            </form>

            <div class="footer-text" data-aos="fade-up" data-aos-delay="800">
                &copy; 2026 SI-KONTRAK ECLAT System. All Rights Reserved.
            </div>
        </div>

    </div>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        once: true,
        duration: 800
    });

    // Add floating animation to particles
    const particles = document.querySelectorAll('.particle');
    particles.forEach((particle, index) => {
        particle.style.animationDelay = `${index * 2}s`;
    });
</script>

</body>
</html>
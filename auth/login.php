<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin | SI-KONTRAK</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'Plus Jakarta Sans',sans-serif;
            background:#0f172a;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            overflow:hidden;
            position:relative;
            padding:20px;
        }

        /* BACKGROUND */
        body::before{
            content:'';
            position:absolute;
            width:200%;
            height:200%;
            background:
                radial-gradient(circle at 20% 20%, rgba(59,130,246,.25), transparent 30%),
                radial-gradient(circle at 80% 70%, rgba(139,92,246,.25), transparent 30%),
                radial-gradient(circle at 50% 50%, rgba(14,165,233,.15), transparent 40%);
            animation:rotateBg 20s linear infinite;
        }

        @keyframes rotateBg{
            0%{
                transform:rotate(0deg);
            }
            100%{
                transform:rotate(360deg);
            }
        }

        /* PARTICLES */
        .particle{
            position:absolute;
            background:rgba(255,255,255,.08);
            border-radius:50%;
            animation:float 12s infinite ease-in-out;
        }

        @keyframes float{
            0%,100%{
                transform:translateY(0);
            }
            50%{
                transform:translateY(-40px);
            }
        }

        /* WRAPPER */
        .login-wrapper{
            position:relative;
            z-index:2;
            width:100%;
            max-width:1050px;
            display:flex;
            border-radius:30px;
            overflow:hidden;
            background:rgba(255,255,255,.04);
            backdrop-filter:blur(20px);
            border:1px solid rgba(255,255,255,.08);
            box-shadow:0 20px 50px rgba(0,0,0,.4);
        }

        /* LEFT */
        .login-left{
            width:50%;
            background:linear-gradient(135deg,#3b82f6,#8b5cf6);
            color:white;
            padding:60px 45px;
            position:relative;
            display:flex;
            flex-direction:column;
            justify-content:center;
            align-items:center;
            text-align:center;
        }

        .login-left::before{
            content:'';
            position:absolute;
            width:250px;
            height:250px;
            background:rgba(255,255,255,.1);
            border-radius:50%;
            top:-80px;
            right:-80px;
        }

        .login-left::after{
            content:'';
            position:absolute;
            width:180px;
            height:180px;
            background:rgba(255,255,255,.08);
            border-radius:50%;
            bottom:-60px;
            left:-60px;
        }

        .brand-logo{
            width:110px;
            height:110px;
            border-radius:30px;
            background:rgba(255,255,255,.15);
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:50px;
            margin-bottom:25px;
            backdrop-filter:blur(10px);
            border:2px solid rgba(255,255,255,.2);
            z-index:2;
        }

        .login-left h2{
            font-size:2.5rem;
            font-weight:800;
            margin-bottom:10px;
            z-index:2;
        }

        .login-left p{
            max-width:300px;
            opacity:.9;
            line-height:1.7;
            z-index:2;
        }

        .secure-badge{
            margin-top:50px;
            z-index:2;
        }

        .secure-badge i{
            font-size:40px;
            opacity:.3;
            margin-bottom:10px;
        }

        /* RIGHT */
        .login-right{
            width:50%;
            padding:60px 50px;
            background:rgba(15,23,42,.85);
            position:relative;
        }

        .login-header{
            margin-bottom:35px;
        }

        .login-header h3{
            color:white;
            font-size:2rem;
            font-weight:700;
            margin-bottom:10px;
        }

        .login-header p{
            color:rgba(255,255,255,.6);
        }

        /* INPUT */
        .input-group-custom{
            margin-bottom:22px;
        }

        .input-group-custom label{
            display:block;
            margin-bottom:10px;
            color:white;
            font-weight:600;
            font-size:.9rem;
        }

        .input-wrapper{
            position:relative;
        }

        .input-wrapper i{
            position:absolute;
            top:50%;
            left:18px;
            transform:translateY(-50%);
            color:#60a5fa;
            z-index:5;
        }

        .form-control{
            height:55px;
            border-radius:16px;
            border:1px solid rgba(255,255,255,.1);
            background:rgba(255,255,255,.05);
            padding-left:50px;
            color:white !important;
            font-size:.95rem;
        }

        .form-control:focus{
            background:rgba(255,255,255,.08);
            border-color:#3b82f6;
            box-shadow:none;
            color:white !important;
        }

        .form-control::placeholder{
            color:rgba(255,255,255,.4);
        }

        /* BUTTON */
        .btn-login{
            width:100%;
            height:55px;
            border:none;
            border-radius:16px;
            background:linear-gradient(135deg,#3b82f6,#8b5cf6);
            color:white;
            font-weight:700;
            margin-top:10px;
            transition:.3s;
        }

        .btn-login:hover{
            transform:translateY(-2px);
            box-shadow:0 10px 25px rgba(59,130,246,.3);
        }

        .footer-text{
            margin-top:35px;
            text-align:center;
            color:rgba(255,255,255,.4);
            font-size:.85rem;
        }

        /* MOBILE */
        @media(max-width:991px){

            body{
                padding:15px;
            }

            .login-wrapper{
                flex-direction:column;
                max-width:500px;
                border-radius:25px;
            }

            .login-left{
                width:100%;
                padding:40px 25px;
            }

            .login-left h2{
                font-size:2rem;
            }

            .login-left p{
                font-size:.95rem;
            }

            .brand-logo{
                width:90px;
                height:90px;
                font-size:40px;
            }

            .secure-badge{
                margin-top:25px;
            }

            .login-right{
                width:100%;
                padding:35px 25px;
            }

            .login-header{
                text-align:center;
            }

            .login-header h3{
                font-size:1.5rem;
            }

            .footer-text{
                font-size:.75rem;
            }
        }

        @media(max-width:576px){

            .login-wrapper{
                border-radius:20px;
            }

            .login-left{
                padding:35px 20px;
            }

            .login-right{
                padding:30px 20px;
            }

            .brand-logo{
                width:80px;
                height:80px;
                font-size:35px;
                border-radius:20px;
            }

            .login-left h2{
                font-size:1.7rem;
            }

            .login-left p{
                font-size:.85rem;
            }

            .login-header h3{
                font-size:1.3rem;
            }

            .btn-login{
                font-size:.9rem;
            }
        }
    </style>
</head>
<body>

<!-- PARTICLES -->
<div class="particle" style="width:10px;height:10px;top:10%;left:15%;"></div>
<div class="particle" style="width:15px;height:15px;top:70%;left:80%;animation-delay:2s;"></div>
<div class="particle" style="width:8px;height:8px;top:30%;left:70%;animation-delay:4s;"></div>
<div class="particle" style="width:12px;height:12px;top:80%;left:20%;animation-delay:1s;"></div>

<div class="login-wrapper">

    <!-- LEFT -->
    <div class="login-left" data-aos="fade-right">

        <div class="brand-logo">
            <i class="fa-solid fa-house-lock"></i>
        </div>

        <h2>SI-KONTRAK</h2>

        <p>
            Sistem Informasi Manajemen Kontrak berbasis ECLAT 
            untuk pengelolaan data kontrakan modern.
        </p>

        <div class="secure-badge">
            <i class="fa-solid fa-shield-halved"></i>
            <p>Secure & Reliable System</p>
        </div>

    </div>

    <!-- RIGHT -->
    <div class="login-right" data-aos="fade-left">

        <div class="login-header">
            <h3>Selamat Datang 👋</h3>
            <p>Silakan login untuk masuk ke dashboard admin</p>
        </div>

        <form method="POST" action="proses_login.php">

            <div class="input-group-custom">
                <label>Username</label>

                <div class="input-wrapper">
                    <i class="fa-solid fa-user"></i>

                    <input 
                        type="text" 
                        name="username" 
                        class="form-control"
                        placeholder="Masukkan username"
                        required
                    >
                </div>
            </div>

            <div class="input-group-custom">
                <label>Password</label>

                <div class="input-wrapper">
                    <i class="fa-solid fa-lock"></i>

                    <input 
                        type="password" 
                        name="password" 
                        class="form-control"
                        placeholder="Masukkan password"
                        required
                    >
                </div>
            </div>

            <button type="submit" class="btn-login">
                MASUK KE DASHBOARD
                <i class="fa-solid fa-arrow-right ms-2"></i>
            </button>

        </form>

        <div class="footer-text">
            © 2026 SI-KONTRAK ECLAT System
        </div>

    </div>

</div>

<!-- JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
    AOS.init({
        duration:800,
        once:true
    });
</script>

</body>
</html>
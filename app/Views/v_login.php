<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | E-Persediaan TIK PN Bale Bandung</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f0f4f8; 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 40, 100, 0.12);
            position: relative;
            overflow: hidden;
            /* Ukuran diperbesar */
            width: 1050px; 
            max-width: 95%;
            min-height: 600px; 
            display: flex;
        }

        /* Bagian Kiri (Form Login) */
        .form-container {
            width: 50%;
            padding: 50px 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #ffffff;
        }

        .form-logo {
            color: #1e3c72;
            font-size: 35px;
            margin-bottom: 15px;
            /* Jika nanti mau pakai gambar logo instansi asli, 
               hapus icon FontAwesome di bawah dan ganti pakai tag <img> */
        }

        .form-container h1 {
            font-weight: 700;
            font-size: 32px;
            margin-bottom: 5px;
            color: #1e3c72; 
        }

        .form-container p.subtitle {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 35px;
            text-align: center;
        }

        .input-group {
            position: relative;
            width: 100%;
            margin: 10px 0;
        }

        .input-group i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aabf;
            font-size: 14px;
        }

        .form-container input {
            background-color: #f8f9fa;
            border: 1px solid #e0e0e0;
            padding: 16px 15px 16px 45px; /* Padding kiri lebih besar untuk icon */
            width: 100%;
            border-radius: 10px;
            outline: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-container input:focus {
            border-color: #2a5298;
            background-color: #ffffff;
            box-shadow: 0 0 8px rgba(42, 82, 152, 0.15);
        }

        .form-container button {
            border-radius: 10px;
            border: none;
            background: linear-gradient(to right, #1e3c72, #2a5298);
            color: #FFFFFF;
            font-size: 15px;
            font-weight: 600;
            padding: 16px 45px;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-top: 25px;
            width: 100%;
        }

        .form-container button:hover {
            box-shadow: 0 8px 20px rgba(30, 60, 114, 0.3);
            background: linear-gradient(to right, #1a3360, #244682);
            transform: translateY(-2px);
        }

        .form-container button:active {
            transform: translateY(0);
        }

        /* Bagian Kanan (Panel Biru Identitas) */
        .branding-container {
            width: 50%;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            padding: 0 60px;
            position: relative;
        }

        /* Background Watermark Server/IT (Sangat tipis) */
        .bg-watermark {
            position: absolute;
            font-size: 350px;
            color: rgba(255, 255, 255, 0.03); /* Opacity sangat kecil agar elegan */
            z-index: 0;
            transform: rotate(-15deg);
            bottom: -50px;
            right: -50px;
        }

        .branding-container .main-icon {
            font-size: 55px;
            margin-bottom: 25px;
            color: #e0e8f5;
            z-index: 1;
            /* Animasi mengambang tipis */
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .branding-container h1 {
            font-weight: 700;
            font-size: 36px;
            line-height: 1.2;
            margin-bottom: 10px;
            z-index: 1;
        }

        .branding-container p.title-sub {
            font-size: 18px;
            font-weight: 500;
            color: #e0e8f5;
            z-index: 1;
            margin-bottom: 25px;
        }

        .branding-divider {
            width: 60px;
            height: 3px;
            background-color: rgba(255, 255, 255, 0.3);
            margin: 0 auto 25px auto;
            border-radius: 5px;
            z-index: 1;
        }

        .branding-container p.desc {
            font-size: 14px;
            line-height: 24px;
            font-weight: 300;
            color: #b9c7df;
            z-index: 1;
            max-width: 80%;
        }

        /* Alert styling */
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 12px;
            border-radius: 8px;
            font-size: 13px;
            width: 100%;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="form-container">
            <form action="<?= base_url('auth/login_process'); ?>" method="post" style="width: 100%; display: flex; flex-direction: column; align-items: center;">
                <?= csrf_field(); ?>
                
                <div class="form-logo">
                    <i class="fas fa-shield-alt"></i> 
                </div>

                <h1>Sign In</h1>
                <p class="subtitle">Silakan masukkan kredensial akun Anda</p>

                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" placeholder="Username" required autocomplete="off" />
                </div>
                
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required />
                </div>
                
                <button type="submit">MASUK KE SISTEM</button>
            </form>
        </div>

        <div class="branding-container">
            <i class="fas fa-server bg-watermark"></i>
            
            <i class="fas fa-cubes main-icon"></i>
            
            <h1>E-Persediaan</h1>
            <p class="title-sub">Barang TIK</p>
            
            <div class="branding-divider"></div>
            
            <p class="desc">Pengadilan Negeri Bale Bandung Kelas 1A<br>Manajemen Logistik & Aset IT Terpadu</p>
        </div>
    </div>

</body>
</html>
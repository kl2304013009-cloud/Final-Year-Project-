<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pusat Sumber Al-Bukhari | SK Kampung Sepulau</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        /* ========== GLOBAL STYLE ========== */
        body {
            margin: 0;
            font-family: "Poppins", sans-serif;
            background: linear-gradient(180deg, #e0ebff 0%, #f9fafc 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #1e293b;
            overflow-x: hidden;
        }

        .container {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 60px 40px;
            max-width: 720px;
            width: 90%;
            position: relative;
        }

        /* ========== LOGO ========== */
        .school-logo {
            width: 120px;
            height: auto;
            margin-bottom: 18px;
            filter: drop-shadow(0 3px 6px rgba(0,0,0,0.2));
        }

        /* ========== HEADER TEXT ========== */
        h1 {
            font-size: 2.2rem;
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 8px;
        }

        h2 {
            font-size: 1.2rem;
            color: #0f172a;
            font-weight: 600;
            margin-bottom: 25px;
        }

        /* ========== DESCRIPTION ========== */
        p {
            color: #475569;
            font-size: 1rem;
            line-height: 1.8;
            margin-bottom: 40px;
        }

        /* ========== BUTTON GROUP ========== */
        .button-group {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 35px;
        }

        a.button {
            display: inline-block;
            padding: 12px 32px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .btn-primary {
            background-color: #1d4ed8;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #1e40af;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #f1f5ff;
            border: 2px solid #1d4ed8;
            color: #1d4ed8;
        }

        .btn-secondary:hover {
            background-color: #e0e7ff;
            transform: translateY(-2px);
        }

        /* ========== FOOTER ========== */
        footer {
            font-size: 0.9rem;
            color: #475569;
            text-align: center;
            margin-top: 40px;
        }

        footer span {
            color: #1e3a8a;
            font-weight: 600;
        }

        /* ========== DECORATIVE BACKGROUND ========== */
        .bg-shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(29,78,216,0.08);
            filter: blur(60px);
        }

        .bg-shape.one {
            width: 250px;
            height: 250px;
            top: -100px;
            left: -80px;
        }

        .bg-shape.two {
            width: 180px;
            height: 180px;
            bottom: -80px;
            right: -60px;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 600px) {
            h1 { font-size: 1.9rem; }
            p { font-size: 0.95rem; }
            .container { padding: 45px 25px; }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="bg-shape one"></div>
        <div class="bg-shape two"></div>

        <!-- LOGO SEKOLAH -->
        <img src="{{ asset('images/logo1.png') }}" 
            alt="Logo SK Kampung Sepulau" 
            class="school-logo">

        <h2>Sekolah Kebangsaan Kampung Sepulau</h2>
        <h1>Pusat Sumber Al-Bukhari</h1>

        <p>
            Selamat datang ke <strong>Pusat Sumber Al-Bukhari</strong> 
            Sekolah Kebangsaan Kampung Sepulau. Portal ini memudahkan proses 
            pinjaman buku, rekod pelajar, dan operasi pusat sumber untuk 
            guru dan pelajar.
        </p>

        <div class="button-group">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="button btn-primary">Pergi ke Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="button btn-primary">Log Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="button btn-secondary">Daftar</a>
                    @endif
                @endauth
            @endif
        </div>

        <footer>
            &copy; {{ date('Y') }} <span>Pusat Sumber Al-Bukhari SK Kampung Sepulau</span><br>
            Dibangunkan oleh <strong>Primer</strong>.
        </footer>
    </div>

</body>
</html>

<x-guest-layout>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1b5e20, #388e3c);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: #ffffff;
            width: 400px;
            border-radius: 18px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);
            padding: 45px 38px;
            box-sizing: border-box;
            text-align: center;
            animation: fadeIn 0.8s ease;
        }

        .login-container img {
            width: 80px;
            margin-bottom: 15px;
        }

        .login-container h2 {
            color: #1b5e20;
            font-size: 22px;
            margin-bottom: 25px;
            font-weight: 600;
            line-height: 1.4;
        }

        label {
            display: block;
            text-align: left;
            font-size: 14px;
            color: #333;
            margin-bottom: 6px;
            font-weight: 500;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 11px 13px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 14px;
            box-sizing: border-box;
            transition: all 0.3s;
        }

        input:focus {
            border-color: #2e7d32;
            outline: none;
            box-shadow: 0 0 0 2px rgba(46, 125, 50, 0.2);
        }

        button {
            width: 100%;
            background: linear-gradient(135deg, #2e7d32, #43a047);
            color: #fff;
            border: none;
            padding: 12px;
            font-size: 15px;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        button:hover {
            background: linear-gradient(135deg, #1b5e20, #2e7d32);
            transform: translateY(-1px);
        }

        .status-message,
        .validation-errors {
            text-align: left;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 12px;
        }

        .status-message {
            background-color: #e8f5e9;
            border-left: 4px solid #2e7d32;
            color: #1b5e20;
        }

        .validation-errors {
            background-color: #fdecea;
            border-left: 4px solid #dc2626;
            color: #991b1b;
        }

        .login-footer {
            margin-top: 20px;
            font-size: 13px;
            color: #555;
        }

        .login-footer span {
            color: #2e7d32;
            font-weight: 500;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div class="login-container">
        <img src="https://img.icons8.com/ios-filled/80/1b5e20/books.png" alt="Login Icon">
        <h2>Selamat Datang ke<br>Pusat Sumber Al-Bukhari</h2>

        <x-validation-errors class="validation-errors" />

        @if (session('status'))
            <div class="status-message">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('student.login') }}">
            @csrf

            <div class="form-group">
                <label for="login">Email / ID</label>
                <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus autocomplete="username">
            </div>

            <div class="form-group" style="margin-top: 12px;">
                <label for="password">Kata Laluan</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
            </div>

            <button type="submit">Log Masuk</button>
        </form>

        <div class="login-footer">
            Akses untuk <span>Pelajar</span> & <span>Guru</span>
        </div>
    </div>
</x-guest-layout>

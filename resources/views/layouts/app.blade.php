<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Pusat Sumber Al-Bukhari') }}</title>

    <!-- ✅ Import Nunito -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body {
            font-family: 'Nunito', sans-serif; /* Gunakan font baru di seluruh laman */
            background: linear-gradient(135deg, #fefae0, #fff8d6);
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
            color: #2e2e2e;
        }

        /* 🌟 TOP BAR */
        header.topbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 70px;
            background: linear-gradient(135deg, #1b5e20, #2e7d32);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2.5rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
            z-index: 100;
            font-family: 'Nunito', sans-serif; /* Pastikan topbar guna font sama */
        }

        .topbar-center h1 {
            font-size: 1.45rem;
            font-weight: 600;
            letter-spacing: 0.6px;
            text-transform: uppercase;
        }

        .menu-toggle {
            background: none;
            border: none;
            color: white;
            font-size: 1.9rem;
            cursor: pointer;
            display: none;
        }

        /* 🔘 PROFILE BUTTON */
        .dropdown-button {
            background: #ffffff;
            border: 2px solid #1b5e20;
            border-radius: 40px;
            padding: 8px 22px;
            color: #1b5e20;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            font-family: 'Nunito', sans-serif; /* samakan font */
        }

        .dropdown-button:hover {
            background: #a5d6a7;
            color: #0c3d13;
            transform: translateY(-1px);
        }

        /* 🧭 SIDEBAR */
        aside.sidebar {
            position: fixed;
            top: 70px;
            left: 0;
            width: 280px;
            height: calc(100vh - 70px);
            background: linear-gradient(180deg, #2e7d32, #388e3c);
            color: white;
            display: flex;
            flex-direction: column;
            padding: 1.5rem 1rem;
            transition: transform 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.15);
            font-family: 'Nunito', sans-serif; /* samakan font */
        }

        .sidebar nav {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .sidebar a.nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 20px;
            color: #fff;
            font-weight: 500;
            text-decoration: none;
            border-radius: 12px;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
            font-family: 'Nunito', sans-serif; /* samakan font */
        }

        .sidebar a.nav-link:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(6px);
        }

        .sidebar a.nav-active {
            background: rgba(255, 255, 255, 0.35);
            font-weight: 600;
            box-shadow: inset 4px 0 0 rgba(255, 255, 255, 0.7);
        }

        /* 📄 MAIN CONTENT */
        main.page-content {
            margin-top: 70px;
            margin-left: 280px;
            padding: 3rem 2.5rem;
            flex: 1;
            overflow-y: auto;
            background: #fffef5;
            border-radius: 20px 0 0 0;
            transition: margin-left 0.3s ease;
        }

        /* 📱 RESPONSIVE */
        @media (max-width: 1024px) {
            aside.sidebar {
                transform: translateX(-100%);
            }
            aside.sidebar.active {
                transform: translateX(0);
            }
            main.page-content {
                margin-left: 0;
            }
            .menu-toggle {
                display: inline-block;
            }
        }
    </style>
</head>

<body class="antialiased {{ Auth::guard('student')->check() ? 'student-layout' : 'admin-layout' }}">

    <!-- 🌟 TOP BAR -->
    <header class="topbar">
        <div class="topbar-left">
            <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
        </div>

        <div class="topbar-center">
            <h1>Pusat Sumber Al-Bukhari</h1>
        </div>

        <div class="topbar-right">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button type="button" class="dropdown-button">
                        {{ Auth::user()->name }}
                    </button>
                </x-slot>

                <x-slot name="content">
                    @if(!Auth::guard('student')->check())
                        <x-dropdown-link href="{{ route('profile.show') }}">Profile</x-dropdown-link>
                        <div class="border-t border-gray-100"></div>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link href="{{ route('logout') }}"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            Logout
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </header>

    <!-- 🧭 SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        @php $currentRoute = Route::currentRouteName(); @endphp
        <nav>
            @if(Auth::guard('student')->check())
                <a href="{{ route('students.dashboard') }}" class="nav-link {{ $currentRoute == 'students.dashboard' ? 'nav-active' : '' }}">Halaman Utama</a>
                <a href="{{ route('borrow.index') }}" class="nav-link {{ $currentRoute == 'borrow.index' ? 'nav-active' : '' }}">Pinjam Buku</a>
                <a href="{{ route('pelajar.history') }}" class="nav-link {{ request()->routeIs('pelajar.history') ? 'nav-active' : '' }}">Sejarah Pinjaman</a>
            @else
                <a href="{{ route('dashboard') }}" class="nav-link {{ $currentRoute == 'dashboard' ? 'nav-active' : '' }}">Halaman Utama</a>
                <a href="{{ route('students.index') }}" class="nav-link {{ str_contains($currentRoute, 'students.') ? 'nav-active' : '' }}">Pelajar</a>
                <a href="{{ route('books.index') }}" class="nav-link {{ str_contains($currentRoute, 'books.') ? 'nav-active' : '' }}">Buku</a>
                <a href="{{ route('borrow_return') }}" class="nav-link {{ $currentRoute == 'borrow_return' ? 'nav-active' : '' }}">Pinjam & Pulang</a>
                <a href="{{ route('report.index') }}" class="nav-link {{ str_contains($currentRoute, 'report.') ? 'nav-active' : '' }}">Laporan</a>
            @endif
        </nav>
    </aside>

    <!-- 📄 MAIN PAGE CONTENT -->
    <main class="page-content">
        {{ $slot }}
    </main>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
    </script>

    @stack('modals')
    @livewireScripts
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link rel="stylesheet" href="{{ asset('css/custom-admin.css') }}">
</head>
<body>
    <div class="admin-wrapper">
        @include('components.admin-sidebar')

        <main class="admin-main">
            <header class="admin-topbar">
                <button type="button" class="admin-mobile-toggle" id="adminMobileToggle" aria-label="Buka menu admin">
                    <i class="bi bi-list"></i>
                </button>

                <div class="admin-topbar-user">
                    <div class="admin-user-info">
                        <span>Admin</span>
                        <strong>{{ auth()->user()->name }}</strong>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn-admin-logout">
                            <i class="bi bi-box-arrow-right me-1"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            <section class="admin-content">
                @if(session('success'))
                    <div class="alert alert-success admin-alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger admin-alert">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleButton = document.getElementById('adminMobileToggle');
            const sidebar = document.querySelector('.admin-sidebar');
            const overlay = document.querySelector('.admin-sidebar-overlay');

            if (toggleButton && sidebar && overlay) {
                toggleButton.addEventListener('click', function () {
                    sidebar.classList.add('show-mobile');
                    overlay.classList.add('show-mobile');
                });

                overlay.addEventListener('click', function () {
                    sidebar.classList.remove('show-mobile');
                    overlay.classList.remove('show-mobile');
                });
            }
        });
    </script>

    @yield('scripts')
</body>
</html>
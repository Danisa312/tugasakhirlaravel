<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard - My App')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"  rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --sea-blue: #00778B;
        }

        .bg-sea-blue {
            background-color: var(--sea-blue) !important;
        }

        .text-sea-blue {
            color: var(--sea-blue);
        }

        .btn-sea-blue {
            background-color: var(--sea-blue);
            border-color: var(--sea-blue);
            color: white;
        }

        .btn-sea-blue:hover {
            background-color: #005F63;
            border-color: #005F63;
        }

        .nav-link.active,
        .nav-link:hover {
            background-color: rgba(0, 119, 139, 0.1);
            border-radius: 5px;
        }

        .sidebar-sticky {
            position: -webkit-sticky;
            position: sticky;
            top: 56px;
            height: calc(100vh - 56px);
            overflow-y: auto;
        }

        .main-content {
            padding-top: 56px;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

<!-- Navbar Atas -->
<nav class="navbar navbar-expand-lg bg-sea-blue text-white fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand text-white fw-bold" href="#">MyApp Dashboard</a>
        <button class="btn btn-sea-blue d-lg-none ms-auto" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#leftSidebar" aria-controls="sidebarMenu">
            ☰ Menu
        </button>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                        Admin
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Profil</a></li>
                        <li><a class="dropdown-item" href="#">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Wrapper Konten -->
<div class="container-fluid main-content">
    <div class="row g-3">
        <!-- Sidebar Kiri -->
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-sea-blue sidebar offcanvas-body collapse">
            <div class="position-sticky pt-3 sidebar-sticky">
                <ul class="nav flex-column" id="navbar-items">
                    <!-- Dinamis dari JavaScript -->
                </ul>
            </div>
        </nav>

        <!-- Konten Utama -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">@yield('page-title', 'Dashboard')</h1>
            </div>

            @yield('content')
        </main>
    </div>
</div>

<!-- Footer Optional -->
<footer class="mt-auto py-3 bg-light text-center">
    <div class="container">
        <small>&copy; {{ date('Y') }} MyApp. All rights reserved.</small>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> 

<!-- JS untuk Render Sidebar -->
@vite(['resources/js/app.js'])

</body>
</html>
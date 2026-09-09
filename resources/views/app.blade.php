<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Point Of Sales' }}</title>

    <!-- SEO Optimization -->
    <meta name="description" content="Blank Page - Spark Admin Premium Bootstrap 5 Admin Dashboard Template">
    <meta name="author" content="Spark Admin Team">

    <!-- Favicon -->
    {{-- <link rel="icon" type="image/png" href="assets/images/favicon.ico"> --}}

    <!-- Local Third-Party Libraries -->
    <link rel="stylesheet" href="assets/libs/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/libs/bootstrap-icons/bootstrap-icons.css">

    <!-- Main Design System -->
    <link rel="stylesheet" href="assets/css/main.css">

    @include('inc.css')
</head>

<body>

    <!-- Main Container -->
    <div class="app-container">

        <!-- Sidebar (Fixed sesuai main.css) -->
        <aside class="sidebar-wrapper" id="sidebar">

            <a href="{{ url('/admin/dashboard') }}" class="sidebar-brand">
                <span>{{ $title ?? 'Point Of Sales' }}</span>
            </a>

            <div class="sidebar-menu-wrapper">

                <!-- Dashboard -->
                <div class="sidebar-menu-section">
                    <ul class="sidebar-menu-list">
                        <li class="sidebar-menu-item">
                            <a href="{{ url('/admin/dashboard') }}" class="sidebar-menu-link active" id="menu-dashboard"
                                title="Dashboard">
                                <span>Dashboard</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Menu -->
                <div class="sidebar-menu-section">
                    <ul class="sidebar-menu-list">
                        <li class="sidebar-menu-item">
                            <a href="{{ route('product.index') }}" class="sidebar-menu-link" id="menu-product" title="Product">
                                <span>Product</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item">
                            <a href="{{ route('category.index') }}" class="sidebar-menu-link" id="menu-category"
                                title="Category">
                                <span>Category</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item">
                            <a href="{{ route('role.index') }}" class="sidebar-menu-link" id="menu-user" title="User">
                                <span>Role</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('user.index') }}" class="sidebar-menu-link" id="menu-user" title="User">
                                <span>User</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item">
                            <a href="#" class="sidebar-menu-link" id="menu-logout" title="Log-Out">
                                <span>Log-Out</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item">
                            <a href="#" class="sidebar-menu-link" id="menu-404" title="404 Page">
                                <span>Error 404</span>
                            </a>
                        </li>
                    </ul>
                </div>

            </div>

        </aside>

        <!-- Main Content (Menggunakan class .main-content agar otomatis menyesuaikan margin sidebar) -->
        <main class="main-content">
            @yield('content')
        </main>

    </div>

    @include('inc.js')

</body>

</html>

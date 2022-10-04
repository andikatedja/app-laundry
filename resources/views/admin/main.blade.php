<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>{{ config('app.name') }} - Admin</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/adminlte.min.css') }}">
    <!-- Custom styles for this template -->
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <!-- Google Font: Nunito -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">
    @yield('css')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <img class="img-circle img-fit mr-1" width="25" height="25"
                            src="{{ $user->getFileAsset() }}" alt="Foto Profil">
                        {{ $user->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                        <a href="{{ url('profile') }}" class="dropdown-item">
                            <i class="fas fa-user-edit mr-2"></i> Edit Profil
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#logoutModal">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="" class="brand-link mt-2">
                <i class="fas fa-tshirt brand-image mt-1 ml-3"></i>
                <h4 class="brand-text text-center">{{ config('app.name') }}</h4>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ url('admin') }}" class="nav-link {{ request()->is('admin') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/input-transaksi') }}"
                                class="nav-link {{ request()->is('admin/input-transaksi') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-invoice"></i>
                                <p>Input Transaksi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/transaksi') }}"
                                class="nav-link {{ request()->is('admin/transaksi*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-history"></i>
                                <p>Riwayat Transaksi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/harga') }}"
                                class="nav-link {{ request()->is('admin/harga') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-list"></i>
                                <p>Daftar Harga</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/members') }}"
                                class="nav-link {{ request()->is('admin/members*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Daftar Member</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/voucher') }}"
                                class="nav-link {{ request()->is('admin/voucher') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-star"></i>
                                <p>Voucher</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/saran') }}"
                                class="nav-link {{ request()->is('admin/saran') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-sticky-note"></i>
                                <p>Saran / Komplain</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/laporan') }}"
                                class="nav-link {{ request()->is('admin/laporan') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>Laporan Keuangan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('profile.show') }}"
                                class="nav-link {{ request()->routeIs('profile.show') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-edit"></i>
                                <p>Edit Profil</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" data-toggle="modal" data-target="#logoutModal">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>Logout</p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            @yield('main-content')
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            Copyright &copy; 2022 {{ config('app.name') }} All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    {{-- Logout Modal --}}
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Keluar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah anda yakin ingin keluar?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <a href="{{ route('login.logout') }}" class="btn btn-primary">Keluar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('vendor/adminlte/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('js/myscript.js') }}"></script>
    @yield('scripts')
</body>

</html>

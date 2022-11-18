<div class="sidebar">

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="{{ route('member.index') }}"
                    class="nav-link {{ request()->routeIs('member.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Beranda</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('member.price_lists.index') }}"
                    class="nav-link {{ request()->routeIs('member.price_lists.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-list"></i>
                    <p>Daftar Harga</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('member.transactions.index') }}"
                    class="nav-link {{ request()->routeIs('member.transactions*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-history"></i>
                    <p>Riwayat Transaksi</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('member.points.index') }}"
                    class="nav-link {{ request()->routeIs('member.points.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-star"></i>
                    <p>Tukar Poin</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('profile.index') }}"
                    class="nav-link {{ request()->routeIs('profile.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user-edit"></i>
                    <p>Edit Profil</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('member.complaints.index') }}"
                    class="nav-link {{ request()->routeIs('member.complaints.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-sticky-note"></i>
                    <p>Saran / Komplain</p>
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

<footer class="main-footer">
    <div class="float-right d-none d-sm-block">
        <b>Version</b> 3.2.0
    </div>
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-header">Pengaturan</li>
            <li class="nav-item">
                <a href="{{ url('/profile') }}" class="nav-link">
                    <i class="nav-icon fas fa-user-cog"></i>
                    <p>Profile</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/settings') }}" class="nav-link">
                    <i class="nav-icon fas fa-cogs"></i>
                    <p>Settings</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/help') }}" class="nav-link">
                    <i class="nav-icon fas fa-question-circle"></i>
                    <p>Bantuan</p>
                </a>
            </li>
            <li class="nav-divider"></li>
            <li class="nav-item">
                <a href="{{ route('logout') }}" class="nav-link text-danger"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                    <p>Logout</p>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
            
        </ul>
    </nav>
</aside>
<!-- /.control-sidebar -->

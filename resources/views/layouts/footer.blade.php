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
            <li class="nav-header" style="text-align: center; align-items: center;">
                <div style="position: relative; display: inline-block;">
                    <img src="{{ url('/profile') }}?v={{ now()->timestamp }}" class="rounded-circle text-center"
                        alt="user-image"
                        style="width: 100px; height: 100px; object-fit: cover; display: block; border: 3px solid #fff; box-shadow: 0 0 8px rgba(0,0,0,0.2);">
                    <button onclick="modalAction('{{ url('/upload_profile') }}')"
                        style="position: absolute; right: 0; bottom: 0; background-color: #007bff; color: white; border-radius: 50%; padding: 6px; font-size: 12px; border: 2px solid white;">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                </div>
            </li>
            <li class="nav-header text-center justify-content-center align-items-center">
                <h5>{{ auth()->user()->nama }}</h5>
            </li>
            <li class="nav-header">Pengaturan</li>
            <li class="nav-item">
                <a href="{{ url('/profile') }}" class="nav-link" id="profile">
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

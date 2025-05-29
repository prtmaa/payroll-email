<aside class="main-sidebar sidebar-dark-primary elevation-4">>


    <!-- Sidebar -->
    <div class="sidebar">

        <div class="row user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">

            </div>
            <div class="info">
                <a href="#" class="d-block">Gudang WMU</a>
            </div>
        </div>


        <!-- Sidebar Menu -->
        <nav class="mt-2 d-flex flex-column">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ url('periode') }}" class="nav-link {{ request()->is('periode') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

            </ul>
        </nav>

        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

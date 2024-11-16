
<aside class="main-sidebar elevation-4 sidebar-light-primary" style="background-color: #f4f6f9">
    <!-- Brand Logo -->
    <a href="" class="brand-link" style="background-color: #ffffff">
        <img src="{{asset('assets\img\eventms.png')}} " alt="EventMS" class="brand-image"
        style="opacity: .8">
        <br>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('assets\img\avatar.png')}}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{Auth::user()->name}}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{route("dashboard.admin")}}" class="nav-link {{ (Request::is('admin/dashboard') ? 'active' : '') }}" >
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link {{ (Request::is('admin/') ? 'active' : '') }}" >
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <p>
                            Calendar
                        </p>
                    </a>
                </li>
                <span class="pl-3 py-3 fw-bold text-uppercase " style="opacity: .8"> Setup</span>
                <li class="nav-item {{ (Request::is('admin/user-management', 'admin/pending-request', 'admin/agent-management') ? 'menu-is-opening menu-open' : '') }}">
                    <a class="nav-link {{ (Request::is('admin/user-management', 'admin/pending-request', 'admin/agent-management') ? 'active' : '') }}" >
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            User Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('user.index') }}" class="nav-link {{ (Request::is('admin/user-management', 'admin/pending-request' ) ? 'active' : '') }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>User</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('agent.index') }}" class="nav-link {{ (Request::is('admin/agent-management' ) ? 'active' : '') }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Agent</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item ">
                    <a href="" class="nav-link {{ (Request::is('admin/') ? 'active' : '') }}" >
                        <i class="nav-icon fas fa-calendar-plus"></i>
                        <p>
                            Event Management
                        </p>
                    </a>
                     
                </li>
                <span class="px-3 py-3 fw-bold text-uppercase " style="opacity: .8"> Report</span>
                <li class="nav-item">
                    <a href="" class="nav-link {{ (Request::is('admin/') ? 'active' : '') }}" >
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Report
                        </p>
                    </a>
                </li>
                <span class="px-3 py-3 fw-bold text-uppercase " style="opacity: .8"> Sign out</span>
                <li class="nav-item">
                    <a href="" class="nav-link {{ (Request::is('admin/') ? 'active' : '') }}" >
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            Sign out
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

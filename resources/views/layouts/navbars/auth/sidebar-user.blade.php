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
        <div class="offset-1 mt-3 pb-3 mb-3 d-flex align-items-center" style="overflow: hidden; white-space: nowrap;position: relative;">
            <div class="image mr-3">
                <img class="rounded-circle img-sm elevation-2" style="object-fit: cover;" src="{{Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture )  : asset('assets/img/avatar.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <div class="d-block">{{Auth::user()->name}}</div>
                <a href="{{ route('profile.admin') }}" class="d-block text-primary">My profile</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{route("home")}}" class="nav-link {{ (Request::is('home', 'my-profile') ? 'active' : '') }}" >
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>
                <li class="nav-header text-uppercase">Setup</li>
                <li class="nav-item {{ (Request::is('admin/user-management', 'admin/pending-request', 'admin/agent-management', 'admin/user-detail/*', 'admin/agent-detail/*') ? 'menu-is-opening menu-open' : '') }}">
                    <a href="#" class="nav-link {{ (Request::is('admin/user-management', 'admin/pending-request', 'admin/agent-management', 'admin/user-detail/*', 'admin/agent-detail/*') ? 'active' : '') }}" >
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Agent Management
                        </p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="{{ route('event.index') }}" class="nav-link {{ (Request::is('admin/event-management', 'admin/event-pending', 'admin/event-draft','admin/event-progress-main/*', 'admin/event-progress-schedule/*') ? 'active' : '') }}" >
                        <i class="nav-icon fas fa-calendar-plus"></i>
                        <p>
                            Event Management
                        </p>
                    </a>
                     
                </li>
                <li class="nav-header text-uppercase">Report</li>
                <li class="nav-item">
                    <a href="" class="nav-link {{ (Request::is('admin/') ? 'active' : '') }}" >
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Report
                        </p>
                    </a>
                </li>
                <li class="nav-header text-uppercase">Sign out</li>
                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link" >
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

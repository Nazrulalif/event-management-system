<aside class="main-sidebar elevation-4 sidebar-light-primary" style="background-color: #f4f6f9">
    <!-- Brand Logo -->
    <a href="" class="brand-link" style="background-color: #ffffff">
        <img src="{{asset('assets\img\eventms.png')}} " alt="EventMS" class="brand-image" style="opacity: .8">
        <br>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="offset-1 mt-3 pb-3 mb-3 d-flex align-items-center"
            style="overflow: hidden; white-space: nowrap;position: relative;">
            <div class="image mr-3">
                <img class="rounded-circle img-sm elevation-2" style="object-fit: cover;"
                    src="{{asset('assets/img/avatar.png') }}"
                    class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <div class="d-block">{{ Auth::guard('agent')->user()->name }}</div>
                <a href="{{ route('profile.agent') }}" class="d-block text-primary">My profile</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{route("home.agent")}}"
                        class="nav-link {{ (Request::is('agent/home', 'agent/my-profile', 'agent/view-event/*') ? 'active' : '') }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>
                <li class="nav-header text-uppercase">Setup</li>
                <li class="nav-item">
                    <a href="{{ route('agent.index.agent') }}"
                        class="nav-link {{ (Request::is('agent/agent-management', 'agent/agent-detail/*') ? 'active' : '') }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Agent Management
                        </p>
                    </a>
                </li>
                <li class="nav-header text-uppercase">Sign out</li>
                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link">
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

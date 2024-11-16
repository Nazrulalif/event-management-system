@extends('layouts.user_type.auth')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- /.content-header -->
    @include('layouts.content-header')
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            @include('admin.staff.register')

            <div class="card">
                <div class="card-header">
                    <div class="card-title">User List</div>
                    @if (!Request::is('admin/pending-request'))
                    <button type="button" data-toggle="modal" data-target="#modalRegister" class="btn btn-primary btn-sm float-right">
                        <i class="fas fa-plus-circle"></i>
                        Register
                    </button>
                    @endif
 
                </div>
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a href="{{ route('user.index') }}"
                                class="nav-link {{ (Request::is('admin/user-management')? 'active' : '' )}} ">
                                Active
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.pending') }}"
                                class="nav-link {{ (Request::is('admin/pending-request')? 'active' : '' )}} ">
                                Pending
                                <span class="right badge badge-danger">{{ $pendingCount }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    @yield('list-content')
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

@endsection

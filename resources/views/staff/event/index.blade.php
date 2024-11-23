@extends('layouts.user_type.auth')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- /.content-header -->
    @include('layouts.content-header')
    @include('staff.event.modal-add')

    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Event List</div>
                    <button type="button" data-toggle="modal" data-target="#modalAdd" class="btn btn-primary btn-sm float-right">
                        <i class="fas fa-plus-circle"></i>
                        Add New Event
                    </button>
                </div>
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a href="{{ route('event.index') }}"
                                class="nav-link {{ (Request::is('event-management')? 'active' : '' )}} ">
                                All
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('event.draft') }}"
                                class="nav-link {{ (Request::is('event-draft')? 'active' : '' )}} ">
                                My Draft
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    @yield('event-list')
                   
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

@endsection

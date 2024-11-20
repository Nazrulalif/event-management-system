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
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5>
                    <i class="icon fas fa-info"></i>
                    Alert!
                </h5>
                All sections must be complete to change status to approved
            </div>
            <div class="d-flex justify-content-end ">
                <div class="callout callout-danger w-1">
                    <div class="text-bold">Status: <span class="font-weight-normal text-danger">Draft</span></div>
                  </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Event Details</div>
                </div>
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a href="{{ route('event.progress', $data->id) }}"
                                class="nav-link {{ (Request::is('admin/event-progress-main/*')? 'active' : '' )}} ">
                                Main
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('event.progress.schedule', $data->id) }}"
                                class="nav-link {{ (Request::is('admin/event-progress-schedule/*')? 'active' : '' )}} ">
                                Event Schedule
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href=""
                                class="nav-link {{ (Request::is('admin/event-draft')? 'active' : '' )}} ">
                                Event Participants & Grouping
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('event.progress.target', $data->id) }}"
                                class="nav-link {{ (Request::is('admin/event-progress-target/*')? 'active' : '' )}} ">
                                Event Target & ROI
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('event.progress.budget', $data->id) }}"
                                class="nav-link {{ (Request::is('admin/event-progress-budget/*')? 'active' : '' )}} ">
                                Event Budget
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('event.progress.reward', $data->id) }}"
                                class="nav-link {{ (Request::is('admin/event-progress-reward/*')? 'active' : '' )}} ">
                                Event Rewards 
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body px-5">

                    @yield('progess-content')

                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
@endsection

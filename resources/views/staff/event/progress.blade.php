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
                    <div class="text-bold">Status: <span class="font-weight-normal {{ $data->status=='Approve'? 'text-success' : 'text-danger' }} ">{{ $data->status }}</span></div>
                  </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Event Details</div>
                </div>
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a href="{{ route('event.progress.user', $data->id) }}"
                                class="nav-link {{ (Request::is('event-progress-main/*')? 'active' : '' )}} ">
                                Main
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('event.progress.schedule.user', $data->id) }}"
                                class="nav-link {{ (Request::is('event-progress-schedule/*')? 'active' : '' )}} ">
                                Event Schedule
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('event.progress.staff.user', $data->id) }}"
                                class="nav-link {{ (Request::is('event-progress-staff-grouping/*')? 'active' : '' )}} ">
                                Event Staff Grouping
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('event.progress.agent.user', $data->id) }}"
                                class="nav-link {{ (Request::is('event-progress-agent-grouping/*')? 'active' : '' )}} ">
                                Event Agent Grouping
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('event.progress.target.user', $data->id) }}"
                                class="nav-link {{ (Request::is('event-progress-target/*')? 'active' : '' )}} ">
                                Event Target & ROI
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('event.progress.budget.user', $data->id) }}"
                                class="nav-link {{ (Request::is('event-progress-budget/*')? 'active' : '' )}} ">
                                Event Budget
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('event.progress.reward.user', $data->id) }}"
                                class="nav-link {{ (Request::is('event-progress-reward/*')? 'active' : '' )}} ">
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

@extends('layouts.user_type.auth')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- /.content-header -->
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content p-4">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-md-8">
                    <h1 class="display-4 font-weight-bold">{{ $data->event_title }}</h1>
                    <div class="mb-3">
                        @if ($data->status == 'Approve')
                            <span class="badge badge-success">{{ $data->status }}</span>
                        @elseif ($data->status == 'Draft')
                            <span class="badge badge-secondary">{{ $data->status }}</span>
                        @elseif ($data->status == 'Reject')
                            <span class="badge badge-danger">{{ $data->status }}</span>
                        @elseif ($data->status == 'Cancelled')
                            <span class="badge badge-warning">{{ $data->status }}</span>
                        @elseif ($data->status == 'Pending')
                            <span class="badge badge-info">{{ $data->status }}</span>
                        @endif
                    </div>
                    <!-- Event Information Card -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Event Information</h3>
                        </div>
                        <div class="card-body">
                            <p><i class="fas fa-calendar-alt mr-2"></i> {{ $start_date }} to {{ $end_date }}</p>
                            <p><i class="fas fa-clock mr-2"></i> {{ $start_time }} - {{ $end_time }} </p>
                            <p><i class="fas fa-map-marker-alt mr-2"></i> {{ $data->platform }}</p>
                            <p><i class="fas fa-marker mr-2"></i> {{ $data->objective }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-end justify-content-center ">
                    <!-- Event Poster Placeholder -->
                    <div class="card w-75">
                        @if ($data->poster_path)
                            <img src="{{ asset('storage/'. $data->poster_path) }}" class="card-img-top" alt="Event Poster">
                        @endif
                    </div>
                </div>
            </div>
            <hr>
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card h-100">
                        <div class="card-header">
                            <h3 class="card-title">Event Schedule</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ($schedule as $item)
                                <div class="col-md-6 pb-3">
                                    <div class="card bg-light mb-3 h-100">
                                        <div class="card-header">
                                            <div class="card-title">{{ $item->day_name }}</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="">Event Vanue</label>
                                                    <p>{{ $item->event_vanue }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="">Target</label>
                                                    <p>{{ $item->target }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="">Business Zone</label>
                                                    <p>{{ $item->business_zone }}</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <table class="table">
                                                    <thead>
                                                        <th>Time</th>
                                                        <th>Activity</th>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card h-100">
                        <div class="card-header">
                            <h3 class="card-title">Staff Group</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card bg-light mb-3">
                                        <div class="card-header">
                                            <div class="card-title">Group 1</div>
                                        </div>
                                        <div class="card-body">
                                            asdasd
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card h-100">
                        <div class="card-header">
                            <h3 class="card-title">Agent Group</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card bg-light mb-3">
                                        <div class="card-header">
                                            <div class="card-title">Group 1</div>
                                        </div>
                                        <div class="card-body">
                                            asdasd
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Event Budget and Staff Group -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h3 class="card-title">Event Target and ROI</h3>
                        </div>
                        <div class="card-body">
                            <p><strong>Total:</strong> $50,000</p>
                            <p><strong>Fee:</strong> $2,500</p>
                            <p><strong>Tax (8%):</strong> $4,000</p>
                            <p class="font-weight-bold"><strong>Grand Total:</strong> $56,700</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h3 class="card-title">Event Budget</h3>
                        </div>
                        <div class="card-body">
                            <p><strong>Leader:</strong> John Smith</p>
                            <p><strong>Vehicle:</strong> Store Van #3</p>
                            <p><strong>Members:</strong></p>
                            <ul class="list-unstyled">
                                <li>Alice Johnson</li>
                                <li>Bob Williams</li>
                                <li>Carol Davis</li>
                                <li>David Brown</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card h-100">
                        <div class="card-header">
                            <h3 class="card-title">Event Awards</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card bg-light mb-3">
                                        <div class="card-header">
                                            <div class="card-title">Internal Category</div>
                                        </div>
                                        <div class="card-body">
                                            asdasd
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light mb-3">
                                        <div class="card-header">
                                            <div class="card-title">External Category</div>
                                        </div>
                                        <div class="card-body">
                                            asdasd
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
@endsection

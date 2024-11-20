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
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-info"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Events Created</span>
                            <span class="info-box-number">
                                {{ $totalEvent }}
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-thumbs-up"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Approved Events</span>
                            <span class="info-box-number">
                                {{ $totalEventApprove }}
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-spinner"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Pending Events</span>
                            <span class="info-box-number">
                                {{ $totalEventPending }}
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-times-circle"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Rejected Events</span>
                            <span class="info-box-number">
                                {{ $totalEventReject }}
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Event Summary {{ $currentYear }}</div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="statusLineChart"
                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
</div>
<script src="{{asset('plugins/jquery/jquery.min.js')}} "></script>
<script>
$(document).ready(function () {
    const approved = @json(array_values($approved));
    const pending = @json(array_values($pending));
    const rejected = @json(array_values($rejected));

    const chartData = {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        datasets: [
            {
                label: 'Approved',
                borderColor: 'rgba(60, 141, 188, 1)',
                backgroundColor: 'rgba(60, 141, 188, 0.1)',
                data: approved,
                fill: false
            },
            {
                label: 'Pending',
                borderColor: 'rgba(255, 193, 7, 1)',
                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                data: pending,
                fill: false
            },
            {
                label: 'Rejected',
                borderColor: 'rgba(220, 53, 69, 1)',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                data: rejected,
                fill: false
            }
        ]
    };

    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true
            },
            tooltip: {
                enabled: true
            },
        },
        scales: {
            x: {
                grid: {
                    display: false
                }
            },
            y: {
                grid: {
                    display: true
                },
                beginAtZero: true
            }
        }
    };

    const ctx = $('#statusLineChart').get(0).getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: chartOptions,
        
    });
});
</script>

@endsection

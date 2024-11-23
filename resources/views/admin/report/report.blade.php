<div class="card">
    <div class="card-header">
        <a href="{{ route('report.print', ['start_date' => $startDate, 'end_date' => $endDate]) }}" rel="noopener" target="_blank" class="btn btn-default btn-sm float-right">
            <i class="fas fa-print"></i> Print
        </a>
    </div>
    <div class="card-body">
        <div class="container mt-4">
            <h1 class="text-center mb-4">Event Management Report</h1>
            <!-- Summary Cards -->
            <div class="row mb-4">
                <!-- Approved -->
                <div class="col-md-3">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $statusCounts['approved'] ?? 0 }}</h3>
                            <p>Approved</p>
                        </div>
                    </div>
                </div>
                <!-- Pending -->
                <div class="col-md-3">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $statusCounts['pending'] ?? 0 }}</h3>
                            <p>Pending</p>
                        </div>
                    </div>
                </div>
                <!-- Rejected -->
                <div class="col-md-3">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $statusCounts['rejected'] ?? 0 }}</h3>
                            <p>Rejected</p>
                        </div>
                    </div>
                </div>
                <!-- Cancelled -->
                <div class="col-md-3">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>{{ $statusCounts['cancelled'] ?? 0 }}</h3>
                            <p>Cancelled</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title">Event Status Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="eventChart" height="100"></canvas>
                </div>
            </div>

            <!-- Event List Table -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title">Event List</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Event Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($events as $event)
                            <tr>
                                <td>{{ $event->event_title }}</td>
                                <td>{{ $event->start_date }}</td>
                                <td>{{ $event->end_date }}</td>
                                <td>
                                    <span
                                        class="badge badge-{{ $event->status_color }}">{{ ucfirst($event->status) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('plugins/jquery/jquery.min.js')}} "></script>

    <script>
        var ctx = document.getElementById('eventChart').getContext('2d');
        var eventChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Approved', 'Pending', 'Reject', 'Cancelled'],
                datasets: [{
                    label: 'Number of Events',
                    data: [
                        {{$statusCounts['approved'] ?? 0}},
                        {{$statusCounts['pending'] ?? 0}},
                        {{$statusCounts['rejected'] ?? 0}},
                        {{$statusCounts['cancelled'] ?? 0}}
                    ],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#6c757d']
                }]
            },
            options: {
                responsive: true,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            precision: 0
                        }
                    }]
                }
            }
        });

    </script>

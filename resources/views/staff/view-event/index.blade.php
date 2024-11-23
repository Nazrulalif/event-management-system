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
                        <div class="card-header ">
                            <h3 class="card-title">Event Information</h3>
                            <a href="{{ route('view.print', $data->id) }}" rel="noopener" target="_blank" class="btn btn-default btn-sm float-right"><i class="fas fa-print"></i> Print</a>
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
                                            <div class="card-title">{{ $item->day_name }}
                                                ({{\Carbon\Carbon::parse($item->event_date)->format('d/m/Y')  }})</div>
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
                                                <table class="table table-condensed">
                                                    <thead>
                                                        <th width='20%'>Time</th>
                                                        <th>Activity</th>
                                                    </thead>
                                                    <tbody>
                                                        @foreach (json_decode($item->activity) as $activity)
                                                        <!-- Assuming data is JSON encoded -->
                                                        <tr>
                                                            <td>{{ \Carbon\Carbon::parse($activity->time)->format('h:i A') }}
                                                            </td> <!-- Format time -->
                                                            <td>{{ $activity->description }}</td>
                                                        </tr>
                                                        @endforeach
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
                                @foreach ($staff as $item)
                                <div class="col-md-6 pb-3">
                                    <div class="card bg-light mb-3 h-100">
                                        <div class="card-header">
                                            <div class="card-title">{{ $item->group_name }}</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="">Mentor</label>
                                                    <p>{{ $item->mentor_name }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="">Leader</label>
                                                    <p>{{ $item->leader_name }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="">Vehicle</label>
                                                    <p>{{ $item->vehicle }}</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Members</label>
                                                    <ul class="list-group">
                                                        @foreach ($item->members as $member)
                                                        <li class="list-group-item">{{ $member->name }}</li>
                                                        @endforeach
                                                        @foreach ($item->agent_members as $member)
                                                        <li class="list-group-item">{{ $member->name }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
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
                            <h3 class="card-title">Agent Group</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered table-striped">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="text-center">Nazir</th>
                                                <th class="text-center">Agent Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($agent as $item)
                                            <tr>
                                                <!-- Use rowspan to span Nazir name across all agent rows -->
                                                <td class="align-middle text-center"
                                                    rowspan="{{ $item->members->count() }}">
                                                    {{ $item->nazir }}
                                                </td>
                                                <!-- Display the first agent -->
                                                <td>{{ $item->members[0]->name }}</td>
                                            </tr>
                                            <!-- Display the remaining agents -->
                                            @foreach ($item->members->skip(1) as $member)
                                            <tr>
                                                <td>{{ $member->name }}</td>
                                            </tr>
                                            @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
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
                            <h3 class="card-title">Event Target</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless table-striped">
                                <thead class="text-center bg-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Product</th>
                                        <th>Outbase</th>
                                        <th>Inbase</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @foreach ($target as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->product }}</td>
                                        <td>{{ number_format($item->outbase) }}</td>
                                        <td>{{ number_format($item->inbase) }}</td>
                                        <td>{{ number_format($item->outbase) +  number_format($item->inbase)}} </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="font-weight-bold text-center">
                                    <tr>
                                        <td colspan="2">Total</td>
                                        <td>{{ number_format($target->sum('outbase')) }}</td>
                                        <td>{{ number_format($target->sum('inbase')) }}</td>
                                        <td>{{ number_format($target->sum('inbase')) + number_format($target->sum('outbase')) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h3 class="card-title">Event ROI</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless table-striped">
                                <thead class="text-center bg-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Product</th>
                                        <th>ARPU</th>
                                        <th>Sales/Physical Target</th>
                                        <th>Annualized Revenue (RM)</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @foreach ($target as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->product }}</td>
                                        <td>{{ number_format($item->arpu, 2) }}</td>
                                        <td>{{ number_format($item->sales_physical_target) }}</td>
                                        <td>{{ number_format($item->revenue, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="font-weight-bold text-center">
                                    <tr>
                                        <td colspan="2">Total</td>
                                        <td>{{ number_format($target->sum('arpu'), 2) }}</td>
                                        <td>{{ number_format($target->sum('sales_physical_target')) }}</td>
                                        <td>{{ number_format($target->sum('revenue'), 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card h-100">
                        <div class="card-header">
                            <h3 class="card-title">Event Budget</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless table-striped">
                                <thead class="text-center bg-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Items</th>
                                        <th>Days</th>
                                        <th>Frequency/Pax</th>
                                        <th>Price/unit</th>
                                        <th>Total Budget Required</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @foreach ($budget as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>{{ $item->days }}</td>
                                        <td>{{ $item->frequency }}</td>
                                        <td>{{ number_format($item->price_per_unit, 2) }}</td>
                                        <td>{{ number_format($item->total_budget, 2) }}</td>
                                        <td>{{ $item->remark }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="text-center font-weight-bold">
                                    <tr class="bg-light">
                                        <td colspan="5">Total</td>
                                        <td>{{ number_format($budget->sum('total_budget'), 2) }}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5">Agency Service Fee State (Local Vendor)</td>
                                        <td>{{ $fee_percent ?? 0 }}</td>
                                        <td>{{ number_format($fee, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5">Service Tax (6%)</td>
                                        <td>{{ $tax_percent ?? 6 }}</td>
                                        <td>{{ number_format($tax, 2) }}</td>
                                    </tr>
                                    <tr class="bg-light">
                                        <td colspan="5">Grand Total Budget</td>
                                        <td>{{ number_format($grand_total, 2) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
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
                                        <div class="card-body h-100">
                                            <div class="row justify-content-center mt-4">
                                                <!-- Second Place -->
                                                <div class="col-md-4">
                                                    <div class="card bg-secondary text-white text-center shadow">
                                                        <div class="ribbon-wrapper">
                                                            <div class="ribbon bg-info">2nd Place</div>
                                                        </div>
                                                        <div class="card-body">
                                                            <h4 class="font-weight-bold mb-3">RM {{ $internalPrizes['second'] }}</h4>
                                                            <i class="fas fa-medal fa-4x mb-3"></i>
                                                            <p class="mb-0">Well Done!</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- First Place -->
                                                <div class="col-md-4">
                                                    <div class="card bg-warning text-white text-center shadow">
                                                        <div class="ribbon-wrapper">
                                                            <div class="ribbon bg-primary">1st Place</div>
                                                        </div>
                                                        <div class="card-body">
                                                            <h4 class="font-weight-bold mb-3">RM {{ $internalPrizes['first'] }}</h4>
                                                            <i class="fas fa-trophy fa-4x mb-3"></i>
                                                            <p class="mb-0">Congratulations!</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Third Place -->
                                                <div class="col-md-4">
                                                    <div class="card" style="background-color: #FF7F50; color: white; text-align: center;">
                                                        <div class="ribbon-wrapper">
                                                            <div class="ribbon bg-danger">3rd Place</div>
                                                        </div>
                                                        <div class="card-body">
                                                            <h4 class="font-weight-bold mb-3">RM {{ $internalPrizes['third'] }}</h4>
                                                            <i class="fas fa-award fa-4x mb-3"></i>
                                                            <p class="mb-0">Great Job!</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row flex-column">
                                                <p>Term and Conditions</p>
                                                <p>{{ $internalCondition }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light mb-3">
                                        <div class="card-header">
                                            <div class="card-title">External Category</div>
                                        </div>
                                        <div class="card-body h-100">
                                            <div class="row justify-content-center mt-4">
                                                <!-- Second Place -->
                                                <div class="col-md-4">
                                                    <div class="card bg-secondary text-white text-center shadow">
                                                        <div class="ribbon-wrapper">
                                                            <div class="ribbon bg-info">2nd Place</div>
                                                        </div>
                                                        <div class="card-body">
                                                            <h4 class="font-weight-bold mb-3">RM {{ $externalPrizes['second'] }}</h4>
                                                            <i class="fas fa-medal fa-4x mb-3"></i>
                                                            <p class="mb-0">Well Done!</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- First Place -->
                                                <div class="col-md-4">
                                                    <div class="card bg-warning text-white text-center shadow">
                                                        <div class="ribbon-wrapper">
                                                            <div class="ribbon bg-primary">1st Place</div>
                                                        </div>
                                                        <div class="card-body">
                                                            <h4 class="font-weight-bold mb-3">RM {{ $externalPrizes['first'] }}</h4>
                                                            <i class="fas fa-trophy fa-4x mb-3"></i>
                                                            <p class="mb-0">Congratulations!</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Third Place -->
                                                <div class="col-md-4">
                                                    <div class="card" style="background-color: #FF7F50; color: white; text-align: center;">
                                                        <div class="ribbon-wrapper">
                                                            <div class="ribbon bg-danger">3rd Place</div>
                                                        </div>
                                                        <div class="card-body">
                                                            <h4 class="font-weight-bold mb-3">RM {{ $externalPrizes['third'] }}</h4>
                                                            <i class="fas fa-award fa-4x mb-3"></i>
                                                            <p class="mb-0">Great Job!</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row flex-column">
                                                <p>Term and Conditions</p>
                                                <p>{{ $externalCondition }}</p>
                                            </div>
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

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
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Event Report</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('report.post') }}" method="get" enctype="multipart/form-data" autocomplete="off">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Start Date</label>
                                    <input type="date" name="start_date" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="">End Date</label>
                                    <input type="date" name="end_date" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row float-right">
                            <div class="col-md-12">
                                <button class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if(!empty($events) && count($events) > 0)
            @include('admin.report.report')
            @endif
        </div>
    </section>
    <!-- /.content -->
</div>
@endsection

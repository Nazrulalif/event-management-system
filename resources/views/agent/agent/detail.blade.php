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
                <div class="col-md-3">
                    @include('agent.agent.sidebar')
                </div>
                <div class="col-md-9">
                    @include('agent.agent.content')
                </div>
            </div>

        </div>
    </section>
    <!-- /.content -->
</div>
@endsection

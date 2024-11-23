@extends('layouts.user_type.guest')

@section('content')
<style>
    @media (min-width: 992px) { 
        .card-lg{
            width: 30%;
        }
    }
</style>
<div class="login-logo">
    <img src="{{ asset('assets/img/eventms-white.png') }}" class="w-25 w-md-75 w-lg-50" alt="...">
</div>
<!-- /.login-logo -->
<div class="card card-lg">
    <div class="card-body login-card-body rounded-lg">
        @if(session()->has('error'))
        <div class="alert alert-danger" style="color: white">{{ session('error') }}</div>
        @elseif(session()->has('success'))
        <div class="alert alert-success" style="color: white">{{ session('success') }}</div>
        @endif
        {{-- <p class="login-box-msg">Sign in to start your session</p> --}}
        @yield('session')
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->
<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>




@endsection

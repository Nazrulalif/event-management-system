@extends('layouts.user_type.guest')

@section('content')

<div class="login-logo">
  <img src="{{ asset('assets/img/eventms.png') }}" class="w-25 w-md-75 w-lg-50" alt="...">


</div>
<!-- /.login-logo -->
<div class="card">
    <div class="card-body login-card-body">

        @if(session()->has('error'))
        <div class="alert alert-danger" style="color: white">{{ session('error') }}</div>
        @endif
        {{-- <p class="login-box-msg">Sign in to start your session</p> --}}

        <form action="{{ route('login.post') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="input-group mb-3">
                <input type="email" class="form-control" name="email" placeholder="Emel">
                @error('email')
                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                @enderror
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input type="password" class="form-control" name="password" placeholder="Kata laluan">
                @error('password')
                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                @enderror
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block">Log In</button>
                </div>
            </div>
        </form>
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

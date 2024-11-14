@extends('layouts.user_type.guest')

@section('content')
<style>
  .wrapper{
    min-height: 0%;
  }
</style>
<body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <img src="\assets\img\logo_sekolah.png" width="50%" class="navbar-brand-img h-100" alt="...">

      </div>
      <!-- /.login-logo -->
      <div class="card">
        <div class="card-body login-card-body">

          @if(session()->has('error'))
            <div class="alert alert-danger" style="color: white">{{ session('error') }}</div>
          @endif
          {{-- <p class="login-box-msg">Sign in to start your session</p> --}}
  
          <form action="{{ route('login.post') }}" method="post" enctype="multipart/form-data" >
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

    {{-- sweet alert --}}
    @if (Session::get('success'))
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        Toast.fire({
            icon: "success",
            title: "{{Session::get('success')}}"
        });

    </script>
      @endif

  </body>




@endsection

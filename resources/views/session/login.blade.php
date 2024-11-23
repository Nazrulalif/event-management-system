@extends('session.index')

@section('session')
<form action="{{ route('login.post') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="input-group mb-3">
        <input type="email" class="form-control" name="email" placeholder="Email">
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
        <input type="password" class="form-control" name="password" placeholder="Password">
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
    <hr>
    <div class="d-flex flex-column text-center">
        <span>Or</span>
        <a href="{{ route('register') }}">Create New Account</a>
    </div>
</form>
@endsection

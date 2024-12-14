@extends('session.index')

@section('session')
<form action="{{ route('register.post') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="input-group mb-3">
        <input type="text" class="form-control" name="name" placeholder="Full Name" required>
        @error('name')
        <p class="text-danger text-xs mt-2">{{ $message }}</p>
        @enderror
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-user"></span>
            </div>
        </div>
    </div>
    <div class="input-group mb-3">
        <select name="gender" id="gender" class="form-control" aria-placeholder="Select gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
        @error('gender')
        <p class="text-danger text-xs mt-2">{{ $message }}</p>
        @enderror
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-male"></span>
            </div>
        </div>
    </div>
    <div class="input-group mb-3">
        <select name="team" id="team" class="form-control" aria-placeholder="Select Team" required>
            <option value="">Select Team</option>
            <option value="Sales Planning (SP)">Sales Planning (SP)</option>
            <option value="Sales Operation MKS (SO MKS)">Sales Operation MKS (SO MKS)</option>
            <option value="Sales Operation MKU (SO MKU)">Sales Operation MKU (SO MKU)</option>
            <option value="Channel Management (CM)">Channel Management (CM)</option>
            <option value="Sales Management (SM)">Sales Management (SM)</option>
            <option value="Coverage & Capacity (CNC)">Coverage & Capacity (CNC)</option>
        </select>
        @error('name')
        <p class="text-danger text-xs mt-2">{{ $message }}</p>
        @enderror
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-people-arrows"></span>
            </div>
        </div>
    </div>
    <div class="input-group mb-3">
        <input type="email" class="form-control" name="email" placeholder="Email" required>
        @error('email')
        <p class="text-danger text-xs mt-2">{{ $message }}</p>
        @enderror
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-envelope"></span>
            </div>
        </div>
    </div>
    {{-- <div class="input-group mb-3">
        <input type="password" class="form-control" name="password" placeholder="Password" required>
        @error('password')
        <p class="text-danger text-xs mt-2">{{ $message }}</p>
        @enderror
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
    </div> --}}
    {{-- <div class="input-group mb-3">
        <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>
        @error('password_confirmation')
        <p class="text-danger text-xs mt-2">{{ $message }}</p>
        @enderror
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
    </div> --}}
    <div class="row">
        <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </div>
    </div>
    <hr>
    <div class="d-flex flex-column text-center">
        <span>Already have account?</span>
        <a href="{{ route('login') }}">Log in</a>
    </div>
</form>
@endsection
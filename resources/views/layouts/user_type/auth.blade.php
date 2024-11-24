@extends('layouts.app')

@section('auth')

@if(Auth::guard('agent')->check())
    @include('layouts.navbars.auth.sidebar-agent')
@elseif(Auth::user()->role_guid ==1)
    @include('layouts.navbars.auth.sidebar-admin')
@else
    @include('layouts.navbars.auth.sidebar-user')
@endif

<main class="main-content">
    @include('layouts.navbars.auth.nav')
    <div class="py-0">
        @yield('content')
    </div>
    @include('layouts.footers.footer')

</main>
@endsection

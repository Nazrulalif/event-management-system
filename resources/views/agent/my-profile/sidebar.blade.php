<div class="card card-primary card-outline">
    <div class="card-body box-profile">
        <div class="text-center">
            <img class="profile-user-img img-fluid img-circle" src="{{ asset('assets/img/avatar.png') }}"
                alt="User profile picture">
        </div>

        <h3 class="profile-username text-center">{{ Auth::guard('agent')->user()->name }}</h3>

        <p class="text-muted text-center">{{ Auth::guard('agent')->user()->email }}r</p>

        <ul class="list-group list-group-unbordered mb-3">
            <li class="list-group-item">
                <b>Phone Number</b> <a class="float-right">{{ Auth::guard('agent')->user()->phone_number }}</a>
            </li>
            <li class="list-group-item">
                <b>Channel</b> <a class="float-right">{{ Auth::guard('agent')->user()->channel }}</a>
            </li>
            
        </ul>
    </div>
    <!-- /.card-body -->
</div>

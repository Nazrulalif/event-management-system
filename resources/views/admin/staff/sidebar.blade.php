<div class="card card-primary card-outline h-100">
    <div class="card-body box-profile">
        <div class="text-center">
            <img class="profile-user-img img-fluid img-circle" src="{{ asset('assets/img/avatar.png') }}"
                alt="User profile picture">
        </div>

        <h3 class="profile-username text-center">{{ $user->name }}</h3>

        <p class="text-muted text-center">{{ $user->email }}r</p>

        <ul class="list-group list-group-unbordered mb-3">
            <li class="list-group-item">
                <b>Team</b> <a class="float-right">{{ $user->team }}</a>
            </li>
            <li class="list-group-item">
                <b>Unit</b> <a class="float-right">{{ $user->unit }}</a>
            </li>
            <li class="list-group-item">
                <b>State</b> <a class="float-right">{{ $user->state }}</a>
            </li>
        </ul>
    </div>
    <!-- /.card-body -->
</div>

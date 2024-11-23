<div class="card card-primary card-outline ">
    <div class="card-body box-profile">
        <div class="d-flex flex-column align-items-center">
            <img class="rounded-circle img-lg" style="object-fit: cover;"
              src="{{Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture )  : asset('assets/img/avatar.png') }}"
                alt="User profile picture">
                <h3 class="profile-username text-center">{{ Auth::user()->name }}</h3>
                <p class="text-muted text-center">{{ Auth::user()->email }}</p>
        </div>

       
        <ul class="list-group list-group-unbordered mb-3">
            <li class="list-group-item">
                <b>Team</b> <a class="float-right">{{ Auth::user()->team }}</a>
            </li>
            <li class="list-group-item">
                <b>Unit</b> <a class="float-right">{{ Auth::user()->unit }}</a>
            </li>
            <li class="list-group-item">
                <b>State</b> <a class="float-right">{{ Auth::user()->state }}</a>
            </li>
        </ul>
    </div>
    <!-- /.card-body -->
</div>

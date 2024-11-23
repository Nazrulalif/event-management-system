<div class="card">
    <div class="card-header">
        <div class="card-title">Settings</div>
    </div>
    <div class="card-body">
        <form class="form-horizontal" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label required">Profile Picture</label>

                <div class="col-sm-10 d-flex">
                    <div class="d-flex mb-4">
                        <img id="selectedAvatar" src="{{Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture )  : asset('assets/img/avatar.png') }}"
                        class="rounded-circle img-lg" style="object-fit: cover;" alt="example placeholder" />

                        <div class="d-flex ml-3 align-self-center">
                            <div data-mdb-ripple-init class="btn btn-outline-secondary btn-sm btn-rounded">
                                <label class="form-label m-1 cursor-pointer" for="customFile2">Choose file</label>
                                <input type="file" class="form-control d-none" id="customFile2" name="profile_picture" onchange="displaySelectedImage(event, 'selectedAvatar')" />
                            </div>
                        </div>

                        
                    </div>

                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label required">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" value="{{ Auth::user()->name }}" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label required">Email</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label required">Gender</label>
                <div class="col-sm-10">
                    <select name="gender" id="gender" class="form-control" required>
                        <option value="">Select Gender</option>
                        <option value="Male" {{ Auth::user()->gender == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ Auth::user()->gender == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="team" class="col-sm-2 col-form-label required">Team</label>
                <div class="col-sm-10">
                    <select name="team" id="team" class="form-control" required>
                        <option value="">Select Team</option>
                        <option value="Sales Planning (SP)"
                            {{ Auth::user()->team == 'Sales Planning (SP)' ? 'selected' : '' }}>
                            Sales Planning (SP)
                        </option>
                        <option value="Sales Operation MKS (SO MKS)"
                            {{ Auth::user()->team == 'Sales Operation MKS (SO MKS)' ? 'selected' : '' }}>
                            Sales Operation MKS (SO MKS)
                        </option>
                        <option value="Sales Operation MKU (SO MKU)"
                            {{ Auth::user()->team == 'Sales Operation MKU (SO MKU)' ? 'selected' : '' }}>
                            Sales Operation MKU (SO MKU)
                        </option>
                        <option value="Channel Management (CM)"
                            {{ Auth::user()->team == 'Channel Management (CM)' ? 'selected' : '' }}>
                            Channel Management (CM)
                        </option>
                        <option value="Sales Management (SM)"
                            {{ Auth::user()->team == 'Sales Management (SM)' ? 'selected' : '' }}>
                            Sales Management (SM)
                        </option>
                        <option value="Coverage & Capacity (CNC)"
                            {{ Auth::user()->team == 'Coverage & Capacity (CNC)' ? 'selected' : '' }}>
                            Coverage & Capacity (CNC)
                        </option>
                    </select>
                </div>

            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label required">Unit</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="unit" value="{{ Auth::user()->unit }}" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label required">State</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="state" value="{{ Auth::user()->state }}" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="offset-sm-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Change Password</div>
    </div>
    <div class="card-body">
        <form action="{{ route('profile.password') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label required">New Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" name="password" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label required">Confirmation Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" name="password_confirmation" required>
            </div>
        </div>
        <div class="form-group row">
            <div class="offset-sm-2 col-sm-10">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
        </form>
    </div>
</div>

<script>
    function displaySelectedImage(event, elementId) {
    const selectedImage = document.getElementById(elementId);
    const fileInput = event.target;

    if (fileInput.files && fileInput.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            selectedImage.src = e.target.result;
        };

        reader.readAsDataURL(fileInput.files[0]);
    }
}
</script>

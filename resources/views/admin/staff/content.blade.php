<div class="card h-100">
    <div class="card-header">
        <div class="card-title">Settings</div>
    </div>
    <div class="card-body">
        <form class="form-horizontal" method="POST" action="{{ route('user.detail.update', $user->id) }}" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label required">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label required">Email</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label required">Gender</label>
                <div class="col-sm-10">
                    <select name="gender" id="gender" class="form-control" required>
                        <option value="">Select Gender</option>
                        <option value="Male" {{ $user->gender == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $user->gender == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="team" class="col-sm-2 col-form-label required">Team</label>
                <div class="col-sm-10">
                    <select name="team" id="team" class="form-control" required>
                        <option value="">Select Team</option>
                        <option value="Sales Planning (SP)"
                            {{ $user->team == 'Sales Planning (SP)' ? 'selected' : '' }}>
                            Sales Planning (SP)
                        </option>
                        <option value="Sales Operation MKS (SO MKS)"
                            {{ $user->team == 'Sales Operation MKS (SO MKS)' ? 'selected' : '' }}>
                            Sales Operation MKS (SO MKS)
                        </option>
                        <option value="Sales Operation MKU (SO MKU)"
                            {{ $user->team == 'Sales Operation MKU (SO MKU)' ? 'selected' : '' }}>
                            Sales Operation MKU (SO MKU)
                        </option>
                        <option value="Channel Management (CM)"
                            {{ $user->team == 'Channel Management (CM)' ? 'selected' : '' }}>
                            Channel Management (CM)
                        </option>
                        <option value="Sales Management (SM)"
                            {{ $user->team == 'Sales Management (SM)' ? 'selected' : '' }}>
                            Sales Management (SM)
                        </option>
                        <option value="Coverage & Capacity (CNC)"
                            {{ $user->team == 'Coverage & Capacity (CNC)' ? 'selected' : '' }}>
                            Coverage & Capacity (CNC)
                        </option>
                    </select>
                </div>

            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label required">Unit</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="unit" value="{{ $user->unit }}" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label required">State</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="state" value="{{ $user->state }}" required>
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

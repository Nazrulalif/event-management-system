<div class="card">
    <div class="card-header">
        <div class="card-title">Settings</div>
    </div>
    <div class="card-body">
        <form class="form-horizontal" method="POST" action="{{ route('profile.update.agent') }}" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label required">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" value="{{ Auth::guard('agent')->user()->name }}" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label required">Email</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" name="email" value="{{ Auth::guard('agent')->user()->email }}" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label required">Phone Number</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="phone" value="{{ Auth::guard('agent')->user()->phone_number }}" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label required">Channel</label>
                <div class="col-sm-10">
                    <select name="channel" id="channel" class="form-control" required>
                        <option value="">Select Channel</option>
                        <option value="Rovers" {{ Auth::guard('agent')->user()->channel == 'Rovers' ? 'selected' : '' }}>Rovers</option>
                        <option value="Agents (UCA/UCP)" {{ Auth::guard('agent')->user()->channel == 'Agents (UCA/UCP)' ? 'selected' : '' }}>Agents (UCA/UCP)</option>
                        <option value="Nextstar" {{ Auth::guard('agent')->user()->channel == 'Nextstar' ? 'selected' : '' }}>Nextstar</option>
                    </select>
                </div>
            </div>
            <div  id="company-name-container" style="display: none;">
                <div class="form-group row">
                    <label for="company_name" class="col-sm-2 col-form-label required">Family/Partners Company Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="company_name" id="company_name" value="{{Auth::guard('agent')->user()->channel == 'Rovers'? Auth::guard('agent')->user()->attribute : '' }}">
                    </div>
                </div>
            </div>
            <div id="unit-container" style="display: none;">
                <div class="form-group row" >
                    <label for="unit" class="col-sm-2 col-form-label required">Unit</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="unit" id="unit" value="{{Auth::guard('agent')->user()->channel == 'Nextstar'? Auth::guard('agent')->user()->attribute : '' }}">
                    </div>
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
        <form action="{{ route('profile.password.agent') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
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
    document.addEventListener('DOMContentLoaded', function () {
        const channelSelect = document.getElementById('channel');
        const companyNameContainer = document.getElementById('company-name-container');
        const unitContainer = document.getElementById('unit-container');
        const companyNameInput = document.getElementById('company_name');
        const unitInput = document.getElementById('unit');

        // Function to toggle fields
        const toggleFields = () => {
            const selectedChannel = channelSelect.value;

            // Reset visibility and required attributes
            companyNameContainer.style.display = 'none';
            unitContainer.style.display = 'none';
            companyNameInput.removeAttribute('required');
            unitInput.removeAttribute('required');

            if (selectedChannel === 'Rovers') {
                companyNameContainer.style.display = 'block';
                companyNameInput.setAttribute('required', 'required');
            } else if (selectedChannel === 'Nextstar') {
                unitContainer.style.display = 'block';
                unitInput.setAttribute('required', 'required');
            }
        };

        // Call on page load
        toggleFields();

        // Call on channel change
        channelSelect.addEventListener('change', toggleFields);
    });
</script>



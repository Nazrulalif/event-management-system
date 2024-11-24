 <!-- Modal -->
 <div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <form action="{{ route('agent.add.user') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">User Registration</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body px-4">
                    <div class="form-group">
                        <label for="name" class="required">Full Name</label>
                        <input type="text" class="form-control" name="name" id="name" required>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="email" class="required">Email</label>
                                <input type="email" class="form-control" name="email" id="email" required>
                            </div>
                            <div class="col-md-6 pt-3 pt-md-0 pt-lg-0">
                                <label for="phone" class="required">Phone Number</label>
                                <input type="number" class="form-control" name="phone" id="phone" required>
                            </div>
                        </div>
                        
                    </div>
                    <div class="form-group">
                        <label for="channel" class="required">Channel</label>
                        <select name="channel" id="channel" class="form-control" required>
                            <option value="">Select Channel</option>
                            <option value="Rovers">Rovers</option>
                            <option value="Agents (UCA/UCP)">Agents (UCA/UCP)</option>
                            <option value="Nextstar">Nextstar</option>
                        </select>
                    </div>
                    <div class="form-group" id="company-name-container" style="display: none;">
                        <label for="company_name" class="required">Family/Partners Company Name</label>
                        <input type="text" class="form-control" name="company_name" id="company_name">
                    </div>
                    <div class="form-group" id="unit-container" style="display: none;">
                        <label for="unit" class="required">Unit</label>
                        <input type="text" class="form-control" name="unit" id="unit">
                    </div>
                </div>
                

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const teamSelect = document.getElementById('channel');
        const companyNameContainer = document.getElementById('company-name-container');
        const unitContainer = document.getElementById('unit-container');
        const companyNameInput = document.getElementById('company_name');
        const unitInput = document.getElementById('unit');

        teamSelect.addEventListener('change', function () {
            const selectedValue = this.value;

            // Reset visibility and required attributes
            companyNameContainer.style.display = 'none';
            unitContainer.style.display = 'none';
            companyNameInput.removeAttribute('required');
            unitInput.removeAttribute('required');

            if (selectedValue === 'Rovers') {
                companyNameContainer.style.display = 'block';
                companyNameInput.setAttribute('required', 'required');
            } else if (selectedValue === 'Nextstar') {
                unitContainer.style.display = 'block';
                unitInput.setAttribute('required', 'required');
            }
        });
    });
</script>



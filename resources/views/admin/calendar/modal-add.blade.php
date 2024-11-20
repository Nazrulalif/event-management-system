<div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="modal-body px-4">
                    <div class="form-group">
                        <label for="title" class="required">Event Title</label>
                        <input type="text" class="form-control" name="title" id="title" required>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="startDate" class="required">Start Date</label>
                                <input type="date" class="form-control" name="start_date" id="startDate" required>
                            </div>
                            <div class="col-md-6">
                                <label for="endDate" class="required">End Date</label>
                                <input type="date" class="form-control" name="end_date" id="endDate" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="start_time" class="required">Start Time</label>
                                <input type="time" class="form-control" name="start_time" id="start_time" required>
                            </div>
                            <div class="col-md-6">
                                <label for="end_time" class="required">End Time</label>
                                <input type="time" class="form-control" name="end_time" id="end_time" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="platform" class="required">Platform</label>
                        <select name="platform" id="platform" class="form-control" required>
                            <option value="">Select Platform</option>
                            <option value="Highrise">Highrise</option>
                            <option value="Landed">Landed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="platform" class="required">Objective</label>
                        <textarea name="objective" id="objective" cols="30" rows="4" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="poster">Upload Poster (Optional)</label>
                        <input type="file" class="form-control" name="poster">
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
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');

        // Validate Time
        startTimeInput.addEventListener('change', function () {
            const startTime = startTimeInput.value;
            if (startTime) {
                endTimeInput.setAttribute('min', startTime);
            } else {
                endTimeInput.removeAttribute('min');
            }
        });

        endTimeInput.addEventListener('change', function () {
            const startTime = startTimeInput.value;
            const endTime = endTimeInput.value;
            if (startTime && endTime < startTime) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Time',
                    text: 'End time cannot be earlier than start time.'
                });
                endTimeInput.value = ''; // Clear the invalid end time
            }
        });

        // Validate Date
        const validateDates = () => {
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;

            if (startDate && endDate) {
                if (endDate < startDate) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Date',
                        text: 'End date cannot be earlier than start date.'
                    });
                    endDateInput.value = ''; // Clear the invalid end date
                } else {
                    // Ensure start date cannot exceed end date
                    startDateInput.setAttribute('max', endDate);
                }
            }

            // Reset max for start date if end date is cleared
            if (!endDate) {
                startDateInput.removeAttribute('max');
            }
        };

        // Set minimum start date to today
        const today = new Date().toISOString().split('T')[0];
        startDateInput.setAttribute('min', today);

        startDateInput.addEventListener('change', function () {
            // Set the minimum date for end date and validate
            if (startDateInput.value) {
                endDateInput.setAttribute('min', startDateInput.value);
            } else {
                endDateInput.removeAttribute('min');
            }
            validateDates();
        });

        endDateInput.addEventListener('change', validateDates);
    });
</script>

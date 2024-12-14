@extends('admin.event.progress')

@section('progess-content')

<div class="d-flex justify-content-end">
    <div id="progressContainer">
        <div id="progressBtn">Checking...</div>
    </div>
</div>

<form action="{{ route('event.progress.main', $data->id) }}" method="POST" enctype="multipart/form-data"
    autocomplete="off">
    @csrf
    @method('PUT')
    <div class="form-group row">
        <label for="title" class="col-sm-2 col-form-label required">Event Title</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" name="title" value="{{ old('title', $data->event_title) }}"
                required>
            @error('title')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="startDate" class="col-sm-2 col-form-label required">Start Date</label>
        <div class="col-sm-4">
            <input type="date" class="form-control" name="start_date" id="startDate"
                value="{{ old('start_date', $data->start_date) }}" required>
            @error('start_date')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <label for="endDate" class="col-sm-2 col-form-label required">End Date</label>
        <div class="col-sm-4">
            <input type="date" class="form-control" name="end_date" id="endDate"
                value="{{ old('end_date', $data->end_date) }}" required>
            @error('end_date')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="start_time" class="col-sm-2 col-form-label required">Start Time</label>
        <div class="col-sm-4">
            <input type="time" class="form-control" name="start_time" id="start_time"
                value="{{ old('start_time', $data->start_time) }}" required>
            @error('start_time')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <label for="end_time" class="col-sm-2 col-form-label required">End Time</label>
        <div class="col-sm-4">
            <input type="time" class="form-control" name="end_time" id="end_time"
                value="{{ old('end_time', $data->end_time) }}" required>
            @error('end_time')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="platform" class="col-sm-2 col-form-label required">Platform</label>
        <div class="col-sm-4">
            <select name="platform" id="platform" class="form-control" required>
                <option value="">Select Platform</option>
                <option value="Highrise" {{ old('platform', $data->platform) == 'Highrise' ? 'selected' : '' }}>Highrise
                </option>
                <option value="Landed" {{ old('platform', $data->platform) == 'Landed' ? 'selected' : '' }}>Landed
                </option>
            </select>
            @error('platform')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="objective" class="col-sm-2 col-form-label">Objective (Not Required)</label>
        <div class="col-sm-4">
            <textarea name="objective" id="objective" cols="30" rows="4" class="form-control"
                >{{ old('objective', $data->objective) }}</textarea>
            @error('objective')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="poster" class="col-sm-2 col-form-label">Upload Poster (Not Required)</label>
        <div class="col-sm-4">
            <input type="file" class="form-control" name="poster">
            <small class="text-muted ">Current Poster:</small>
            <div class="col-md-8">
                @if ($data->poster_path)
                <img src="{{ asset('storage/' . $data->poster_path) }}" alt="Poster"
                    style="width: 100%; height: auto; max-height: 400px; object-fit: contain;" />
                @else
                <small class="text-muted text-center">No poster uploaded.</small>
                @endif

                @error('poster')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

    </div>
    <div class="form-group">
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </div>
</form>
<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}} "></script>
<script>
    $(document).ready(function () {
        const progressContainer = $('#progressContainer');
        const eventId = @json($data->id); // Safely include the event ID

        if (!eventId) {
            console.error('Event ID is missing.');
            progressContainer.html(`
                <div class="d-flex flex-row align-items-center text-danger">
                    <i class="far fa-times-circle text-danger"></i>
                    <span class="ml-1">Error</span>
                </div>
            `);
            return;
        }

        // Function to check progress from the backend
        const checkProgress = () => {
            $.ajax({
                url: `/admin/events/${eventId}/check-progress-main`,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.complete) {
                        progressContainer.html(`
                            <div class="d-flex flex-row align-items-center text-md">
                                <i class="far fa-check-circle text-success"></i>
                                <span class="text-success ml-1">Complete</span>
                            </div>
                        `);
                    } else {
                        progressContainer.html(`
                            <div class="d-flex flex-row align-items-center text-danger">
                                <i class="far fa-times-circle text-danger"></i>
                                <span class="ml-1">Incomplete</span>
                            </div>
                        `);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error checking progress:', error);
                    progressContainer.html(`
                        <div class="d-flex flex-row align-items-center text-danger">
                            <i class="far fa-times-circle text-danger"></i>
                            <span class="ml-1">Error</span>
                        </div>
                    `);
                }
            });
        };

        // Check progress on page load
        checkProgress();
    });

</script>

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
@endsection

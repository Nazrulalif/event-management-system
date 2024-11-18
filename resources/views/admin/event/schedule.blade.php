@extends('admin.event.progress')

@section('progess-content')
<div class="container">
    <div class="d-flex justify-content-end pb-3">
        <div id="progressContainer">
            <div id="progressBtn">Checking...</div>
        </div>
    </div>
    <form action="{{ route('event.schedule.update', $data->id) }}" method="POST" enctype="multipart/form-data"
        autocomplete="off">
        @csrf

        <div class="row">
            @if ($existingSchedules->isEmpty())
            <!-- If no schedules exist, generate default empty days -->
            @for ($i = 1; $i <= $data->period; $i++)
                <div class="col-md-6 pb-3">
                    <div class="card h-100 mb-3">
                        <div class="card-header">
                            <div class="card-title">Day {{ $i }}</div>
                        </div>
                        <div class="card-body">
                            <input type="hidden" name="schedules[{{ $i - 1 }}][day_name]" value="Day {{ $i }}">

                            @php
                            $currentDate = $startDate->copy()->addDays($i - 1)->format('Y-m-d');
                            @endphp

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="">Date</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="date" name="schedules[{{ $i - 1 }}][date]" class="form-control"
                                        value="{{ $currentDate }}" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="">Target</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="number" name="schedules[{{ $i - 1 }}][target]" class="form-control"
                                        required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="">Business Zone</label>
                                </div>
                                <div class="col-md-9">
                                    <select name="schedules[{{ $i - 1 }}][business_zone]" class="form-control" required>
                                        <option value="">Select Business Zone</option>
                                        <option
                                            value="AIR KEROH/ AIR MOLEK/ MELAKA UNIT/ JASIN – (MKS- melaka selatan), ALOR GAJAH/ BERTAM/ MALIM/ MASJID TANAH – (MKU- melaka utara)">
                                            All</option>
                                        <option value="AIR KEROH/ AIR MOLEK/ MELAKA UNIT/ JASIN – (MKS- melaka selatan)
                                                ">AIR KEROH/ AIR MOLEK/ MELAKA UNIT/ JASIN – (MKS- melaka selatan)
                                        </option>
                                        <option value="ALOR GAJAH/ BERTAM/ MALIM/ MASJID TANAH – (MKU- melaka utara)
                                            ">ALOR GAJAH/ BERTAM/ MALIM/ MASJID TANAH – (MKU- melaka utara)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="">Event Vanue</label>
                                </div>
                                <div class="col-md-9">
                                    
                                        <textarea name="schedules[{{ $i - 1 }}][event_vanue]" class="form-control" id="" cols="30" rows="5" required></textarea>
                                </div>
                            </div>
                            <div class="activities-container">
                                <div class="activity-row mb-2 row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Time</label>
                                            <input type="time" name="schedules[{{ $i - 1 }}][activities][0][time]"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label>Activity</label>
                                            <input type="text"
                                                name="schedules[{{ $i - 1 }}][activities][0][description]"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary add-activity" data-day="{{ $i - 1 }}">
                                Add Activity
                            </button>
                        </div>
                    </div>
                </div>
                @endfor
                @else
                <!-- Display existing schedules in same layout as new schedules -->
                @foreach ($existingSchedules as $schedule)
                <div class="col-md-6 pb-3">
                    <div class="card mb-3 h-100">
                        <div class="card-header">
                            <div class="card-title">Day {{ $loop->index + 1 }} - {{ $schedule->event_date }}</div>
                        </div>
                        <div class="card-body">
                            <input type="hidden" name="schedules[{{ $loop->index }}][day_name]"
                                value="{{ $schedule->day_name }}">
                            <input type="hidden" name="schedules[{{ $loop->index }}][date]"
                                value="{{ $schedule->event_date }}">

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="">Date</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="date" name="schedules[{{ $loop->index }}][date]" class="form-control"
                                        value="{{ $schedule->event_date }}" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="">Target</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="number" name="schedules[{{ $loop->index  }}][target]"
                                        class="form-control" value="{{ $schedule->target }}" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="">Business Zone</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea name="schedules[{{$loop->index  }}][business_zone]" class="form-control" 
                                            cols="30" rows="3" readonly>{{ $schedule->business_zone }}</textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="">Event Vanue</label>
                                </div>
                                <div class="col-md-9">
                                        <textarea name="schedules[{{$loop->index  }}][event_vanue]" class="form-control" 
                                           cols="30" rows="3" readonly>{{ $schedule->event_vanue }}</textarea>
                                </div>
                            </div>

                            <div class="activities-container">
                                @if ($schedule->activity)
                                @foreach (json_decode($schedule->activity, true) as $activityIndex => $activity)
                                <div class="activity-row mb-2 row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Time</label>
                                            <input type="time"
                                                name="schedules[{{ $loop->index }}][activities][{{ $activityIndex }}][time]"
                                                value="{{ $activity['time'] }}" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label>Activity</label>
                                            <input type="text"
                                                name="schedules[{{ $loop->index }}][activities][{{ $activityIndex }}][description]"
                                                value="{{ $activity['description'] }}" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <p>No activities added yet.</p>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
                @endforeach
                @endif
        </div>

        <div class="d-flex flex-column align-items-end">
            @if ($existingSchedules->isEmpty())
            <small class="text-muted pb-1 align-self-start">Please ensure that all the event details are correct before
                saving. You can add multiple activities for each day if necessary.</small>

            <button type="submit" class="btn btn-primary">Save Schedules</button>
            @else
            <small class="text-warning text-muted pb-1 align-self-start">Warning: This will clear all existing
                schedules. Ensure that you want to remove all data before proceeding.</small>

            <button type="submit" name="clear_schedule" value="1" class="btn btn-danger">Clear Schedule</button>
            @endif
        </div>

    </form>
</div>
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
                url: `/admin/events/${eventId}/check-progress-schedule`,
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
    $(document).ready(function () {
        $('.add-activity').click(function () {
            const dayIndex = $(this).data('day');
            const container = $(this).siblings('.activities-container');
            const activityCount = container.find('.activity-row').length;

            const newActivity = `
                <div class="activity-row mb-2 row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Time</label>
                            <input type="time" name="schedules[${dayIndex}][activities][${activityCount}][time]" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="form-group">
                            <label>Activity</label>
                            <input type="text" name="schedules[${dayIndex}][activities][${activityCount}][description]" class="form-control" required>
                        </div>
                    </div>
                </div>
            `;
            container.append(newActivity);
        });
    });

</script>


@endsection

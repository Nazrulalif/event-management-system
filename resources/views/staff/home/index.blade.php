@extends('layouts.user_type.auth')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- /.content-header -->
    @include('layouts.content-header')
    <!-- /.content-header -->
    @include('staff.home.modal-detail')
    @include('staff.event.modal-add')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <div class="pb-3">
                        <button class="btn btn-block btn-primary" data-toggle="modal" data-target="#modalAdd">Add New Event</button>
                    </div>
                    <div class="sticky-top mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Upcomming Events</h4>
                            </div>
                            <div class="card-body">
                                <div class="timeline timeline-inverse">
                                    <!-- timeline time label -->
                                    @foreach ($upcomingEvents as $event)
                                    <div class="time-label">
                                        <span class="">
                                            {{ \Carbon\Carbon::parse($event->start_date)->format('j F. Y') }}
                                        </span>
                                    </div>
                                    <div>
                                        @if ($event->event_title)
                                        <i class="far fa-calendar-alt bg-blue"></i>
                                        @else
                                        <i class="fas fa-handshake bg-warning"></i>
                                        @endif
                                        <div class="timeline-item">
                                            <div class="px-2 py-2">
                                                <span>{{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}</span>
                                                <a href="#" class="event-detail-btn" data-toggle="modal"
                                                    data-target="#eventModal" data-title="{{ $event->event_title }}"
                                                    data-start="{{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}"
                                                    data-end="{{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}"
                                                    data-date="{{ \Carbon\Carbon::parse($event->start_date)->format('l, d/m/Y') }}"
                                                    data-date-end="{{ \Carbon\Carbon::parse($event->end_date)->format('l, d/m/Y') }}"
                                                    data-description="{{ $event->objective }}"
                                                    data-createdby="{{ $event->creator_name }}"
                                                    data-id="{{ $event->id }}"
                                                    data-platform="{{ $event->platform }}">

                                                    {{$event->event_title}}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach

                                    <!-- END timeline item -->
                                    <div>
                                        <i class="far fa-clock bg-gray"></i>
                                    </div>
                                </div>

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card card-primary">
                        <div class="card-body p-0">
                            <!-- THE CALENDAR -->
                            <div id="calendar"></div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
    </section>
    <!-- /.content -->
</div>
<script src="{{asset('plugins/jquery/jquery.min.js')}} "></script>
<script>

    //modal upcomming
    $(document).ready(function () {
        // When an event in the timeline is clicked
        $('.event-detail-btn').on('click', function () {
            // Get event data from data-* attributes
            var eventTitle = $(this).data('title');
            var eventStart = $(this).data('start');
            var eventEnd = $(this).data('end');
            var eventDate = $(this).data('date');
            var eventDateEnd = $(this).data('date-end');
            var eventDescription = $(this).data('description');
            var createdBy = $(this).data('createdby');
            var platform = $(this).data('platform');
            var id = $(this).data('id');

            // Set data in the modal
            $('#eventTitle').text(eventTitle);
            $('#eventStartTime').text(eventStart);
            $('#eventEndTime').text(eventEnd);
            $('#eventStartDate').text(eventDate);
            $('#eventEndDate').text(eventDate);
            $('#eventDescription').text(eventDescription);
            $('#createdBy').text(createdBy);
            $('#platform').text(platform);

            $('#deleteEventBtn').data('event-id', id);
            var eventId = id;
            var detailUrl = '/view-event/' + eventId; // Adjust with your route structure
            $('#eventModal').find('#viewDetail').attr('href', detailUrl);
        });
    });


    $(document).ready(function () {
        // Initialize FullCalendar
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            themeSystem: 'bootstrap',
            editable: false, // Disable event editing
            droppable: false, // Disable event dragging
            selectable: true, // Disable date selection
            events: function (info, successCallback, failureCallback) {
                // Make an AJAX request to fetch events from the server
                $.ajax({
                    url: '/events', // URL of your route
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        successCallback(data); // Pass the events data to FullCalendar
                    },
                    error: function (xhr, status, error) {
                        failureCallback(error); // Handle any errors
                    }
                });
            },
            eventClick: function (info) {
                // Trigger the modal to show event details
                var event = info.event;

                // Set the event details in the modal
                $('#eventTitle').text(event.title);
                $('#eventStart').text(event.start.toLocaleString()); // Format start date/time
                $('#eventEnd').text(event.end ? event.end.toLocaleString() :
                    'N/A'); // Format end date/time
                $('#eventDescription').text(event.extendedProps.description ||
                    'No objective available.');
                $('#eventStartTime').text(event.extendedProps.start_time || '');
                $('#eventEndTime').text(event.extendedProps.end_time || '');
                $('#eventStartDate').text(event.extendedProps.start_date || '');
                $('#eventEndDate').text(event.extendedProps.end_date || '');
                $('#platform').text(event.extendedProps.platform || '');
                $('#createdBy').text(event.extendedProps.creator_name || '');
                $('#deleteEventBtn').data('event-id', event.id);
                var eventId = info.event.id;
                var detailUrl = '/view-event/' +eventId; // Adjust with your route structure

                $('#eventModal').find('#viewDetail').attr('href', detailUrl);

                $('#eventModal').modal('show');
            }
        });

        // Render the calendar
        calendar.render();
    });

</script>

@endsection
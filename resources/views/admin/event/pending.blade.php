@extends('admin.event.index')

@section('event-list')

<table id="example" class="table table-bordered w-full">
    <thead>
        <tr>
            <th width='30%'>Event Title</th>
            <th>Platform</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th width='20%'>Created By</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>

<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}} "></script>


<script>
    $(document).ready(function () {
        const table = $('#example').DataTable({
            processing: false,
            serverSide: true,
            searching: true,
            ordering: true,
            order: [
                [0, 'desc']
            ], // Order by the first column (index 0) in descending order
            autoWidth: false,
            responsive: true,
            ajax: '/admin/event-pending',
            columns: [
                { data: 'event_title' },
                { data: 'platform' },
                { data: 'start_date' },
                { data: 'end_date' },
                { data: 'creator_name' },
                { data: 'created_at' },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                            <div>
                                <a href='/admin/view-event/${row.id}' title="View" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button title="approve" class="btn btn-success btn-sm approve" data-id="${row.id}">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button title="Delete" class="btn btn-danger btn-sm reject" data-id="${row.id}">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>`;
                    },
                    orderable: false,
                    searchable: false
                }
            ],
        });

        $('#example').on('click', '.approve', function () {
            const id = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "This event will be approved!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, approve it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/event-management/approve/${id}`,
                        type: 'post',
                        data: {
                            _token: "{{ csrf_token() }}" // Ensure CSRF token is included
                        },
                        success: function (response) {
                            Swal.fire(
                                'Approved!',
                                'The event has been rejeceted.',
                                'success'
                            );
                            table.ajax.reload(); // Reload the DataTable to reflect changes
                        },
                        error: function (xhr) {
                            Swal.fire(
                                'Error!',
                                'An error occurred while approved the event.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        // SweetAlert and AJAX Deactivation
        $('#example').on('click', '.reject', function () {
            const id = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "This event will be rejected!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, reject it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/event-management/reject/${id}`,
                        type: 'post',
                        data: {
                            _token: "{{ csrf_token() }}" // Ensure CSRF token is included
                        },
                        success: function (response) {
                            Swal.fire(
                                'Rejected!',
                                'The event has been rejeceted.',
                                'success'
                            );
                            table.ajax.reload(); // Reload the DataTable to reflect changes
                        },
                        error: function (xhr) {
                            Swal.fire(
                                'Error!',
                                'An error occurred while rejected the event.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>

@endsection
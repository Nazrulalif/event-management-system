@extends('staff.event.index')

@section('event-list')

<table id="example" class="table table-bordered w-full">
    <thead>
        <tr>
            <th width='30%'>Event Title</th>
            <th>Platform</th>
            <th>Start Date</th>
            <th>End Date</th>
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
            ajax: '/event-draft',
            columns: [
                { data: 'event_title' },
                { data: 'platform' },
                { data: 'start_date' },
                { data: 'end_date' },
                { data: 'created_at' },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                            <div>
                                <a href='/event-progress-main/${row.id}' title="Edit" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button title="Delete" class="btn btn-danger btn-sm delete" data-id="${row.id}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>`;
                    },
                    orderable: false,
                    searchable: false
                }
            ],
        });

        // SweetAlert and AJAX Deactivation
        $('#example').on('click', '.delete', function () {
            const id = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "This event will be deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/event-management/delete/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}" // Ensure CSRF token is included
                        },
                        success: function (response) {
                            Swal.fire(
                                'Deleted!',
                                'The event has been deleted.',
                                'success'
                            );
                            table.ajax.reload(); // Reload the DataTable to reflect changes
                        },
                        error: function (xhr) {
                            Swal.fire(
                                'Error!',
                                'An error occurred while deleted the event.',
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
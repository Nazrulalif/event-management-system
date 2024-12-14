@extends('staff.event.index')

@section('event-list')

@include('staff.event.edit-event')

<table id="example" class="table table-bordered w-full">
    <thead>
        <tr>
            <th width='30%'>Event Title</th>
            <th>Platform</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th width='20%'>Created By</th>
            <th>Status</th>
            <th>Created At</th>
            <th width='15%'>Actions</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>

<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}} "></script>


<script>
    $(document).ready(function () {
        const userId = "{{ Auth::user()->id }}";

        const table = $('#example').DataTable({
            processing: false,
            serverSide: true,
            searching: true,
            ordering: true,
            autoWidth: false,
            responsive: true,
            ajax: '/event-management',
            columns: [{
                    data: 'event_title'
                },
                {
                    data: 'platform'
                },
                {
                    data: 'start_date'
                },
                {
                    data: 'end_date'
                },
                {
                    data: 'creator_name'
                },
                {
                    data: 'status',
                    render: function (data, type, row) {
                        if (data == 'Approve') {
                            return `<div class='badge badge-success'> ${data} </div>`;
                        } else if (data == 'Reject') {
                            return `<div class='badge badge-danger'> ${data} </div>`;
                        } else if (data == 'Cancelled') {
                            return `<div class='badge badge-warning'> ${data} </div>`;
                        } else if (data == 'Pending') {
                            return `<div class='badge badge-info'> ${data} </div>`;
                        } else {
                            return `<div class='badge badge-secondary'> Unknown status </div>`;
                        }
                    },
                },
                {
                    data: 'created_at'
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        // Initialize buttons
                        let viewButton = `
                            <a href='/view-event/${row.id}' title="View" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>`;

                        let editButton = '';
                        let deleteButton = `
                            <button title="Delete" class="btn btn-danger btn-sm delete" data-id="${row.id}">
                                <i class="fas fa-trash-alt"></i>
                            </button>`;

                       
                    // Get today's date in YYYY-MM-DD format
                    const today = new Date().toISOString().split('T')[0];
                    
                    // Show edit button only if status is 'Approve' and today is before or equal to start_date
                    if (row.status === 'Approve' && today >= row.start_date) {
                        editButton = `
                        <button title="Edit" 
                            class="btn btn-info btn-sm edit" 
                            data-id="${row.id}"
                            data-toggle="modal" 
                            data-target="#modalEditEvent">
                            <i class="fas fa-edit"></i>
                        </button>`;

                    }else if(row.status === 'Reject' && today >= row.start_date && row.created_by === userId){
                        editButton = `
                        <a href='/event-progress-main/${row.id}' title="Edit" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i>
                        </a>`;
                    }else{
                        editButton = `
                        <button title="Edit" 
                            class="btn btn-info btn-sm edit" 
                            data-id="${row.id}"
                            data-toggle="modal" 
                            data-target="#modalEditEvent" disabled>
                            <i class="fas fa-edit"></i>
                        </button>`;
                    }

                        // Combine and return buttons
                        return `<div>${viewButton} ${editButton} ${deleteButton}</div>`;
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
                            table.ajax
                                .reload(); // Reload the DataTable to reflect changes
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

        $(document).on('click', '.edit', function () {
            const id = $(this).data('id');

            // Fetch group data via AJAX
            $.ajax({
                url: `/event-management/show/${id}`, // Create a route to fetch group data
                type: 'GET',
                success: function (response) {
                    // Populate modal fields with fetched data
                    $('#modalEditEvent #title').val(response.event_title);
                    $('#modalEditEvent #startDate').val(response.start_date);
                    $('#modalEditEvent #endDate').val(response.end_date);
                    $('#modalEditEvent #start_time').val(response.start_time);
                    $('#modalEditEvent #end_time').val(response.end_time);
                    $('#modalEditEvent #id').val(id);
                },
                error: function () {
                    alert('Failed to fetch group data.');
                }
            });
        });
    });

</script>

@endsection

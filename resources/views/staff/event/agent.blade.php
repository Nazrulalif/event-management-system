@extends('staff.event.progress')

@section('progess-content')

<div class="container">
    <div class="d-flex justify-content-end pb-3">
        <div id="progressContainer">
            <div id="progressBtn">Checking...</div>
        </div>
    </div>
    @include('staff.event.add-agent-group')
    @include('staff.event.edit-agent-group')
    <div class="float-right pb-4">
        <button type="button" data-toggle="modal" data-target="#modalAddAgent" class="btn btn-primary btn-sm ">
            <i class="fas fa-plus-circle"></i>
            Add New
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered " id="example">
            <thead>
                <td>Nazir</td>
                <td>Members</td>
                <td width='10%'>Created At</td>
                <td width='10%'>action</td>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
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
                url: `/events/${eventId}/check-progress-agent`,
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
        
        // Initialize Select2 for any existing select elements on page load
        $('.select2').select2();

        // Initialize Select2 for select elements inside the Edit modal when it's shown
        $(document).on('shown.bs.modal', '#modalEditAgent', function () {
            $('#modalEditAgent .select2').select2();
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });
        });

        // Initialize Select2 for select elements inside the Add modal when it's shown
        $(document).on('shown.bs.modal', '#modalAddAgent', function () {
            $('#modalAddAgent .select2').select2();
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });
        });
        const eventId = @json($data->id); // Safely include the event ID

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
            ajax: `/event-progress-agent-grouping/${eventId}`,
            columns: [
                {
                    data: 'nazir',
                    name: 'nazir'
                },
                {
                    data: 'members',
                    name: 'members',
                    orderable: false,
                    searchable: false
                }, // New members column
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                            <div>
                                  <button 
                                        title="Edit" 
                                        class="btn btn-info btn-sm edit" 
                                        data-id="${row.id}" 
                                        data-toggle="modal" 
                                        data-target="#modalEditAgent">
                                        <i class="fas fa-edit"></i>
                                    </button>
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
                text: "This group will be deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/event-progress-agent-grouping-delete/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}" // Ensure CSRF token is included
                        },
                        success: function (response) {
                            Swal.fire(
                                'Deleted!',
                                'The group has been deleted.',
                                'success'
                            );
                            table.ajax
                        .reload(); // Reload the DataTable to reflect changes
                        },
                        error: function (xhr) {
                            Swal.fire(
                                'Error!',
                                'An error occurred while deleted the group.',
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
                url: `/event-progress-agent-grouping-show/${id}`, // Create a route to fetch group data
                type: 'GET',
                success: function (response) {

                    console.log(response);
                    
                    // Populate modal fields with fetched data
                    $('#modalEditAgent #id').val(id);
                    $('#modalEditAgent #nazir').val(response.nazir).trigger('change');
                    $('#modalEditAgent #member').val(response.members).trigger('change');
                },
                error: function () {
                    alert('Failed to fetch group data.');
                }
            });
        });


    });

</script>
@endsection
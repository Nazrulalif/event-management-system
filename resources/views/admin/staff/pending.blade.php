@extends('admin.staff.index')

@section('list-content')
<table id="pending" class="table table-bordered w-full">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Gender</th>
            <th>Created At</th>
            <th width="18%">Actions</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>
<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}} "></script>

<script>
    $(document).ready(function () {
        const table = $('#pending').DataTable({
            processing: false,
            serverSide: true,
            searching: true,
            ordering: true,
            order: [
                [0, 'desc']
            ], // Order by the first column (index 0) in descending order
            autoWidth: false,
            responsive: true,
            ajax: '/admin/pending-request',
            columns: [
                { data: 'name' },
                { data: 'email' },
                { data: 'gender' },
                { data: 'created_at' },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                            <div>
                                <a href="/admin/user-detail/${row.id}" title="View" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button title="Accept" class="btn btn-success btn-sm accept" data-id="${row.id}">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button title="Reject" class="btn btn-danger btn-sm reject" data-id="${row.id}">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>`;
                    },
                    orderable: false,
                    searchable: false
                }
            ],
        });

        // Accept action
        $('#pending').on('click', '.accept', function () {
            const userId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You are approving this user. This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, approve it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/pending-request/accept/${userId}`,
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function () {
                            Swal.fire(
                                'Approved!',
                                'The user has been approved successfully.',
                                'success'
                            );
                            table.ajax.reload(); // Reload the DataTable
                        },
                        error: function () {
                            Swal.fire(
                                'Error!',
                                'There was a problem approving the user.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        // Reject action
        $('#pending').on('click', '.reject', function () {
            const userId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "This will delete the user request!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/pending-request/reject/${userId}`,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function () {
                            Swal.fire(
                                'Deleted!',
                                'The user request has been deleted.',
                                'success'
                            );
                            table.ajax.reload(); // Reload the DataTable
                        },
                        error: function () {
                            Swal.fire(
                                'Error!',
                                'There was a problem deleting the user request.',
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
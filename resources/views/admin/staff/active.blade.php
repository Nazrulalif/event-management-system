@extends('admin.staff.index')

@section('list-content')
<table id="example" class="table table-bordered w-full">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Gender</th>
            <th>Access</th>
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
            ajax: '/admin/user-management',
            columns: [
                { data: 'name' },
                { data: 'email' },
                { data: 'gender' },
                {
                    data: 'role_guid',
                    render: function (data, type, row) {
                        if (data === 1) {
                            return `<div class='badge badge-success'> Administrator </div>`;
                        } else if (data === 2) {
                            return `<div class='badge badge-primary'> Staff </div>`;
                        } else {
                            return `<div class='badge badge-secondary'> Unknown Role </div>`;
                        }
                    },
                },
                { data: 'created_at' },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                            <div>
                                <a href="/admin/user-detail/${row.id}" type="button" title="View" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button title="Deactivate" class="btn btn-danger btn-sm deactivate" data-id="${row.id}">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>`;
                    },
                    orderable: false,
                    searchable: false
                }
            ],
        });

        // SweetAlert and AJAX Deactivation
        $('#example').on('click', '.deactivate', function () {
            const userId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "This user will be deactivated!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, deactive it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/user-management/deactivate/${userId}`,
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}" // Ensure CSRF token is included
                        },
                        success: function (response) {
                            Swal.fire(
                                'Deactivated!',
                                'The user has been deactivated.',
                                'success'
                            );
                            table.ajax.reload(); // Reload the DataTable to reflect changes
                        },
                        error: function (xhr) {
                            Swal.fire(
                                'Error!',
                                'An error occurred while deactivating the user.',
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

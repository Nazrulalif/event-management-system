@extends('layouts.user_type.auth')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- /.content-header -->
    @include('layouts.content-header')
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @include('admin.agent.add')
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Agent List</div>
                    <button type="button" data-toggle="modal" data-target="#modalAdd" class="btn btn-primary btn-sm float-right">
                        <i class="fas fa-plus-circle"></i>
                        Add New
                    </button>
                </div>
                <div class="card-body">
                    <table id="example" class="table table-bordered w-full">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Channel</th>
                                <th>Phone Number</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                    
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
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
            ajax: '/admin/agent-management',
            columns: [
                { data: 'name' },
                { data: 'email' },
                { data: 'channel' },
                { data: 'phone_number' },
                { data: 'created_at' },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                            <div>
                                <a type="button" title="View" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i>
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
            const userId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "This user will be deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/agent-management/delete/${userId}`,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}" // Ensure CSRF token is included
                        },
                        success: function (response) {
                            Swal.fire(
                                'Deleted!',
                                'The user has been deleted.',
                                'success'
                            );
                            table.ajax.reload(); // Reload the DataTable to reflect changes
                        },
                        error: function (xhr) {
                            Swal.fire(
                                'Error!',
                                'An error occurred while deleted the user.',
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

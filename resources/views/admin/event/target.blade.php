@extends('admin.event.progress')

@section('progess-content')
<div class="d-flex justify-content-end">
    <div id="progressContainer">
        <div id="progressBtn">Checking...</div>
    </div>
</div>

<form id="productForm" method="POST" action="{{ route('event.target.update', $data['id']) }}">
    @csrf
    <div class="container pt-4">
        <div class="table-responsive">
            <table class="table w-100" id="productTable">
                <thead class="thead-light text-center">
                    <th>No</th>
                    <th width='20%'>Product</th>
                    <th>ARPU</th>
                    <th>Outbase</th>
                    <th>Inbase</th>
                    <th>Sales Physical Target</th>
                    <th>Annualized Revenue (RM)</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @forelse ($targets as $index => $target)
                    <tr class="product-row">
                        <td class="row-number">{{ $index + 1 }}</td>
                        <td>
                            <select name="products[{{ $index }}][product]" class="form-control product-select">
                                <option value="">Select Product</option>
                                @foreach ($products as $product)
                                <option value="{{ $product }}" {{ $product == $target->product ? 'selected' : '' }}>
                                    {{ $product }}
                                </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="products[{{ $index }}][arpu]" class="form-control arpu-input"
                                step="0.01" value="{{ $target->arpu }}">
                        </td>
                        <td>
                            <input type="number" name="products[{{ $index }}][outbase]" class="form-control outbase"
                                value="{{ $target->outbase }}">
                        </td>
                        <td>
                            <input type="number" name="products[{{ $index }}][inbase]" class="form-control inbase"
                                value="{{ $target->inbase }}">
                        </td>
                        <td>
                            <input type="number" name="products[{{ $index }}][sales_physical_target]"
                                class="form-control" readonly value="{{ $target->sales_physical_target }}">
                        </td>
                        <td>
                            <input type="number" name="products[{{ $index }}][revenue]" class="form-control"
                                value="{{ $target->revenue }}">
                        </td>
                        <td>
                            <input type="hidden" name="products[{{ $index }}][id]" value="{{ $target->id }}">
                            <button type="button" class="btn btn-danger btn-sm delete-row">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr class="product-row">
                        <td class="row-number">1</td>
                        <td>
                            <select name="products[0][product]" class="form-control product-select">
                                <option value="">Select Product</option>
                                @foreach ($products as $product)
                                <option value="{{ $product }}">{{ $product }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" name="products[0][arpu]" class="form-control arpu-input" step="0.01">
                        </td>
                        <td><input type="number" name="products[0][outbase]" class="form-control outbase"></td>
                        <td><input type="number" name="products[0][inbase]" class="form-control inbase"></td>
                        <td><input type="number" name="products[0][sales_physical_target]" class="form-control"
                                readonly></td>
                        <td><input type="number" name="products[0][revenue]" class="form-control"></td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm delete-row" style="display:none">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <button type="button" class="btn btn-primary btn-sm" id="addRow">Add Row</button>
        </div>
        <div class="form-group">
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</form>

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
                url: `/admin/events/${eventId}/check-progress-target`,
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

        let rowCount = 1;

        // Add new row
        $('#addRow').click(function () {
            rowCount++;
            const newRow = $('.product-row').first().clone();

            // Clear inputs
            newRow.find('input').val('');
            newRow.find('select').val('');

            // Update names and IDs
            newRow.find('select, input').each(function () {
                const name = $(this).attr('name');
                if (name) {
                    $(this).attr('name', name.replace('[0]', `[${rowCount-1}]`));
                }
            });

            // Update row number
            newRow.find('.row-number').text(rowCount);

            // Show delete button
            newRow.find('.delete-row').show();

            $('#productTable tbody').append(newRow);
        });

        $(document).on('click', '.delete-row', function () {
            const row = $(this).closest('tr');
            const recordId = row.find('input[name*="[id]"]').val();
            // Check if only one row exists
            if ($('#productTable tbody tr').length === 1) {
                    Swal.fire(
                        'Error!',
                        'At least one row must remain in the table.',
                        'warning'
                    );
                    return; // Exit if only one row exists
                }

            // Show SweetAlert confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    if (recordId) {
                        // If the row exists in the database, send a delete request
                        $.ajax({
                            url: `/admin/event-progress-target-delete/${recordId}`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content') // CSRF token for Laravel
                            },
                            success: function (response) {
                                if (response.success) {
                                    row.remove();
                                    updateRowNumbers();
                                    Swal.fire(
                                        'Deleted!',
                                        'The record has been deleted.',
                                        'success'
                                    );
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        response.message ||
                                        'Unable to delete the record.',
                                        'error'
                                    );
                                }
                            },
                            error: function (xhr) {
                                Swal.fire(
                                    'Error!',
                                    'An error occurred while trying to delete the record.',
                                    'error'
                                );
                            }
                        });
                    } else {
                        // If the row doesn't exist in the database, simply remove it
                        row.remove();
                        updateRowNumbers();
                        Swal.fire(
                            'Deleted!',
                            'The row has been removed.',
                            'success'
                        );
                    }
                }
            });
        });

        // Update row numbers after deletion
        function updateRowNumbers() {
            $('.row-number').each(function (index) {
                $(this).text(index + 1);
            });
        }


        // Update row numbers
        function updateRowNumbers() {
            $('.row-number').each(function (index) {
                $(this).text(index + 1);
            });
        }

        // Calculate Sales Physical Target
        $(document).on('input', '.outbase, .inbase', function () {
            const row = $(this).closest('tr');
            const outbase = parseFloat(row.find('input[name*="[outbase]"]').val()) || 0;
            const inbase = parseFloat(row.find('input[name*="[inbase]"]').val()) || 0;

            // Update Sales Physical Target field
            row.find('input[name*="[sales_physical_target]"]').val(outbase + inbase);
        });

    });

</script>
@endsection

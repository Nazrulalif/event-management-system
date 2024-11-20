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
                            <button type="button" class="btn btn-danger btn-sm delete-row" {!! $index===0
                                ? 'style="display:none"' : '' !!}>
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
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <input type="text" name="total_outbase" id="total_outbase" class="form-control" readonly
                                value="{{ $targets->sum('outbase') }}">
                        </td>
                        <td>
                            <input type="text" name="total_inbase" id="total_inbase" class="form-control" readonly
                                value="{{ $targets->sum('inbase') }}">
                        </td>
                        <td>
                            <input type="text" name="total_target" id="total_target" class="form-control" readonly
                                value="{{ $targets->sum('sales_physical_target') }}">
                        </td>
                        <td>
                            <input type="text" name="total_revenue" id="total_revenue" class="form-control" readonly
                                value="{{ $targets->sum('revenue') }}">
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
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
        const eventId = @json($data->id);
        const deletedIds = []; // Array to store IDs of deleted rows

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

        let rowCount = $('.product-row').length;

        function calculateTotals() {
            let totalOutbase = 0;
            let totalInbase = 0;
            let totalTarget = 0;
            let totalRevenue = 0;

            $('.product-row').each(function () {
                // Calculate row totals
                const outbase = parseFloat($(this).find('input[name*="[outbase]"]').val()) || 0;
                const inbase = parseFloat($(this).find('input[name*="[inbase]"]').val()) || 0;
                const revenue = parseFloat($(this).find('input[name*="[revenue]"]').val()) || 0;
                const target = outbase + inbase;

                // Update the sales physical target for this row
                $(this).find('input[name*="[sales_physical_target]"]').val(target);

                // Add to totals
                totalOutbase += outbase;
                totalInbase += inbase;
                totalTarget += target;
                totalRevenue += revenue;
            });

            // Update footer fields with formatted numbers
            $('#total_outbase').val(totalOutbase.toFixed(2));
            $('#total_inbase').val(totalInbase.toFixed(2));
            $('#total_target').val(totalTarget.toFixed(2));
            $('#total_revenue').val(totalRevenue.toFixed(2));
        }

        // Recalculate on any input change
        $(document).on('input', '.product-row input', function () {
            calculateTotals();
        });


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
                    $(this).attr('name', name.replace(/\[\d+\]/, `[${rowCount-1}]`));
                }
            });

            // Update row number
            newRow.find('.row-number').text(rowCount);

            // Show delete button
            newRow.find('.delete-row').show();

            // Remove any existing hidden id field
            newRow.find('input[name*="[id]"]').remove();

            $('#productTable tbody').append(newRow);
        });

        // Delete row
        $(document).on('click', '.delete-row', function () {
            if ($('.product-row').length > 1) {
                const row = $(this).closest('tr');
                const idInput = row.find('input[name*="[id]"]');

                // If row has an ID, add it to deletedIds array
                if (idInput.length && idInput.val()) {
                    deletedIds.push(idInput.val());
                }

                row.remove();
                updateRowNumbers();
                calculateTotals();

            }
        });

        // Update row numbers and input names after deletion
        function updateRowNumbers() {
            $('.product-row').each(function (index) {
                $(this).find('.row-number').text(index + 1);

                // Update all input and select names to maintain sequential indexes
                $(this).find('select, input').each(function () {
                    const name = $(this).attr('name');
                    if (name) {
                        $(this).attr('name', name.replace(/\[\d+\]/, `[${index}]`));
                    }
                });
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

        // Add hidden input for deleted IDs before form submission
        $('#productForm').on('submit', function () {
            if (deletedIds.length > 0) {
                deletedIds.forEach(id => {
                    $(this).append(`<input type="hidden" name="deleted_ids[]" value="${id}">`);
                });
            }
        });
    });

</script>
@endsection

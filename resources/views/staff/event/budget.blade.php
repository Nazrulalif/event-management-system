@extends('staff.event.progress')

@section('progess-content')
<div class="d-flex justify-content-end">
    <div id="progressContainer">
        <div id="progressBtn">Checking...</div>
    </div>
</div>

<form action="{{ route('event.budget.update.user', $data->id) }}" method="POST" enctype="multipart/form-data"
    autocomplete="off">
    @csrf
    <div class="container pt-4">
        <div class="table-responsive">
            <table class="table w-100" id="productTable">
                <thead class="thead-light text-center">
                    <th>No</th>
                    <th width='20%'>Items</th>
                    <th>Day(s)</th>
                    <th>Frequency/Pax</th>
                    <th>Price/UNIT (RM)</th>
                    <th>Total Budget Required</th>
                    <th>Remark</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @if(isset($budget->budget_items) && count($budget->budget_items) > 0)
                        @foreach($budget->budget_items as $index => $item)
                        <tr class="budget-row">
                            <td class="row-number">{{ $index + 1 }}</td>
                            <td>
                                <textarea name="items[{{ $index }}][description]" class="form-control item-description" cols="30"
                                    rows="1" required>{{ $item->description }}</textarea>
                            </td>
                            <td>
                                <input type="number" name="items[{{ $index }}][days]" class="form-control days" min="0"
                                    value="{{ $item->days }}" required>
                            </td>
                            <td>
                                <input type="number" name="items[{{ $index }}][frequency]" class="form-control frequency" min="0"
                                    value="{{ $item->frequency }}" required>
                            </td>
                            <td>
                                <input type="number" name="items[{{ $index }}][price_per_unit]" class="form-control price" step="0.01"
                                    min="0" value="{{ $item->price_per_unit }}" required>
                            </td>
                            <td>
                                <input type="number" name="items[{{ $index }}][total_budget]" class="form-control total-budget"
                                    step="0.01" value="{{ $item->total_budget }}" readonly>
                            </td>
                            <td>
                                <textarea name="items[{{ $index }}][remark]" cols="30" rows="1" class="form-control"
                                    >{{ $item->remark }}</textarea>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm delete-row" {!! $index === 0 ? 'style="display:none"' : '' !!}>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr class="budget-row">
                            <td class="row-number">1</td>
                            <td>
                                <textarea name="items[0][description]" class="form-control item-description" cols="30"
                                    rows="1" required></textarea>
                            </td>
                            <td>
                                <input type="number" name="items[0][days]" class="form-control days" min="0" required>
                            </td>
                            <td>
                                <input type="number" name="items[0][frequency]" class="form-control frequency" min="0"
                                    required>
                            </td>
                            <td>
                                <input type="number" name="items[0][price_per_unit]" class="form-control price" step="0.01"
                                    min="0" required>
                            </td>
                            <td>
                                <input type="number" name="items[0][total_budget]" class="form-control total-budget"
                                    step="0.01" readonly>
                            </td>
                            <td>
                                <textarea name="items[0][remark]" cols="30" rows="1" class="form-control"
                                    ></textarea>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm delete-row" style="display:none">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr class="bg-light">
                        <td></td>
                        <td>Total</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><input type="text" name="total" class="form-control" value="{{ $budget->total ?? '' }}" readonly></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Agency Service Fee State (Local Vendor)</td>
                        <td></td>
                        <td></td>
                        <td>
                            <div class="input-group mb-3">
                                <input type="text" name="fee_percent" class="form-control" value="{{ $budget->fee_percent ?? '' }}" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </td>
                        <td><input type="text" name="fee" class="form-control" value="{{ $budget->fee ?? '' }}" readonly></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Service Tax (6%)</td>
                        <td></td>
                        <td></td>
                        <td>
                            <div class="input-group mb-3">
                                <input type="text" name="tax_percent" class="form-control" value="{{ $budget->tax_percent ?? '' }}" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </td>
                        <td><input type="text" name="tax" class="form-control" value="{{ $budget->tax ?? '' }}" readonly></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="bg-light">
                        <td></td>
                        <td>Grand Total Budget</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><input type="text" name="grand_total" class="form-control" value="{{ $budget->grand_total ?? '' }}" readonly></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

            <button type="button" class="btn btn-primary btn-sm" id="addRow">Add Row</button>

        </div>
        <div class="form-group mt-3">
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
                url: `/events/${eventId}/check-progress-budget`,
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
        let rowCount = $('.budget-row').length;

        // Add new row
        $('#addRow').click(function () {
            rowCount++;
            const newRow = $('.budget-row').first().clone();

            // Clear inputs
            newRow.find('input, textarea').val('');

            // Update names and attributes
            newRow.find('input, textarea').each(function () {
                const name = $(this).attr('name');
                if (name) {
                    $(this).attr('name', name.replace(/\[\d+\]/, `[${rowCount - 1}]`));
                }
            });

            // Update row number
            newRow.find('.row-number').text(rowCount);

            // Show delete button
            newRow.find('.delete-row').show();

            $('#productTable tbody').append(newRow);
        });

        // Delete row
        $(document).on('click', '.delete-row', function () {
            if ($('.budget-row').length > 1) {
                $(this).closest('tr').remove();
                updateRowNumbers();
                calculateTotals();
            }
        });

        // Update row numbers
        function updateRowNumbers() {
            $('.row-number').each(function (index) {
                $(this).text(index + 1);
            });

            // Update input names to maintain sequential indexes
            $('.budget-row').each(function (index) {
                $(this).find('input, textarea').each(function () {
                    const name = $(this).attr('name');
                    if (name) {
                        $(this).attr('name', name.replace(/\[\d+\]/, `[${index}]`));
                    }
                });
            });
        }

        // Calculate total budget for a row
        $(document).on('input', '.days, .frequency, .price', function () {
            const row = $(this).closest('tr');
            const days = parseFloat(row.find('.days').val()) || 0;
            const frequency = parseFloat(row.find('.frequency').val()) || 0;
            const price = parseFloat(row.find('.price').val()) || 0;

            const totalBudget = days * frequency * price;
            row.find('.total-budget').val(totalBudget.toFixed(2));
            calculateTotals();
        });

        // Calculate totals, fees, taxes, and grand total
        function calculateTotals() {
            let total = 0;

            // Sum all total budget values
            $('.total-budget').each(function () {
                total += parseFloat($(this).val()) || 0;
            });

            $('input[name="total"]').val(total.toFixed(2));

            // Calculate agency fee
            const feePercent = parseFloat($('input[name="fee_percent"]').val()) || 0;
            const fee = (total * feePercent) / 100;
            $('input[name="fee"]').val(fee.toFixed(2));

            // Calculate tax
            const taxPercent = parseFloat($('input[name="tax_percent"]').val()) || 0;
            const tax = ((total + fee) * taxPercent) / 100;
            $('input[name="tax"]').val(tax.toFixed(2));

            // Calculate grand total
            const grandTotal = total + fee + tax;
            $('input[name="grand_total"]').val(grandTotal.toFixed(2));
        }

        // Recalculate totals when agency fee or tax percent inputs change
        $(document).on('input', 'input[name="fee_percent"], input[name="tax_percent"]', function () {
            calculateTotals();
        });

        // Initial calculation in case of preloaded values
        calculateTotals();
    });
</script>

@endsection
@extends('admin.event.progress')

@section('progess-content')
<div class="d-flex justify-content-end">
    <div id="progressContainer">
        <div id="progressBtn">Checking...</div>
    </div>
</div>

<div class="container">
    <form action="{{ route('event.reward.update', $data->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
        @csrf
        
        <div class="row">
            <!-- Internal Prize Section -->
            <div class="col-md-6">
                <h5>Internal Category</h5>
                <div class="form-group">
                    <label for="internal_first_prize">First Prize</label>
                    <input type="number" name="prizes[internal][first]" id="internal_first_prize" class="form-control" 
                           placeholder="Amount" required 
                           value="{{ old('prizes.internal.first', json_decode($reward->prize, true)['internal']['first'] ?? '') }}">
                </div>

                <div class="form-group">
                    <label for="internal_second_prize">Second Prize</label>
                    <input type="number" name="prizes[internal][second]" id="internal_second_prize" class="form-control" 
                           placeholder="Amount" required 
                           value="{{ old('prizes.internal.second', json_decode($reward->prize, true)['internal']['second'] ?? '') }}">
                </div>

                <div class="form-group">
                    <label for="internal_third_prize">Third Prize</label>
                    <input type="number" name="prizes[internal][third]" id="internal_third_prize" class="form-control" 
                           placeholder="Amount" required 
                           value="{{ old('prizes.internal.third', json_decode($reward->prize, true)['internal']['third'] ?? '') }}">
                </div>

                <div class="form-group">
                    <label for="internal_condition">Conditions</label>
                    <textarea name="conditions[internal]" id="internal_condition" rows="4" class="form-control" 
                              placeholder="Terms and conditions to win the internal prize" required>{{ old('conditions.internal', json_decode($reward->condition, true)['internal'] ?? '') }}</textarea>
                </div>
            </div>

            <!-- External Prize Section -->
            <div class="col-md-6">
                <h5>External Category</h5>
                <div class="form-group">
                    <label for="external_first_prize">First Prize</label>
                    <input type="number" name="prizes[external][first]" id="external_first_prize" class="form-control" 
                           placeholder="Amount" required 
                           value="{{ old('prizes.external.first', json_decode($reward->prize, true)['external']['first'] ?? '') }}">
                </div>

                <div class="form-group">
                    <label for="external_second_prize">Second Prize</label>
                    <input type="number" name="prizes[external][second]" id="external_second_prize" class="form-control" 
                           placeholder="Amount" required 
                           value="{{ old('prizes.external.second', json_decode($reward->prize, true)['external']['second'] ?? '') }}">
                </div>

                <div class="form-group">
                    <label for="external_third_prize">Third Prize</label>
                    <input type="number" name="prizes[external][third]" id="external_third_prize" class="form-control" 
                           placeholder="Amount" required 
                           value="{{ old('prizes.external.third', json_decode($reward->prize, true)['external']['third'] ?? '') }}">
                </div>

                <div class="form-group">
                    <label for="external_condition">Conditions</label>
                    <textarea name="conditions[external]" id="external_condition" rows="4" class="form-control" 
                              placeholder="Terms and conditions to win the external prize" required>{{ old('conditions.external', json_decode($reward->condition, true)['external'] ?? '') }}</textarea>
                </div>
            </div>
            
        </div>
        <div class="form-group">
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </form>
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
                url: `/admin/events/${eventId}/check-progress-reward`,
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

@endsection
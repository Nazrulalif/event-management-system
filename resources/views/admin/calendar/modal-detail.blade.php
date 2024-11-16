<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Event Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h4 class="py-2" id="eventTitle" style="text-align: center"></h4>

                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td> <strong>Date</strong></td>
                                <td><span id="eventStartDate"></span> - <span id="eventEndDate"></td>
                            </tr>
                            <tr>
                                <td><strong>Time</strong> </td>
                                <td><span id="eventStartTime"></span> - <span id="eventEndTime"></td>
                            </tr>
                            <tr>
                                <td><strong>Created by</strong> </td>
                                <td><span id="createdBy"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Platform</strong></td>
                                <td><span id="platform"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Objectives</strong> </td>
                                <td><p id="eventDescription"></p></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="deleteEventBtn" class="btn btn-danger">Delete</button>
                <a class="btn btn-primary" id="viewDetail">View more details</a>
            </div>
        </div>
    </div>
</div>

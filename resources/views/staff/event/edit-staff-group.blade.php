 <!-- Modal -->
 <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <form action="{{ route('event.staff.editUpdate.user') }}" method="post" enctype="multipart/form-data"
                autocomplete="off">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Group Member</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body px-4">
                    <div class="form-group">
                        <label for="name" class="required">Group Name</label>
                        <input type="text" class="form-control" name="name" id="name" required>
                        <input type="hidden" class="form-control" name="id" id="id" readonly>
                    </div>
                    <div class="form-group">
                        <label for="email" class="required">Mentor</label>
                        <select name="mentor" id="mentor" class="form-control" required>
                            <option value="">Select Mentor</option>
                            @foreach ($mentor as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="vehicle" class="required">Vehicle</label>
                        <select name="vehicle" id="vehicle" class="form-control" required>
                            <option value="">Select Vehicle</option>
                            <option value="EXORA - BQT 1249">EXORA - BQT 1249</option>
                            <option value="EXORA - BQT 1253">EXORA - BQT 1253</option>
                            <option value="EXORA - BRC 7907">EXORA - BRC 7907</option>
                            <option value="ALZA - BPA 445">ALZA - BPA 4451</option>
                            <option value="TMOW">TMOW</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="leader" class="required">Leader</label>
                        <select name="leader" id="leader" class="form-control" required>
                            <option value="">Select Leader</option>
                            @foreach ($leader as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="member" class="required">Members</label>
                        <select name="member[]" id="member" class="form-control select2" multiple="multiple"
                            data-placeholder="Select a Member" style="width: 100%;" required>
                            @foreach ($member as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nextar">Members (nextar)</label>
                        <select name="nextar[]" id="nextar" class="form-control select2" multiple="multiple"
                            data-placeholder="Select a Member" style="width: 100%;">
                            @foreach ($nextar as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>

        </div>
    </div>
</div>
{{-- <script src="{{asset('plugins/jquery/jquery.min.js')}} "></script>

<script>
    $(document).ready(function () {
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    })

</script> --}}

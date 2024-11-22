 <!-- Modal -->
 <div class="modal fade" id="modalEditAgent" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <form action="{{ route('event.agent.editUpdate') }}" method="post" enctype="multipart/form-data"
                autocomplete="off">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Group Member</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body px-4">
                    <div class="form-group">
                        <label for="nazir" class="required">Nazir</label>
                        <input type="hidden" class="form-control" name="id" id="id" readonly>

                        <select name="nazir" id="nazir" class="form-control" required>
                            <option value="">Select Nazir</option>
                            @foreach ($nazir as $item)
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

 <!-- Modal -->
 <div class="modal fade" id="modalRegister" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog ">
         <div class="modal-content">
             <form action="{{ route('user.register') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                 @csrf

                 <div class="modal-header">
                     <h5 class="modal-title" id="exampleModalLabel">User Registration</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
                 </div>
                 <div class="modal-body px-4">
                     <div class="form-group">
                         <label for="name" class="required">Full Name</label>
                         <input type="text" class="form-control" name="name" id="name" required>
                     </div>
                     <div class="form-group">
                         <label for="email" class="required">Email</label>
                         <input type="email" class="form-control" name="email" id="email" required>
                     </div>
                     <div class="form-group">
                        <label for="team" class="required">Team</label>
                        <select name="team" id="team" class="form-control" required>
                            <option value="">Select Team</option>
                            <option value="Sales Planning (SP)">Sales Planning (SP)</option>
                            <option value="Sales Operation MKS (SO MKS)">Sales Operation MKS (SO MKS)</option>
                            <option value="Sales Operation MKU (SO MKU)">Sales Operation MKU (SO MKU)</option>
                            <option value="Channel Management (CM)">Channel Management (CM)</option>
                            <option value="Sales Management (SM)">Sales Management (SM)</option>
                            <option value="Coverage & Capacity (CNC)">Coverage & Capacity (CNC)</option>
                        </select>
                    </div>
                     <div class="form-group">
                         <div class="row">
                             <div class="col-md-6">
                                 <label for="gender" class="required">Gender</label>
                                 <select name="gender" id="gender" class="form-control" required>
                                     <option value="">Select Gender</option>
                                     <option value="Male">Male</option>
                                     <option value="Female">Female</option>
                                 </select>
                             </div>
                             <div class="col-md-6 pt-3 pt-md-0 pt-lg-0 pt-xl-0">
                                 <label for="access" class="required">Access</label>
                                 <select name="access" id="access" class="form-control" required>
                                     <option value="">Select Access</option>
                                     <option value="1">Administrator</option>
                                     <option value="2">Staff</option>
                                     <option value="3">Agent</option>
                                 </select>
                             </div>
                         </div>
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

<!-- Container Fluid -->
<div class="container-fluid">

   <div class="row">
      <div class="col-6">
         <form action="" method="post">
            <!-- Start Card -->
            <div class="card shadow-sm mb-4">
               <div class="card-header">
                  <h5 class="m-0 font-weight-bold text-primary col-5">Add User Role</h5>
               </div>
               <div class="card-body py-0 pt-3">
                  <div class="form-group mb-1 pt-0">
                     <label for="role">Role Name </label>
                     <input type="text" class="form-control" id="role" placeholder="Enter Role Name">
                     <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                  </div>
                  <div class="form-group">
                     <label for="exampleFormControlSelect1">Is Active</label>
                     <select class="form-control" id="exampleFormControlSelect1">
                        <option>true</option>
                        <option>false</option>
                     </select>
                  </div>
               </div>
               <div class="card-footer">
                  <button type="submit" class="btn btn-block btn-primary">Add</button>
               </div>
            </div>
            <!-- End Card -->
         </form>
      </div>
      <!-- End Col -->
   </div>

</div>
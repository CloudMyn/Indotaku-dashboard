<div class="container-fluid">

   <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Add New Key</h1>
      <a href="<?= base_url("api/key") ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-arrow-left fa-sm text-white-50"></i> Back</a>
   </div>

   <div class="row">
      <div class="col-md-8">
         <div class="card shadow mb-4">
            <div class="card-header">
               <h5 class="m-0 font-weight-bold text-primary">Comic Description</h5>
            </div>
            <div class="card-body">
               <div class="form-group my-1">
                  <label for="username">Username : </label>
                  <input type="text" class="form-control" name="username" id="username" placeholder="username" value="<?= set_value("username") ?>">
                  <?= form_error("username", '<p class="text-danger p-0 m-0">', '</p>') ?>
               </div>
               <div class="form-group my-1">
                  <label for="email">Email Address : </label>
                  <input type="text" class="form-control" name="email" id="email" placeholder="Email Addresses" value="<?= set_value("email") ?>">
                  <?= form_error("email", '<p class="text-danger p-0 m-0">', '</p>') ?>
               </div>
               <div class="form-group my-1">
                  <label for="ipaddress">Ip Address : </label>
                  <input type="text" class="form-control" name="ipaddress" id="ipaddress" placeholder="Ip Addresses" value="<?= set_value("ipaddress") ?>">
                  <?= form_error("ipaddress", '<p class="text-danger p-0 m-0">', '</p>') ?>
               </div>
            </div>
            <div class="card-footer">
               <button type="submit" class="btn btn-primary btn-block">Submit</button>
            </div>
         </div>
      </div>
      <!-- End 1st Colum  -->

      <div class="col-md-4">
         <div class="row no-gutters">

            <!-- Addition Option -->
            <div class="col-12">
               <div class="card shadow mb-3">
                  <div class="card-header">
                     <h5 class="m-0 font-weight-bold text-primary">Addition</h5>
                  </div>
                  <div class="card-body py-2">
                     <div class="form-group mb-1">
                        <label for="limit">Ignore Limit Access :</label>
                        <select class="form-control" id="limit">
                           <option value="1">Don't Limit</option>
                           <option value="0">Keep Limit</option>
                        </select>
                     </div>
                     <div class="form-group mb-1">
                        <label for="private-key">Is Private Key :</label>
                        <select class="form-control" id="private-key">
                           <option value="1">Private</option>
                           <option value="0">Public</option>
                        </select>
                     </div>
                  </div>
                  <div class="card-footer">
                  </div>
               </div>
            </div>

            <div class="col-12">
               <div class="card shadow mb-3">
                  <div class="card-header">
                     <h5 class="m-0 font-weight-bold text-primary">Allowed Webs URI</h5>
                  </div>
                  <div class="card-body py-2">
                     <div class="form-group my-0">
                        <input type="text" class="form-control" name="email" id="email" placeholder="http://... or https://..." value="<?= set_value("email") ?>">
                        <?= form_error("email", '<p class="text-danger p-0 m-0">', '</p>') ?>
                     </div>
                  </div>
                  <div class="card-footer">
                     <button type="button" class="btn btn-primary btn-block">Add URI</button>
                  </div>
               </div>
            </div>


         </div>
      </div>
      <!-- End 2nd Column -->

   </div>

</div>
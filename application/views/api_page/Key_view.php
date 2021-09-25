<div class="container-fluid">
   <div class="align-items-center justify-content-between mb-4 row">
      <h1 class="h3 mb-0 text-gray-5600">API Key</h1>
   </div>

   <div class="card">
      <div class="card-header">
         <h5 class="m-0 text-primary font-weight-bold">Api Keys</h5>
      </div>
      <div class="card-body py-1 pt-3">

         <div class="row mb-3">
            <div class="col-md-12">
               <?= $this->session->flashdata("message") ?>
            </div>
            <div class="col-md-1 text-center">
               <h3 class="btn btn-danger btn-block"><?= $totals_result ?></h3>
            </div>
            <div class="col-md-3">
               <a href="<?= base_url("api/addApiKey") ?>" class="btn btn-success btn-block"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Add New Key</a>
            </div>
            <div class="col-md-8">
               <form method="post">
                  <div class="input-group b">
                     <?php if (isset($keyword)) : ?>
                        <input type="text" class="form-control bg-light border-0 small" id="comic-search" placeholder="Search Keys" name="keyword" autocomplete="off" value="<?= $keyword?>">
                     <?php else : ?>
                        <input type="text" class="form-control bg-light border-0 small" id="comic-search" placeholder="Search Keys" name="keyword" autocomplete="off">
                     <?php endif; ?>
                        <div class="input-group-append">
                        <input class="btn btn-primary" type="submit" value="Find" name="submit">
                        </div>
                     </div>
               </form>
            </div>
         </div>


         <div class="table-responsive">
            <table class="table table-striped text-center" id="dataTable" width="100%" cellspacing="0">
               <thead>
                  <tr>
                     <th scope="col">#</th>
                     <th scope="col">Account Name</th>
                     <th scope="col">API Key</th>
                     <th scope="col">User Role</th>
                     <th scope="col">Ignore Limits</th>
                     <th scope="col">Private Key</th>
                     <th scope="col">Ip Address</th>
                     <th scope="col">Action</th>
                  </tr>
               </thead>
               <tbody id="table-body py-0">
                  <?php if (empty($api_keys)) : ?>
                     <tr class="">
                        <td colspan="8" class="">
                           <div class="alert alert-warning m-0" role="alert">
                              <strong>No Data Was Found!</strong>
                           </div>
                        </td>
                     </tr>
                  <?php endif; ?>
                  <?php foreach ($api_keys as $api_key) : ?>
                     <tr>
                        <th scope="row" class="p-0 pt-2"><?= ++$start ?></th>
                        <td class="p-0 pt-2"><?= $api_key["username"] ?></td>
                        <td class="p-0 pt-2"><?= $api_key["key"] ?></td>
                        <td class="p-0 pt-2"><?= $api_key["role_name"] ?></td>
                        <td class="p-0 pt-2">
                           <?php if ($api_key["ignore_limits"] == "1") : ?>
                              <p class="badge badge-warning">enabled</p>
                           <?php else : ?>
                              <p class="badge badge-danger">disabled</p>
                           <?php endif ?>
                        </td>
                        <td class="p-0 pt-2">
                           <?php if ($api_key["is_private_key"] == "1") : ?>
                              <p class="badge badge-warning">enabled</p>
                           <?php else : ?>
                              <p class="badge badge-danger">disabled</p>
                           <?php endif ?>
                        </td>
                        <td class="p-0 pt-2"><?= $api_key["ip_addresses"] ?></td>
                        <td class="p-0 pt-2">
                           <a href="<?= base_url("api/updateApiKey/" . $api_key["username"]) ?>" class="badge badge-success"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
                           <a href="<?= base_url("api/deleteApiKey/" . $api_key["username"]) ?>" class="badge badge-danger" onclick="return confirm('Are You Sure Want To Delete <?= $api_key['username'] ?>')"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a>
                        </td>
                     </tr>
                  <?php endforeach ?>
               </tbody>
            </table>
         </div>
         <!-- ENd Table -->
      </div>
      <div class="card-footer"></div>
   </div>

</div>
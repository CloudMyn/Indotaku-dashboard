<div class="container-fluid">

   <div class="align-items-center justify-content-between mb-4 row">
      <h1 class="h3 mb-0 text-gray-800">Menu</h1>
   </div>
   <div class="col-md-9">
      <div class="card shadow mb-4">
         <!-- <div class="card-header">
            <h5 class="m-0 font-weight-bold text-primary">Menu Table</h5>
         </div> -->
         <div class="card-body">
            <div class="row mb-3">
               <div class="col-md-12">
                  <?php //$this->session->flashdata("message"); ?>
                  <?= validation_errors("<div class='alert alert-danger'>", "</div>") ?>
               </div>
               <div class="col-md-4">
                  <button class="btn btn-success btn-block" onclick="addMenu()" data-toggle="modal" data-target="#menuModal"><i class="fa fa-plus"></i> Tambah</button>
               </div>
               <div class="col-md-8">
                  <div class="input-group">
                     <input type="text" class="form-control bg-light border-0 small" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="basic-addon2">
                     <div class="input-group-append">
                        <button class="btn btn-primary" type="button"><i class="fa fa-search"></i></button>
                     </div>
                  </div>
               </div>
            </div>
            <table class="table table-striped">
               <thead class="text-center">
                  <tr>
                     <th scope="col">#</th>
                     <th scope="col">name</th>
                     <th scope="col">sub menu</th>
                     <th scope="col">is active</th>
                     <th scope="col">action</th>
                  </tr>
               </thead>
               <tbody class="text-center">
                  <?php $id = 1; ?>
                  <?php foreach ($menus as $menu) : ?>
                     <tr>
                        <th scope="row"><?= $id++ ?></th>
                        <td><?= $menu["menu_name"]; ?></td>
                        <td>
                           <div class="btn-group">
                              <a href="<?= base_url("menu/detailMenu/" . $menu["menu_name"] . "/" . $menu["menu_id"]) ?>" class="btn btn-primary">Sub Menu</a>
                              <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              </button>
                              <?php
                              $menuId = $menu['menu_id'];
                              $query = "SELECT `user_menu`.`menu_id`, `menu_name`, `user_menu`.`is_active`, `sm_title`, `user_sub_menu`.`menu_id`, `user_sub_menu`.`sm_id` FROM `user_menu` JOIN `user_sub_menu` ON `user_menu`.`menu_id` = `user_sub_menu`.`menu_id` WHERE `user_menu`.`menu_id` = $menuId";

                              $subs_menu =  $this->db->query($query)->result_array();
                              ?>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                 <?php foreach ($subs_menu as $sub_menu) : ?>
                                    <button class="dropdown-item"><?= $sub_menu["sm_title"] ?></button>
                                 <?php endforeach ?>
                              </div>
                           </div>
                        </td>
                        <td>
                           <?php if ($menu["is_active"] == 1) { ?>
                              <span class="badge badge-success">Enabled</span>
                           <?php } else { ?>
                              <span class="badge badge-danger">Disabled</span>
                           <?php } ?>
                        </td>
                        <td>
                           <button type="button" class="btn btn-outline-success" onclick="updateMenu('<?= $menu['menu_id'] ?>','<?= $menu['menu_name'] ?>','<?= $menu['is_active'] ?>')" data-toggle="modal" data-target="#menuModal">
                              <i class="fa fa-pencil"></i> Edit
                           </button>
                           <a href="<?= base_url("menu/delete/" . $menu["menu_name"]); ?>" class="btn btn-outline-danger" onclick="return confirm('Are You Sure!')">
                              <i class="fa fa-trash"></i>
                              Delete
                           </a>
                        </td>
                     </tr>
                  <?php endforeach ?>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>




<!-- Modal -->
<form method="post" id="menu-form" action="">
   <div class="modal fade" id="menuModal" tabindex="-1" role="dialog" aria-labelledby="menuModalLabel">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="menuModalLabel">Add New Menu</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span>&times;</span>
               </button>
            </div>
            <div class="modal-body" id="modal-menu">
               <div class="form-group">
                  <label for="menu-name">Menu Name :</label>
                  <input type="text" class="form-control" name="menu-name" id="name-field" value="" autocomplete="off">
               </div>
               <div class="form-group">
                  <label for="isActive">Is Active :</label>
                  <select class="form-control" id="isActive-field" name="is-active">
                     <option value="1">true</option>
                     <option value="0">false</option>
                  </select>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               <button type="submit" class="btn btn-primary" id="btn-submit">Add</button>
            </div>
         </div>
      </div>
   </div>
</form>

<script>
   // function addMenu() {
   //    $("#menuModalLabel").html("Add New Menu");
   //    $("#btn-submit").html("Add");
   //    $("#name-field").val("");
   //    $("#isActive-field").html(`<option value="1">true</option><option value="0">false</option>`);
   // }

   // function updateMenu(name, isActive) {
   //    $("#menuModalLabel").html("Update Menu");
   //    $("#btn-submit").html("Update");
   //    $("#name-field").val(name);
   //    $("#menu-form").attr("action", "");
   //    if (isActive == 1) {
   //       $("#isActive-field").html(`<option value="1">true</option><option value="0">false</option>`);
   //    } else {
   //       $("#isActive-field").html(`<option value="0">false</option><option value="1">true</option>`);
   //    }
   // }
</script>
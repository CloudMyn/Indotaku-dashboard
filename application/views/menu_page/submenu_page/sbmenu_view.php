<div class="container-fluid">

   <div class="col">
      <div class="card shadow mb-4">
         <div class="card-header">
            <h5 class="m-0 font-weight-bold text-primary">Sub Menu Table</h5>
         </div>
         <div class="card-body">
            <div class="row mb-3">
               <div class="col-md-12">
                  <?= $this->session->flashdata("message"); ?>
                  <?= validation_errors("<div class='alert alert-danger'>", "</div>") ?>
               </div>
               <div class="col-md-4">
                  <button class="btn btn-success btn-block" onclick="addSubMenu()" data-toggle="modal" data-target="#subMenuModal"><i class="fa fa-plus" aria-hidden="true"></i> Tambah</button>
               </div>
               <div class="col-md-8">
                  <div class="input-group">
                     <input type="text" class="form-control bg-light border-0 small" placeholder="Find.." aria-describedby="basic-addon2">
                     <div class="input-group-append">
                        <button class="btn btn-primary" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
                     </div>
                  </div>
               </div>
            </div>
            <table class="table table-bordered">
               <thead class="text-center">
                  <tr>
                     <th scope="col">#</th>
                     <th scope="col">Name</th>
                     <th scope="col">Icon</th>
                     <th scope="col">URL</th>
                     <th scope="col">Menu</th>
                     <th scope="col">Is Active</th>
                     <th scope="col">Action</th>
                  </tr>
               </thead>
               <tbody class="text-center">
                  <?php $id = 1; ?>
                  <?php foreach ($sbmenu as $submenu) : ?>
                     <tr>
                        <th scope="row"><?= $id++ ?></th>
                        <td><?= $submenu["sm_title"]; ?></td>
                        <td><i class="<?= $submenu["sm_icon"]; ?>"></i></td>
                        <td><?= $submenu["sm_url"]; ?></td>
                        <td><?= $submenu["menu_name"]; ?></td>
                        <td>
                           <?php if ($submenu["sm_active"] == 1) { ?>
                              <span class="badge badge-success">Enabled</span>
                           <?php } else { ?>
                              <span class="badge badge-danger">Disabled</span>
                           <?php } ?>
                        </td>
                        <td>
                           <a href="#" class="badge badge-warning btn-update" onclick="updateSubMenu(this)" data-toggle="modal" data-target="#subMenuModal" data-id="<?= $submenu["sm_id"] ?>" data-url="<?= base_url() ?>" data-menuid="<?= $submenu["menu_id"] ?>">
                              <i class="fa fa-trash" aria-hidden="true"></i>
                              Update
                           </a>
                           <a href="<?= base_url("menu/sm_delete/" . $submenu["sm_title"]); ?>" class="badge badge-danger" onclick="return confirm('Are You Sure!')">
                              <i class="fa fa-trash" aria-hidden="true"></i>
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
<form method="post" id="sm-form">
   <div class="modal fade" id="subMenuModal" tabindex="-1" role="dialog" aria-labelledby="subMenuModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="subMenuModalLabel">Add New Menu</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body" id="sbmenu-modal">
               <div class="form-group">
                  <label for="name">Name :</label>
                  <input type="text" class="form-control" name="submenu-name" id="menu" value="" autocomplete="off">
               </div>
               <div class="form-group">
                  <label for="icon">Icons :</label>
                  <input type="text" class="form-control" name="submenu-icon" id="icon" value="" autocomplete="off">
               </div>
               <div class="form-group">
                  <label for="url">Url :</label>
                  <input type="text" class="form-control" name="submenu-url" id="url" value="" autocomplete="off">
               </div>
               <div class="form-group">
                  <label for="name">Menu Name :</label>
                  <select class="form-control" id="name" name="menu-name">
                     <?php foreach ($menus as $menu) : ?>
                        <option value="<?= $menu["menu_id"] ?>"><?= $menu["menu_name"] ?></option>
                     <?php endforeach; ?>
                  </select>
               </div>
               <div class="form-group">
                  <label for="isActive">Is Active :</label>
                  <select class="form-control" id="isActive" name="is-active">
                     <option value="1">true</option>
                     <option value="0">false</option>
                  </select>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               <button type="submit" class="btn btn-primary" id="btn-sbmodal">Add</button>
            </div>
         </div>
      </div>
   </div>
</form>


<script>
   function addSubMenu() {
      $("#subMenuModalLabel").html("Add New Sub Menu");
      $("#subMenuModalLabel").html("Update Sub Menu");
      $("#btn-sbmodal").html("Update");
      $("#menu").val("");
      $("#icon").val("");
      $("#url").val("");
      $("#isActive").html(`<option value="1">true</option><option value="0">false</option>`);
      $("#name").html(`
      <?php foreach ($menus as $menu) : ?>
         <option value="<?= $menu["menu_id"] ?>"><?= $menu["menu_name"] ?></option>
      <?php endforeach; ?>
      `);
      $("#sm-form").attr("action", "");
   }

   function updateSubMenu(i) {
      let sm_id = $(i).data("id");
      let url_menu = `${$(i).data("url")}menu/ajaxGetAllMenu`;
      let url_sbmenu = `${$(i).data("url")}menu/ajaxGetAllSubMenuById/${sm_id}`;
      $("#subMenuModalLabel").html("Update Sub Menu");
      $("#btn-sbmodal").html("Update");
      $("#sbmenu-modal").append(`<input type="hidden" value="${sm_id}" name="sm_id">`);
      $("#sm-form").attr("action", `${$(i).data("url")}menu/updateSbMenu`);
      ajaxGetSubMenu(url_menu, url_sbmenu, i);
   }

   function ajaxGetSubMenu(url_menu, url_sbmenu, i) {
      $.get(url_sbmenu, function(json, status) {
         let data = JSON.parse(json);
         $("#menu").val(data.sm_title);
         $("#icon").val(data.sm_icon);
         $("#url").val(data.sm_url);
         if (data.sm_active == 1) {
            $("#isActive").html(`<option value="1">true</option><option value="0">false</option>`);
         } else {
            $("#isActive").html(`<option value="0">false</option><option value="1">true</option>`)
         }
         ajaxGetMenu(url_menu, i);
      });
   }

   function ajaxGetMenu(url_menu, i) {
      $.get(url_menu, function(json, status) {
         let menu_id = $(i).data("menuid");
         let data = JSON.parse(json);
         $("#name").html("");
         for (let index = 0; index < data.length; index++) {
            if (menu_id == data[index].menu_id) {
               $("#name").append(`<option selected value="${data[index].menu_id}">${data[index].menu_name}</option>`);
            } else {
               $("#name").append(`<option value="${data[index].menu_id}">${data[index].menu_name}</option>`);
            }
         }
      });
   }
</script>
<!-- <//var_dump($menu_detail);die; -->
<div class="container-fluid">

   <div class="align-items-center justify-content-between mb-4 row">
      <!-- <div class="col-3"> -->
      <h1 class="h3 mb-0 text-gray-800">Menu Detail</h1>
      <!-- </div> -->
      <!-- <div class="col-3 text-right"> -->
      <a href="<?= base_url("menu") ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-arrow-left fa-sm text-white-50"></i> Back</a>
      <!-- </div> -->
   </div>

   <div class="row">

      <div class="col-3">
         <div class="card shadow md-4">
            <div class="card-header">
               <span class="row">
                  <h5 class="m-0 font-weight-bold text-primary col-5">Menu</h5>
                  <div class="form-check col-7 text-right">
                     <input class="form-check-input" type="checkbox" value="" id="editMode">
                     <label class="form-check-label" for="editMode">
                        Edit Mode
                     </label>
                  </div>
               </span>
            </div>
            <div class="card-body m-0">
               <form action="" method="post" class="m-0 p-0">
                  <div class="form-group">
                     <label for="menu-name">Menu Name :</label>
                     <input type="hidden" name="id" value="<?= $menu["menu_id"]?>">
                     <input type="text" class="form-control user-input" name="menu-name" id="menu" value="<?= $menu["menu_name"]; ?>" disabled>
                  </div>
                  <div class="form-group d-inline">
                     <label for="menu-name">Is Active :</label>
                     <?php if ($menu["is_active"] == 1) { ?>
                        <span class="badge badge-success">true</span>
                     <?php } else { ?>
                        <span class="badge badge-danger">false</span>
                     <?php } ?>
                     <select class="form-control user-input" name="is-active" id="is-active" disabled>
                        <?php if ($menu["is_active"] == 1) { ?>
                           <option value="1">true</option>
                           <option value="0">false</option>
                        <?php } else { ?>
                           <option value="0">false</option>
                           <option value="1">true</option>
                        <?php } ?>
                     </select>
                  </div>
                  <!-- Prevent default -->
                  <button id="btn-submit" class="btn btn-primary btn-block mt-3 disabled">Submit</button>
               </form>
            </div>
         </div>
      </div>

      <div class="col-9">
         <div class="card shadow mb-4">
            <div class="card-header">
               <h5 class="m-0 font-weight-bold text-primary col-5">Sub Menu</h5>
            </div>
            <div class="card-body">
               <table class="table table-striped text-center">
                  <thead>
                     <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Icon</th>
                        <th scope="col">URL</th>
                        <th scope="col">is Active</th>
                        <th scope="col">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php $id = 1; ?>
                     <?php foreach ($subs_menu as $sub_menu) : ?>
                        <tr>
                           <th scope="row"><?= $id++ ?></th>
                           <td><?= $sub_menu["sm_title"]; ?></td>
                           <td><?= $sub_menu["sm_icon"]; ?></td>
                           <td><?= $sub_menu["sm_url"]; ?></td>
                           <td><?= $sub_menu["sm_active"]; ?></td>
                           <td>
                              <a href="#closeMe" class="badge badge-success py-1"><i class="fa fa-pencil"></i></a>
                              <a href="#closeMe" class="badge badge-danger py-1"><i class="fa fa-trash-o"></i></a>
                           </td>
                        </tr>
                     <?php endforeach ?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>

   </div>

</div>
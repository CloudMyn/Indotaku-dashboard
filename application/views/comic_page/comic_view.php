<div class="container-fluid">

   <style>
      p.max-lines-1 {
         overflow: hidden;
         text-overflow: ellipsis;
         display: -webkit-box;
         line-clamp: 1;
         -webkit-line-clamp: 1;
         -webkit-box-orient: vertical;
      }
   </style>

   <div class="col-12 p-0">
      <div class="card shadow-sm mb-4">
         <div class="card-body py-1 pt-3">
            <div class="row mb-2 no-gutters">
               <div class="col-md-12">
                  <?= $this->session->flashdata("message") ?>
               </div>
               <div class="col-md-1 text-center">
                  <h3 class="btn btn-danger btn-block"><?= $totals_result ?></h3>
               </div>

               <div class="col-md-1 align-center pl-2">
                  <a href="<?= base_url("comic/delete-search") ?>" class="btn btn-warning w-75"><i class="fa fa-refresh" aria-hidden="true"></i></a>
               </div>

               <div class="col-md-3 pr-2">
                  <a href="<?= base_url("comic/new-comic") ?>" class="btn btn-success btn-block"><i class="fa fa-plus" aria-hidden="true"></i> New Comic</a>
               </div>

               <div class="col-md-7">
                  <form method="post">
                     <div class="input-group b">
                        <?php if (isset($keyword)) : ?>
                           <input type="text" class="form-control bg-light border-0 small" id="comic-search" placeholder="Looking For a Comic here..." name="keyword" autocomplete="off" value="<?= $keyword ?>">
                        <?php else : ?>
                           <input type="text" class="form-control bg-light border-0 small" id="comic-search" placeholder="Looking For a Comic here..." name="keyword" autocomplete="off">
                        <?php endif; ?>
                        <div class="input-group-append">
                           <input class="btn btn-primary" type="submit" value="Find" name="submit">
                        </div>
                     </div>
                  </form>
               </div>
            </div>
            <div class="table-responsive">
               <table class="table table-striped text-center table-sm" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                     <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Author</th>
                        <th scope="col">Total Chapters</th>
                        <th scope="col">Status</th>
                        <th scope="col">Is Active</th>
                        <th scope="col">Action</th>
                     </tr>
                  </thead>
                  <tbody id="table-body py-0">
                     <?php if (empty($comics)) : ?>
                        <tr class="">
                           <td colspan="7" class="">
                              <div class="alert alert-warning m-0" role="alert">
                                 <strong>No Data Was Found!</strong>
                              </div>
                           </td>
                        </tr>
                     <?php endif; ?>
                     <?php foreach ($comics as $comic) : ?>
                        <tr>
                           <th scope="row" class="p-0 pt-2 px-3"><?= ++$start ?></th>
                           <td class="p-0 pt-2" style="width: 40%;">
                              <p class="m-0 max-lines-1"><?= $comic["comic_name"] ?></p>
                           </td>
                           <td class="p-0 pt-2" style="width: 20%;">
                              <p class="m-0 max-lines-1"><?= $comic["comic_author"] ?></p>
                           </td>
                           <td class="p-0 pt-2"><?= $comic["comic_chapters"] ?></td>
                           <td class="p-0 pt-2">
                              <?php if ($comic["comic_status"] == 1) { ?>
                                 <p class="badge badge-warning">Ongoing</p>
                              <?php } else { ?>
                                 <p class="badge badge-danger">end</p>
                              <?php } ?>
                           </td>
                           <td class="p-0 pt-2">
                              <?php if ($comic["comic_active"] == "1") : ?>
                                 <p class="badge badge-info">true</p>
                              <?php else : ?>
                                 <p class="badge badge-danger">false</p>
                              <?php endif ?>
                           </td>
                           <td class="p-0 pt-2">
                              <a href="<?= base_url("comic/update-comic/" . $comic["comic_slug"]) ?>" class="badge badge-success"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
                              <a href="<?= base_url("comic/delete-comic/" . $comic["comic_slug"]) ?>" class="badge badge-danger" onclick="return confirm('Are You Sure Want To Delete <?= $comic['comic_name'] ?>')"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a>
                           </td>
                        </tr>
                     <?php endforeach ?>
                  </tbody>
               </table>
            </div>
            <!-- ENd Table -->
         </div>
         <div class="card-footer">
            <div class="row">
               <div class="col-md-6">
                  <?= $this->pagination->create_links() ?>
               </div>
               <div class="col-md-4">
                  <form class="form-group row p-0 m-0 no-gutters" method="POST">
                     <div class="col-2">
                        <button type="submit" name="order-btn" class="btn btn-primary btn-block">Ok</button>
                     </div>
                     <div class="col-6 mx-1">
                        <select class="custom-select mr-sm-2" name="order-name">
                           <?php foreach ($order_menu as $k => $v) : ?>
                              <?php if ($k == $order_menu_active) : ?>
                                 <option selected value="<?= $k ?>"><?= $v ?></option>
                              <?php else : ?>
                                 <option value="<?= $k ?>"><?= $v ?></option>
                              <?php endif; ?>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="col-3">
                        <select class="custom-select mr-sm-2" name="order-type">

                           <?php foreach ($order_menu_type as $k) : ?>
                              <?php if ($k == $order_type_active) : ?>
                                 <option selected value="<?= $k ?>"><?= $k ?></option>
                              <?php else : ?>
                                 <option value="<?= $k ?>"><?= $k ?></option>
                              <?php endif; ?>
                           <?php endforeach; ?>
                        </select>
                     </div>
                  </form>
               </div>
               <div class="col-md-2">
                  <a href="<?= base_url("comic/scrapeComics") ?>" class="btn btn-primary btn-block">Scrape Comic</a>
               </div>
            </div>
         </div>
      </div>

   </div>
   <!-- end-row -->
</div>

<script>
   $('#exampleModal').on('show.bs.modal', event => {
      var button = $(event.relatedTarget);
      var modal = $(this);
      // Use above variables to manipulate the DOM

   });
</script>


<script>
   $("#comic-searchs").on("keyup", function(i) {
      let val = $(this).val();
      let id = 0;
      if (val == null || val == "") {
         _ajaxGetTable("<?= base_url("comic/ajaxGetTableLimitBy/") ?>", '<?= $limit ?>', 0);
         return;
      }
      $.get(`<?= base_url("comic/ajaxSearchComic/") ?>${val}`, function(json, status) {
         let data = JSON.parse(json);
         $("#table-body").html("");
         if (data.status == 0) {
            $("#table-body").html(`<tr>
               <th scope="row">#</th>
               <td>not found!</td>
               <td>not found!</td>
               <td>not found!</td>
               <td>not found!</td>
               <td>not found!</td>
               <td>not found!</td></tr>`);
            console.log(data.message);
            return;
         } else {
            for (let i = 0; i < data.totals; i++) {
               $("#table-body").append(`<tr>
               <th scope="row">${++id}</th>
               <td>${data.comics[i].name}</td>
               <td>${data.comics[i].author}</td>
               <td>${data.comics[i].total_chapters}</td>
               <td>${_status(data.comics[i].status)}</td>
               <td>${_active(data.comics[i].is_active)}</td>
               <td>${_action(data.comics[i])}</td></tr>`);
            }
         }
      });
   });

   function _status(status) {
      if (status == 1) {
         return `<p class="badge badge-warning">Ongoing</p>`;
      } else {
         return `<p class="badge badge-danger">End</p>`;
      }
   }

   function _active(isActive) {
      if (isActive == 1) {
         return `<p class="badge badge-info">true</p>`;
      } else {
         return `<p class="badge badge-danger">false</p>`;
      }
   }

   function _action(data) {
      return `<a href="<?= base_url("comic/updateComic/") ?>${data.comic_slug}" class="badge badge-success"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a> <a href="<?= base_url("comic/deletecomic/") ?>${data.comic_slug}/${data.cover}" class="badge badge-danger" onclick="return confirm('Are You Sure Want To Delete ${data.name}')"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a>`
   }
</script>
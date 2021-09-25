<div class="container-fluid">

   <!-- Page Heading -->
   <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-primary">Comic Logs</h1>
      <?php if ($current_logs != "comic") : ?>
         <?php if ($current_logs == "chapter") : ?>
            <a href="<?= base_url("comic/logs") ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-arrow-left fa-fw fa-sm text-white-50"></i> Back</a>
         <?php elseif ($current_logs == "image") : ?>
            <a href="<?= base_url("comic/logs/chapter/" . $comic_name) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-arrow-left fa-fw fa-sm text-white-50"></i> Back</a>
         <?php endif; ?>
      <?php endif; ?>
   </div>


   <div class="row">
      <div class="col-12">
         <div class="card shadow-sm">
            <div class="card-body py-2">
               <table class="table table-sm table-hover table-inverse my-0 table-borderless">
                  <thead class="thead-inverse text-uppercase text-center">
                     <th scope="col">date</th>
                     <th scope="col">name</th>
                     <th scope="col">related comic</th>
                     <th scope="col">message</th>
                     <th scope="col">chapter log</th>
                  </thead>
                  <tbody class="text-capitalize text-center">
                     <?php if (empty($logs)) : ?>
                        <tr>
                           <td colspan="5">
                              <div class="alert alert-danger my-0" role="alert">
                                 There Is No <strong>Logs!</strong> Was Found!
                              </div>
                           </td>
                        </tr>
                     <?php else : ?>
                        <?php foreach ($logs as $log) : ?>
                           <tr>
                              <td><?= date("h:m | d-M-Y", $log['_kl_date']) ?></td>
                              <?php if ($current_logs == "image") : ?>
                                 <td><a href='<?= $log['_kl_image_link'] ?>' target='blank'>link image</a></td>
                              <?php else : ?>
                                 <td><?= $log['_kl_name'] ?></td>
                              <?php endif; ?>
                              <td><?= $log['_kl_komik_name'] ?></td>
                              <?php if ($log['_kl_type'] == 0) : ?>
                                 <td>
                                    <div class="dropright">
                                       <a href="#" class="badge badge-danger" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Error</a>
                                       <div class="dropdown-menu p-4 text-muted" aria-labelledby="dropdownMenuLink" style="max-width: 1000px; width: 450px;">
                                          <p class="text-justify"> <?= $log['_kl_msg'] ?></p>
                                       </div>
                                    </div>
                                 </td>
                              <?php else : ?>
                                 <td>
                                    <div class="dropright">
                                       <a href="#" class="badge badge-success" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Success</a>
                                       <div class="dropdown-menu p-4 text-muted" aria-labelledby="dropdownMenuLink" style="max-width: 1000px; width: 450px;">
                                          <p class="text-justify"> <?= $log['_kl_msg'] ?></p>
                                       </div>
                                    </div>
                                 </td>
                              <?php endif; ?>
                              <?php if ($current_logs == "comic") : ?>
                                 <td><a href="<?= base_url("comic/logs/chapter/" . $log['_kl_komik_name']) ?>" class="badge badge-primary">Chapter Logs</a></td>
                              <?php elseif ($current_logs == "chapter") : ?>
                                 <td><a href="<?= base_url("comic/logs/image/" . $log['_kl_komik_name'] . "/" . $log['_kl_name']) ?>" class="badge badge-primary">Image Logs</a></td>
                              <?php else : ?>
                                 <td><a href="<?= base_url("comic/logs/chapter/" . $log['_kl_komik_name']) ?>" class="badge badge-primary">Back To Chapter Logs</a></td>
                              <?php endif; ?>
                           </tr>
                        <?php endforeach; ?>
                     <?php endif; ?>
                  </tbody>
               </table>
            </div>
            <div class="card-footer py-1 row">
               <div class="col-10">
                  <?= $this->pagination->create_links(); ?></div>
               <div class="col-2 text-right">
                  <button type="button" class="btn btn-primary py-1"> Clear </button>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
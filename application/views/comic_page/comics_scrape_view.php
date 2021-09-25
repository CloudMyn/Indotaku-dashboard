<div class="container-fluid">
   <!-- Page Heading -->
   <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Scrape Comics</h1>

      <a href="<?= base_url("comic/") ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-arrow-left fa-sm text-white-50"></i> Back</a>
   </div>
   <form action="" method="post">
      <div class="row mt-3">
         <div class="col-8">
            <div class="card shadow mb-4">
               <div class="card-header">
                  <h5 class="m-0 font-weight-bold text-primary">Web Scrape</h5>
               </div>
               <div class="card-body">
                  <?= $this->session->flashdata("message") ?>
                  <div class="form-group">
                     <label for="web-url">Web Comic URL</label>
                     <input type="text" class="form-control" name="web-url" id="web-url" placeholder="https:// or http://" value="">
                  </div>
                  <?= form_error("web-url") ?>
                  <div class="form-group">
                     <label for="comic-selector">Comic Selector</label>
                     <input type="text" class="form-control" name="comic-selector" id="comic-selector" placeholder="CSS Comic selector" value="<?= set_value("comic-selector") ?>">
                  </div>
                  <?= form_error("comic-selector") ?>
                  <div class="form-group">
                     <label for="chapter-selector">Chapter Selector</label>
                     <input type="text" class="form-control" name="chapter-selector" id="chapter-selector" placeholder="CSS Image selector" value="<?= set_value("chapter-selector") ?>">
                  </div>
                  <?= form_error("chapter-selector") ?>
                  <div class="form-group">
                     <label for="image-selector">Image Selector</label>
                     <input type="text" class="form-control" name="image-selector" id="image-selector" placeholder="CSS Image selector" value="<?= set_value("image-selector") ?>">
                  </div>
                  <?= form_error("image-selector") ?>
               </div>
               <div class="card-footer">
               </div>
            </div>
         </div>
         <!-- Column End -->

         <div class="col-4">

            <div class="row no-gutters">
               <div class="card shadow mb-4 col">
                  <div class="card-header">
                     <h5 class="m-0 font-weight-bold text-primary">Scrape Options</h5>
                  </div>
                  <div class="card-body py-3">
                     <div class="form-group m-0 mb-2">
                        <select name="web-saveopt" id="websaveopt" class="custom-select mr-sm-2">
                           <option value="0" selected> -- Dont Save -- </option>
                           <option value="1"> -- Save Files -- </option>
                        </select>
                     </div>
                     <?= form_error("web-saveopt") ?>


                     <div class="form-group my-2 no-gutters">
                        <select class="custom-select text-center" id="update-only" name="update-only">
                           <option value="1" selected> -- Hanya Update Saja -- </option>
                           <option value="0"> -- Add If Not Available -- </option>
                        </select>
                     </div>
                     <?= form_error("update-only") ?>

                     <div class="form-group m-0">
                        <select name="web-sources" id="webscrape" class="custom-select mr-sm-2">
                           <option value="null"> -- Select Web Sources --</option>
                           <?php foreach ($scrape_source as $sc) : ?>
                              <option value="<?= $sc["ws_komik_name"] ?>"><?= $sc["ws_komik_name"] ?></option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                  </div>
                  <div class="card-footer">
                     <button type="submit" class="btn btn-block btn-primary">submit</button>
                  </div>
               </div>
            </div>
            <!-- End Card Row -->

         </div>
         <!-- Column End -->

      </div>
   </form>

</div>


<script>
   $("#webscrape").on("change", function() {
      let val = $(this).val();
      if (!isEmpty(val)) {
         $.get(`<?= base_url("comic/ajax-get-target/") ?>${val}`, function(json, status) {
            let data = JSON.parse(json);
            $("#comic-selector").val(data.ws_komik_selector);
            $("#comic-selector").attr("readonly", "true");
            $("#chapter-selector").val(data.ws_komik_selector_chap);
            $("#chapter-selector").attr("readonly", "true");
            $("#image-selector").val(data.ws_komik_selector_img);
            $("#image-selector").attr("readonly", "true");
         });
      } else {
         $("#comic-selector").val("");
         $("#comic-selector").removeAttr("readonly");
         $("#chapter-selector").val("");
         $("#chapter-selector").removeAttr("readonly");
         $("#image-selector").val("");
         $("#image-selector").removeAttr("readonly");
      }
   });

   function isEmpty(val) {
      if (val != "null") {
         return false;
      } else {
         return true;
      }
   }
</script>
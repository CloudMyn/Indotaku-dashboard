<div class="container-fluid">
   <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Add New Comic</h1>


      <a href="<?= base_url("comic/scrape-comic") ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i class="fa fa-cloud-download fa-fw" aria-hidden="true"></i> Scrape Comic</a>
   </div>



   <form method="post" enctype="multipart/form-data">
      <!-- Start Row -->
      <div class="row">

         <div class="col-md-8">

            <div class="card shadow-sm mb-4">
               <div class="card-header">
                  <h5 class="m-0 font-weight-bold text-primary">Comic Description</h5>
               </div>
               <div class="card-body">
                  <div class="form-group my-1">
                     <label for="comic_name">Name* : </label>
                     <input type="text" class="form-control" name="comic_name" id="comic_name" placeholder="Comic Name" value="<?= set_value("comic_name") ?>">
                     <?= form_error("comic_name", '<p class="text-danger p-0 m-0">', '</p>') ?>
                  </div>
                  <div class="form-group my-1">
                     <label for="comic_author">Author* : </label>
                     <input type="text" class="form-control" name="comic_author" id="comic_author" placeholder="Comic author" value="<?= set_value("comic_author") ?>">
                     <?= form_error("comic_author", '<p class="text-danger p-0 m-0">', '</p>') ?>
                  </div>
                  <div class="form-group my-1">
                     <label for="comic_desc">Sinopsis* : </label>
                     <textarea class="form-control" id="comic_desc" rows="3" name="comic_desc" placeholder="Comic Sinopsis"><?= set_value("comic_desc") ?></textarea>
                     <?= form_error("comic_desc", '<p class="text-danger p-0 m-0">', '</p>') ?>
                  </div>
               </div>
               <div class="card-footer">
                  <button type="submit" class="btn btn-primary btn-block">Submit</button>
               </div>
            </div>

         </div>


         <div class="col-4">

            <div class="card shadow-sm mb-4">
               <div class="card-header">
                  <h5 class="font-weight-bold text-primary my-0">Comic Cover*</h5>
               </div>
               <div class="card-body text-center py-1">
                  <img src="" class="my-1" alt="Image" width="70%" id="view-cover" hidden>
                  <div class="form-group my-1" id="form-name">
                     <input type="file" name="comic_cover" id="input-image" hidden>
                     <input type="text" class="form-control" id="view-name" name="image-placeholder" placeholder="Your image" value="" readonly>
                  </div>
                  <?= form_error("image-placeholder") ?>
               </div>
               <div class="card-footer text-muted">
                  <button type="button" class="btn btn-primary btn-block" id="btn-image-choose">Choose</button>
               </div>
            </div>

            <div class="card shadow-sm mb-4">
               <div class="card-header">
                  <h5 class="font-weight-bold text-primary my-0">Addition</h5>
               </div>
               <div class="card-body py-2">

                  <div class="form-group row my-2">
                     <label for="comic_status" class="col-sm-3 col-form-label text-center">Status</label>
                     <div class="col-sm-9">
                        <select class="custom-select mr-sm-2" id="comic_status" name="comic_status">
                           <option value="1" selected>Ongoing</option>
                           <option value="0">End</option>
                        </select>
                     </div>
                  </div>

                  <div class="form-group row my-2">
                     <label for="comic_active" class="col-sm-3 col-form-label text-center">IsActive</label>
                     <div class="col-sm-9">
                        <select class="custom-select mr-sm-2" id="comic_active" name="comic_active">
                           <option value="1" selected>Active</option>
                           <option value="0">Not Active</option>
                        </select>
                     </div>
                  </div>

                  <div class="form-group row my-2">
                     <label for="comic_rating" class="col-sm-3 col-form-label text-center">Rating*</label>
                     <div class="col-sm-9">
                        <input class="form-control" value="5.00" type="text" id="comic_rating" name="comic_rating">
                     </div>
                  </div>

                  <div class="form-group row my-2 mb-3">
                     <label for="comic_type" class="col-sm-3 col-form-label text-center">Type*</label>
                     <div class="col-sm-9">
                        <select class="custom-select mr-sm-2" id="comic_type" name="comic_type">
                           <option value="" selected>Comic Type</option>
                           <option value="Manga">Manga</option>
                           <option value="Manhua">Manhua</option>
                           <option value="Manhwa">Manhwa</option>
                        </select>
                     </div>
                  </div>

                  <div class="form-group row my-2 mb-3">
                     <label for="comic_web_source" class="col-sm-3 col-form-label text-center">Web</label>
                     <div class="col-sm-9">
                        <select class="custom-select mr-sm-2" id="comic_web_source" name="comic_web_source">
                           <option selected value="none">none</option>
                           <?php foreach ($sources as $source) : ?>
                              <option value="<?= $source["ws_komik_name"] ?>"><?= $source["ws_komik_name"] ?></option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                  </div>


                  <div class="form-group row my-2">
                     <label for="comic_18plus" class="col-sm-3 col-form-label text-center">is 18+</label>
                     <div class="col-sm-9">
                        <select class="custom-select mr-sm-2" id="comic_18plus" name="comic_18plus">
                           <option value="0" selected>No</option>
                           <option value="1">Yes</option>
                        </select>
                     </div>
                  </div>

                  <div class="form-group row my-2">
                     <label for="comic_project" class="col-sm-3 col-form-label text-center">Project</label>
                     <div class="col-sm-9">
                        <select class="custom-select mr-sm-2" id="comic_project" name="comic_project">
                           <option value="1" selected>Yes</option>
                           <option value="0">No</option>
                        </select>
                     </div>
                  </div>

                  <div class="form-group row my-2">
                     <label for="comic_storage" class="col-sm-3 col-form-label text-center">Storage</label>
                     <div class="col-sm-9">
                        <select class="custom-select mr-sm-2" id="comic_storage" name="comic_storage">
                           <option value="1" selected>Server Storage</option>
                           <option value="0">Cloud Storage</option>
                        </select>
                     </div>
                  </div>


               </div>


               <div class="card-footer py-1">
                  <div class="col-12 my-2 p-0">
                     <?= form_error("comic_status") ?>
                  </div>
                  <div class="col-12 my-2 p-0">
                     <?= form_error("comic_active") ?>
                  </div>
                  <div class="col-12 my-2 p-0">
                     <?= form_error("comic_rating") ?>
                  </div>
                  <div class="col-12 my-2 p-0">
                     <?= form_error("comic_type") ?>
                  </div>
                  <div class="col-12 my-2 p-0">
                     <?= form_error("comic_storage") ?>
                  </div>
               </div>
            </div>


            <div class="card shadow-sm mb-4">
               <div class="card-header">
                  <h5 class="font-weight-bold text-primary my-0">Genre*</h5>
               </div>
               <div class="card-body pl-5 py-1">
                  <div class="row no-gutters py-2">
                     <input type="hidden" name="comic_genre">
                     <?php foreach ($genres as $genre) : ?>
                        <div class="col-md-6">
                           <div class="form-check">
                              <input class="form-check-input" name="<?= strtolower($genre["name"]); ?>" type="checkbox" value="<?= strtolower($genre["genre"]); ?>" id="<?= strtolower($genre["genre"]); ?>">
                              <label class="form-check-label" for="<?= strtolower($genre["genre"]); ?>">
                                 <?= $genre["genre"] ?>
                              </label>
                           </div>
                        </div>
                     <?php endforeach ?>
                  </div>
               </div>
               <div class="card-footer">
                  <div class="col-12 m-0 p-0">
                     <?= form_error("genre") ?>
                  </div>
               </div>
            </div>

         </div>


      </div>
      <!-- Row end -->
   </form>


</div>


<script>
   //--Simulasikan Input File Di Klik Ketiak Tombol Choose Di Tekan
   $("#btn-image-choose").on("click", function() {
      $("#input-image").click();
   });
   //--Tampilkan Gambar Ketika Telah Dipilih
   $("#input-image").on("change", function() {
      _previewImage();
   });

   //--Fugsi Tampilkan Gambar
   function _previewImage() {
      let inputFile = document.getElementById("input-image");
      //-Isi Dari File Merupakan Object Yang Berisi Property Files
      let file = inputFile.files[0];
      //-Buat Url File Yang Dipilih
      let url = URL.createObjectURL(file);
      //--inisialisasi Aturan
      let allowedExten = /^(jpg|jpeg|png)$/;
      let fileExten = file.name.split(".").pop().toLowerCase();
      //--Validasi File
      // console.log(file);
      if (file.type.split("/").shift() != "image") {
         alert("Gambar Tidak Valid, Kami Hanya Menerima File Gambar Saja");
         return;
      }
      if (!allowedExten.exec(fileExten)) {
         alert("Extensi Tidak Valid, Kami Hanya Menerima JPG|JPEG|PNG");
         return;
      }
      if (file.size >= 3048000) {
         alert("Gambar Terlalu Besar, Ukuran Maximal 3Mb");
         return;
      }
      $("#view-name").attr("value", `Name : ${file.name}`);
      $("#view-cover").attr("src", url);
      $("#view-cover").removeAttr("hidden");
   }
</script>
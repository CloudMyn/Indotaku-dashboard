<div class="container-fluid">
   <!-- Page Heading -->
   <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Comic <?= $comic["comic_name"] ?></h1>

      <a href="<?= base_url("comic") ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-arrow-left fa-sm text-white-50"></i> Back</a>
   </div>

   <form enctype="multipart/form-data" method="post" id="my-form">

      <div class="row">


         <div class="col-md-8">


            <div class="card shadow-sm mb-4">
               <div class="card-header">
                  <h5 class="m-0 font-weight-bold text-primary">Comic Description</h5>
               </div>
               <div class="card-body">
                  <div class="form-group my-1">
                     <label for="comic_name">Name : </label>
                     <input type="text" class="form-control" name="comic_name" id="comic_name" placeholder="Comic Name" value="<?= $comic["comic_name"] ?>">
                     <?= form_error("comic_name", '<p class="text-danger p-0 m-0">', '</p>') ?>
                  </div>
                  <div class="form-group my-1">
                     <label for="comic_author">Author : </label>
                     <input type="text" class="form-control" name="comic_author" id="comic_author" placeholder="Comic author" value="<?= $comic["comic_author"] ?>">
                     <?= form_error("comic_author", '<p class="text-danger p-0 m-0">', '</p>') ?>
                  </div>
                  <div class="form-group my-1">
                     <label for="comic_desc">Sinopsis : </label>
                     <textarea class="form-control" id="comic_desc" rows="3" name="comic_desc"><?= $comic["comic_desc"] ?></textarea>
                     <?= form_error("comic_desc", '<p class="text-danger p-0 m-0">', '</p>') ?>
                  </div>
               </div>
               <div class="card-footer">
                  <button type="submit" class="btn btn-primary btn-block" id="btn-comic-submit">Submit</button>
               </div>
            </div>


            <div class="card shadow-sm mb-4">
               <div class="card-header">
                  <!-- method="POST" action="<?= base_url("comic/download-chapter") ?>" -->
                  <div class="row">
                     <div class="col-3">
                        <h5 class="m-0 font-weight-bold text-primary">Comic Chapters</h5>
                     </div>
                     <div class="col-9 text-right">
                        <a href="<?= base_url("chapter/update/" . $comic["comic_slug"]) ?>" class="badge badge-warning"><i class="fa fa-refresh fa-1x" aria-hidden="true"></i> Update</a>
                        <a href="#" data-toggle="modal" data-target="#modal-choose" class="badge badge-primary"><i class="fa fa-plus-square fa-1x"></i> New</a>
                     </div>
                  </div>
               </div>
               <div class="card-body py-3" style="height: 400px; overflow: auto" id="chapter-list">
                  <?php
                  $index = $total_chapters;
                  foreach ($chapters as $chapter) : ?>
                     <div class="alert bg-light border-left-primary shadow-sm-sm row mx-1 no-gutters" role="alert" id="<?= $chapter["chapter_id"] ?>">
                        <div class="pr-2">
                           <input type="checkbox" name="checkbox[]" class="checkbox" value="<?= $comic["comic_slug"] . "|" . $chapter["chapter_id"] ?>" class="m-0 p-0" onclick="check_clicked_box(this)" data-index="<?= --$index ?>">
                        </div>
                        <div class="col-8 text-dark">
                           <p class="m-0"><?= _filterParamsByUs($chapter["chapter_name"]) ?></p>
                        </div>
                        <div class="col-3 px-0 ml-4 text-right">
                           <a href="<?= base_url("chapter/view/" . $chapter["chapter_slug"]) ?>" class="badge badge-success"><i class="fa fa-sign-in"></i> detail</a>
                           <a href="<?= base_url("chapter/delete-chapter/" . $comic["comic_slug"] . "/" . $chapter["chapter_id"]) ?>" class="badge badge-danger border-0" onclick="return confirm('Yakin!!')"><i class="fa fa-trash-o"></i> delete</a>
                        </div>
                     </div>
                  <?php endforeach ?>
               </div>
               <div class="card-footer row no-gutters justify-content-end">
                  <div class="col-8 py-0 pr-1 text-left d-none" id="footer-menu">
                     <button type="button" class="badge btn-danger border-0"><i class="fa fa-fw fa-trash"></i> delete</button>
                     <button type="submit" class="badge btn-success border-0"><i class="fa fa-fw fa-download"></i> download</bu>
                  </div>
                  <div class="col-1 py-0 pr-1 bg-infos text-right">
                     <input type="checkbox" id="check-all" onclick="check_all(this)">
                  </div>
                  <div class="col-3 py-0 bg-infos text-left">
                     <h6 class="m-0 font-weight-bold text-secondary" id="info-chapters"> | totals chapters : <?= $total_chapters ?></h6>
                  </div>
               </div>
            </div>


         </div>


         <div class="col-md-4">

            <div class="card shadow-sm mb-4">
               <div class="card-header">
                  <h5 class="font-weight-bold text-primary my-0">Image Cover</h5>
               </div>
               <div class="card-body text-center py-1">
                  <img src="<?= base_url("assets/image/komik/" . $comic["comic_cover"]) ?>" class="my-1" alt="Image" width="70%" id="view-cover">
                  <div class="form-group my-1" id="form-name">
                     <input type="file" name="comic_cover" id="input-image" hidden>
                     <input type="text" class="form-control" id="view-name" name="image-placeholder" placeholder="Your image" value="<?= $comic["comic_cover"] ?>" readonly>
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
                           <?php if ($comic["comic_status"] == 1) { ?>
                              <option value="1" selected>Ongoing</option>
                              <option value="0">End</option>
                           <?php } else { ?>
                              <option value="0" selected>End</option>
                              <option value="1">Ongoing</option>
                           <?php } ?>
                        </select>
                     </div>
                  </div>

                  <div class="form-group row my-2">
                     <label for="comic_active" class="col-sm-3 col-form-label text-center">isActive</label>
                     <div class="col-sm-9">
                        <select class="custom-select mr-sm-2" id="comic_active" name="comic_active">
                           <?php if ($comic["comic_active"] == 1) { ?>
                              <option value="1" selected>Active</option>
                              <option value="0">Not Active</option>
                           <?php } else { ?>
                              <option value="0" selected>Not Active</option>
                              <option value="1">Active</option>
                           <?php } ?>
                        </select>
                     </div>
                  </div>

                  <div class="form-group row my-2">
                     <label for="comic_rating" class="col-sm-3 col-form-label text-center">Rating</label>
                     <div class="col-sm-9">
                        <input class="form-control" value="<?= $comic["comic_rating"] ?>" type="text" id="comic_rating" name="comic_rating">
                     </div>
                  </div>

                  <div class="form-group row my-2 mb-3">
                     <label for="comic_type" class="col-sm-3 col-form-label text-center">Type</label>
                     <div class="col-sm-9">
                        <select class="custom-select mr-sm-2" id="comic_type" name="comic_type">
                           <?php if ($comic["comic_type"] == "Manga") { ?>
                              <option value="Manga">Manga</option>
                              <option value="Manhua">Manhua</option>
                              <option value="Manhwa">Manhwa</option>
                           <?php } else if ($comic["comic_type"] == "Manhua") { ?>
                              <option value="Manhua">Manhua</option>
                              <option value="Manga">Manga</option>
                              <option value="Manhwa">Manhwa</option>
                           <?php } else { ?>
                              <option value="Manhwa">Manhwa</option>
                              <option value="Manhua">Manhua</option>
                              <option value="Manga">Manga</option>
                           <?php } ?>
                        </select>
                     </div>
                  </div>

                  <div class="form-group row my-2 mb-3">
                     <label for="comic_web_source" class="col-sm-3 col-form-label text-center">web</label>
                     <div class="col-sm-9">
                        <select class="custom-select mr-sm-2" id="comic_web_source" name="comic_web_source">
                           <?php foreach ($sources as $src) : ?>
                              <?php if ($comic["comic_web_source"] == $src["ws_komik_name"]) : ?>
                                 <option value="<?= $src["ws_komik_name"] ?>" selected><?= join(" ",  explode("-", $src["ws_komik_name"])) ?></option>
                              <?php else : ?>
                                 <option value="<?= $src["ws_komik_name"] ?>"><?= $src["ws_komik_name"] ?></option>
                              <?php endif; ?>
                           <?php endforeach; ?>
                        </select>
                     </div>
                  </div>

                  <div class="form-group row my-2">
                     <label for="comic_18plus" class="col-sm-3 col-form-label text-center">is 18+</label>
                     <div class="col-sm-9">
                        <select class="custom-select mr-sm-2" id="comic_18plus" name="comic_18plus">
                           <?php if ($comic["comic_18plus"] == 1) { ?>
                              <option value="1" selected>Yes</option>
                              <option value="0">No</option>
                           <?php } else { ?>
                              <option value="0" selected>No</option>
                              <option value="1">Yes</option>
                           <?php } ?>
                        </select>
                     </div>
                  </div>

                  <div class="form-group row my-2">
                     <label for="comic_project" class="col-sm-3 col-form-label text-center">Project</label>
                     <div class="col-sm-9">
                        <select class="custom-select mr-sm-2" id="comic_project" name="comic_project">
                           <?php if ($comic["comic_project"] == 1) { ?>
                              <option value="1" selected>Yes</option>
                              <option value="0">No</option>
                           <?php } else { ?>
                              <option value="0" selected>No</option>
                              <option value="1">Yes</option>
                           <?php } ?>
                        </select>
                     </div>
                  </div>


                  <div class="form-group row my-2">
                     <label for="comic_storage" class="col-sm-3 col-form-label text-center">Storage</label>
                     <div class="col-sm-9">
                        <select class="custom-select mr-sm-2" id="comic_storage" name="comic_storage">
                           <?php if ($comic["comic_storage"] == 1) { ?>
                              <option value="1" selected>Server Storage</option>
                              <option value="0">Cloud Storage</option>
                           <?php } else { ?>
                              <option value="1">Server Storage</option>
                              <option value="0" selected>Cloud Storage</option>
                           <?php } ?>
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
                  <h5 class="font-weight-bold text-primary my-0">Genre</h5>
               </div>
               <div class="card-body pl-5 py-1">
                  <?php
                  $genreRegEx = strtolower($comic["comic_genre"]);
                  $arrayGenre = explode(",", $genreRegEx);
                  $arrGenreRegEx = [];
                  foreach ($arrayGenre as $assGenre) {
                     $arrGenreRegEx[] .= "(" . $assGenre . ")";
                  }
                  $genreRegEx = implode("|", $arrGenreRegEx);
                  // var_dump($genreRegEx);
                  $regEx = "/^($genreRegEx)*$/";
                  // var_dump($regEx);die;
                  ?>
                  <div class="row no-gutters py-2">
                     <?php foreach ($genres as $genre) : ?>

                        <?php if (preg_match($regEx, strtolower($genre["genre"]))) { ?>
                           <div class="col-md-6">
                              <div class="form-check">
                                 <input class="form-check-input" name="<?= strtolower($genre["name"]); ?>" type="checkbox" value="<?= strtolower($genre["genre"]); ?>" id="<?= strtolower($genre["genre"]); ?>" checked>
                                 <label class="form-check-label" for="<?= strtolower($genre["genre"]); ?>">
                                    <?= $genre["genre"] ?>
                                 </label>
                              </div>
                           </div>
                        <?php } else { ?>
                           <div class="col-md-6">
                              <div class="form-check">
                                 <input class="form-check-input" name="<?= strtolower($genre["name"]); ?>" type="checkbox" value="<?= strtolower($genre["genre"]); ?>" id="<?= strtolower($genre["genre"]); ?>">
                                 <label class="form-check-label" for="<?= strtolower($genre["genre"]); ?>">
                                    <?= $genre["genre"] ?>
                                 </label>
                              </div>
                           </div>
                        <?php } ?>

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

   </form>

</div>


<!-- Modal -->
<div class="modal fade" id="modal-choose" tabindex="-1" role="dialog" aria-labelledby="modals-title">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="modals-title">Choose Upload Method</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span>&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <a href="<?= base_url("chapter/add-new-chapter/" . $comic["comic_slug"]) ?>" class="btn btn-primary btn-block">Add New Chapter</a>
            <a href="<?= base_url("chapter/scrape-new-chapter/" . $comic["comic_slug"]) . "/" . $total_chapters ?>" class="btn btn-primary btn-block" id="btn-scrape" data-totals="<?= $total_chapters ?>" data-komikname="<?= $comic["comic_slug"] ?>">Scrape Comic Chapters</a>
         </div>
      </div>
   </div>
</div>

<script>
   // ------------------------------------------- Input Checkbox Option -------------------------------------------
   // --------- init ---------
   const checkbox = document.getElementsByClassName("checkbox")
   const checkAll = document.getElementById("check-all")
   const total_checkbox = checkbox.length;
   let is_checked_all = false
   let currently_checked = 0
   let data = [];


   // Check All
   function check_all(check_all) {
      data = []
      for (let i = 0; i < total_checkbox; i++) {
         _check_current_box(checkbox[i])
      }
   }

   function _check_current_box(current_checkbox) {
      // current_checkbox.checked = !current_checkbox.checked
      let arr = current_checkbox.value
      let index = $(current_checkbox).data("index")
      if (checkAll.checked) {
         _show_selected_options(true)
         current_checkbox.checked = true
         currently_checked++
         data[index] = current_checkbox.value
      } else {
         _show_selected_options(false)
         current_checkbox.checked = false
         currently_checked = 0
         data = []
      }
   }

   // Single Checked
   function check_clicked_box(current_checkbox) {
      let arr = current_checkbox.value
      let index = $(current_checkbox).data("index")
      // check jika tercek maka `currently_checked` ditambah 1 sebaliknya akan dikurang 1
      if (current_checkbox.checked) {
         currently_checked++
         data[index] = current_checkbox.value
      } else {
         currently_checked--
         delete data[index]
         console.log(data[index])
         console.log(data.length)
      }
      if (currently_checked > 0 && currently_checked < total_checkbox) {
         _show_selected_options(true) // tampilkan menu
         checkAll.checked = false
         is_checked_all = false
      } else if (currently_checked == total_checkbox) {
         // Di eksekusi ketika semua checkbox ter centang
         is_checked_all = true
         checkAll.checked = true
      } else {
         // Di eksekusi ketika tidak ada checkbox yang ter centang
         _show_selected_options(false) // sembunyikan menu
         // currently_checked = 0
      }
   }



   function _show_selected_options(show) {
      if (show) {
         $("#footer-menu").removeClass("d-none")
         $("#my-form").attr("action", `${base_url}chapter/download_selected_chapter/`)
         $("#btn-comic-submit").attr("disabled", "")
      } else {
         $("#footer-menu").addClass("d-none")
         $("#my-form").attr("action", ``)
         $("#btn-comic-submit").removeAttr("disabled")
      }
   }

   $("#footer-menu button.btn-danger").on("click", function() {
      if (!confirm("Yakin!, Mau Dihapus?")) return false
      data.forEach((data) => {
         let data_arr = data.split("|");
         let comic_name = data_arr.shift()
         let comic_id = data_arr.pop()
         $.get(`${base_url}chapter/delete-chapter/${comic_name}/${comic_id}`, (data, status) => deleteCallback(data, status, comic_id)).then((_) => {
            window.document.location.reload()
         })

      })
   })

   function deleteCallback(data, status, comic_id) {
      console.log(data)
      if (status == "success") {
         currently_checked--
         $("#info-chapters").html(`| totals chapters ${data}`);
         $(`#${comic_id}`).remove()
         _show_selected_options(false)
         checkAll.checked = false
         data = [];
      } else {
         _show_selected_options(true)
         console.log(`Error ${data}`);
      }
   }
</script>




<script>
   //--Simulasikan Input File Di Klik Ketiak Tombol Choose Di Tekan
   $("#btn-image-choose").on("click", function() {
      $("#input-image").click();
   });
   //--Tampilkan Gambar Ketika Telah Dipilih
   $("#input-image").on("change", function() {
      _previewImage();
   });

   //--Fungsi Tampilkan Gambar
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
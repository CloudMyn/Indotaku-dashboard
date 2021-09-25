<div class="container-fluid">
   <!-- Page Heading -->
   <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Add New Chapters</h1>

      <a href="<?= base_url("comic/update-comic/" . $comic_slug) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-arrow-left fa-sm text-white-50"></i> Back</a>
   </div>


   <form action="" method="post" enctype="multipart/form-data">
      <div class="row">

         <div class="col-8">
            <div class="row">

               <div class="col-12 mb-2">
                  <div class="card shadow">
                     <div class="card-header">
                        <h5 class="my-0 text-primary font-weight-bold">Upload Image</h5>
                     </div>
                     <div class="card-body" id="upload-card">
                        <?= $this->session->flashdata("message") ?>
                        <?= form_error("chapter_name") ?>
                        <div class="py-0 my-0">
                           <input type="file" class="btn border col-12 m-0" id="input-image" name="images[]" multiple>
                        </div>
                     </div>
                     <div class="card-footer text-right py-2">
                        <!-- <button type="button" class="btn btn-primary py-1" id="btn-add">Add</button> -->
                     </div>
                  </div>
               </div>
               <!-- Column End  -->

               <div class="col-12 mb-2">
                  <div class="card shadow">
                     <div class="card-header">
                        <h5 class="my-0 text-primary font-weight-bold">Previw Image Choosed</h5>
                     </div>
                     <div class="card-body" id="img-preview">

                     </div>
                     <div class="card-footer text-right py-2">
                        <!-- <button type="button" class="btn btn-primary py-1" id="btn-add">Add</button> -->
                     </div>
                  </div>
               </div>

            </div>
            <!-- Row End  -->
         </div>


         <?php

         $arr = explode(" ", $latest_chapter["chapter_name"]);
         $next_chapter = intval(end($arr)) + 1;
         if ($next_chapter === 1) {
            $x = explode("-", $arr[0]);
            $title   =  join(" ", $x);
         } else {
            $title = $latest_chapter["chapter_name"];
         }
         ?>

         <div class="col-4">
            <div class="row">
               <div class="col">
                  <div class="card shadow">
                     <div class="card-header">
                        <h5 class="my-0 text-primary font-weight-bold"><?= $title ?></h5>
                     </div>
                     <div class="card-body" id="view-images-card">
                        <div class="form-group py-0 my-0">
                           <label for="chapter">Chapter</label>
                           <input type="text" class="form-control" value="<?= $next_chapter ?>" name="chapter_name" id="chapter" placeholder="chapter" autocomplete="off">
                        </div>
                     </div>
                     <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary btn-block" id="btn-upload">Upload</button>
                     </div>
                  </div>
               </div>
            </div>
         </div>


      </div>
   </form>


</div>


<script>
   //AJAX
   let dataimages = [];


   // Script-Upload
   // $('#btn-choose-script').on('click', () => {
   //    $("#script-input").click();
   // });
   $("#input-image").on("change", function() {
      $("#img-preview").html("");
      let inputFile = document.getElementById("input-image");
      for (let i = 0; i < inputFile.files.length; i++) {
         let file = inputFile.files[i];
         let url = URL.createObjectURL(file);
         let allowedExtens = /(\jpg|\jpeg|\png|\gif)/;
         let fileExtens = file.name.split('.').pop().toLowerCase();
         if (file.type.split('/').shift() != "image") {
            alert("We Only Receive Image File");
            return;
         } else if (!allowedExtens.exec(fileExtens)) {
            alert("We Only Receive .jpg | .jpeg | .png | .gif");
            return;
         } else if (file.size >= 3048000) {
            alert("The File Is Too Large Max-Size Is 3mb");
            return;
         }
         $("#img-preview").append(`<img src="" alt="" id="image-view-${i}" width="100%">`);
         // $('#file-name').val(`name: ${inputFile.files[i].name}`);
         $(`#image-view-${i}`).attr('src', url);
         dataimages.push(inputFile.files[i]);
      }
      // console.log(dataimages);
   });
</script>
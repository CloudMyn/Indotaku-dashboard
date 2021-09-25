<div class="container-fluid">
   <!-- Page Heading -->
   <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800"><?= $chapter["chapter_name"] ?></h1>

      <a href="<?= base_url("comic/update-comic/" . $chapter["comic_slug"]) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-arrow-left fa-sm text-white-50"></i> Back</a>
   </div>

   <div class="row justify-content-center">
      <div class="card col-10 px-0">
         <form action="" method="post">
            <div class="card-header">
               <div class="row justify-content-end">
                  <div class="col-md-10">
                     <h5 class="my-0 text-primary font-weight-bold">Chapter View</h5>
                  </div>
                  <div class="col-md-2 text-right">
                     <button type="submit" class="badge badge-success" id="btn-download"><i class="fa fa-download" aria-hidden="true"></i> Download</button>
                  </div>
               </div>
            </div>
            <div class="card-body row justify-content-center">
               <input type="hidden" value="<?= $chapter["chapter_name"] ?>" name="current_chapter">
               <?php foreach ($images as $image) : ?>
                  <?php
                  // var_dump($images);
                  if (preg_match("/(https:|http:)+/", $image) == 1) {
                     $image = $image;
                  } else {
                     $image = base_url($image);
                  }
                  ?>
                  <div class="col-md-10">
                     <input type="hidden" name="images[]" value="<?= $image ?>">
                     <img src="<?= $image ?>" alt="Image" width="100%">
                  </div>
               <?php endforeach ?>
            </div>
         </form>
      </div>
   </div>

</div>


<script>
   console.log("HAllo");
   $(this).on("keyDown", () => {
      console.log('hallo');
   });

   $("#btn-image").on("click", function() {
      let file = $("#image-blob").html()
      let url = "http://localhost/komikins/assets/image/komik-chapters/The_Begining_After_The_End/Chapter-1/";
   })


   $("#btn-download -").on("click", function() {
      for (let i = 0; i < $(".btn-dwd").length; i++) {
         $(".btn-dwd")[i].click();
         console.log("-count-");
      }
   })
</script>
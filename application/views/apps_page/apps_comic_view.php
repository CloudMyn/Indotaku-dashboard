<div class="container-fluid">

   <style>
      .items {
         border: 2px dashed lightslategray;
         text-align: center;
         height: 200px;
         border-radius: 5px;
         cursor: pointer;
         background: rgba(0, 0, 0, 0);
         transition-duration: 0.4s;
      }

      .items:hover {
         background: rgba(0, 0, 0, 0.1);
      }

      .items-icon {
         line-height: 200px;
      }

      .rec {
         padding: 0;
         background-color: red;
         width: 100%;
         max-width: 100%;
         overflow: hidden;
         border-radius: 5px;
      }

      .rec>img {
         width: 100%;
         max-height: 200px;
      }

      .rec-title {
         margin: 0px 5px;
         color: whitesmoke;
         text-shadow: 3px 1px 10px black;
         position: absolute;
         bottom: 0;
      }

      .rec-title>h5 {
         font-weight: 500;
         font-size: 100%;
         width: inherit;
      }

      .scroll-horizotal {
         flex-wrap: nowrap;
         overflow-x: auto;
         overflow-y: hidden;
         padding-bottom: 10px;
      }
   </style>

   <div class="card shadow">
      <div class="card-header">
         <h5 class="text-primary title-color font-weight-bolder">Comics Recomendation</h5>
      </div>
      <div class="card-body">
         <div class="row scroll-horizotal">
            <div class="col-2">
               <div class="items">
                  <i class="fa fa-plus fa-4x items-icon" aria-hidden="true"></i>
               </div>
            </div>
            <?php foreach ($comics_rec as $comic_rec) : ?>
               <a href="<?= base_url("comic/updateComic/" . $comic_rec["komik_name"]) ?>" target="blank" class="col-2">
                  <div class="rec">
                     <img src="<?= base_url() ?>assets/image/komik/<?= $comic_rec['cover'] ?>">
                     <div class="rec-title">
                        <h5><?= $comic_rec["name"] ?></h5>
                     </div>
                  </div>
               </a>
            <?php endforeach; ?>
         </div>
      </div>
   </div>


   <div class="card shadow mt-4">
      <div class="card-header">
         <h5 class="text-primary title-color font-weight-bolder">Comics New</h5>
      </div>
      <div class="card-body">
         <div class="row scroll-horizotal">
            <div class="col-2">
               <div class="items">
                  <i class="fa fa-plus fa-4x items-icon" aria-hidden="true"></i>
               </div>
            </div>
            <?php foreach ($comics_new as $comic_new) : ?>
               <a href="<?= base_url("comic/updateComic/" . $comic_new["komik_name"]) ?>" target="blank" class="col-2">
                  <div class="rec">
                     <img src="<?= base_url() ?>assets/image/komik/<?= $comic_new['cover'] ?>">
                     <div class="rec-title">
                        <h5><?= $comic_new["name"] ?></h5>
                     </div>
                  </div>
               </a>
            <?php endforeach; ?>
         </div>
      </div>
   </div>




   <div class="card shadow mt-4">
      <div class="card-header">
         <h5 class="text-primary title-color font-weight-bolder">Comics Popular</h5>
      </div>
      <div class="card-body">
         <div class="row scroll-horizotal">
            <div class="col-2">
               <div class="items">
                  <i class="fa fa-plus fa-4x items-icon" aria-hidden="true"></i>
               </div>
            </div>
            <?php foreach ($comics_popular as $comic_popular) : ?>
               <a href="<?= base_url("comic/updateComic/" . $comic_popular["komik_name"]) ?>" target="blank" class="col-2">
                  <div class="rec">
                     <img src="<?= base_url() ?>assets/image/komik/<?= $comic_popular['cover'] ?>">
                     <div class="rec-title">
                        <h5><?= $comic_popular["name"] ?></h5>
                     </div>
                  </div>
               </a>
            <?php endforeach; ?>
         </div>
      </div>
   </div>


</div>
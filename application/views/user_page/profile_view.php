<div class="container-fluid">


   <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">My Profiles</h1>
      <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
   </div>

   <!-- Card Horizontal -->
   <div class="card mb-0" style="max-width: 600px">
      <div class="row no-gutters">
         <div class="col-md-4">
            <img src="<?= base_url("Assets/image/user-profile/".$user_data["image"])?>" alt="" class="card-img">
         </div>
         <div class="col-md-8">
            <div class="card-body">
               <h5 class="card-title text-capitalize"><?= $user_data["fullname"]?></h5>
               <p class="card-text font-weight-bold"><?= $user_data["email"]?></p>
               <hr class="mt-5">
               <p class="card-text"><small class="text-muted">Member-Since- <?= date("d  M  Y",$user_data["date_created"])?></small></p>
            </div>
         </div>
      </div>
   </div>

</div>
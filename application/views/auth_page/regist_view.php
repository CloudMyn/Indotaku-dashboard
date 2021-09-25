<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="description" content="This Web Template Create By Bootstrap">
   <meta name="author" content=ByTeamKomikINx>

   <title><?= $title ?></title>

   <!-- Custom fonts for this template-->
   <link href="<?= base_url("assets/font-awesome/css") ?>font-awesome.min.css" rel="stylesheet" type="text/css">

   <!-- Custom styles for this template-->
   <link href="<?= base_url("assets/") ?>css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">

   <div class="container">
      <div class="row justify-content-center">
         <div class="card o-hidden border-0 shadow-lg my-5 col-lg-7">
            <div class="card-body p-0">
               <!-- Nested Row within Card Body -->
               <div class="row">
                  <div class="col-lg">
                     <div class="p-5">
                        <div class="text-center">
                           <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                        </div>
                        <form class="user" method="post" action="<?= base_url("auth/registration") ?>">
                           <div class="form-group">
                              <input autocomplete="off" type="input" class="form-control form-control-user" id="fullname" name="fullname" placeholder="Full Name" value="<?= set_value('fullname')?>">
                              <?= form_error("fullname") ?>
                           </div>
                           <div class="form-group">
                              <input autocomplete="off" type="input" class="form-control form-control-user" id="username" name="username" placeholder="Username"value="<?= set_value('username')?>">
                              <?= form_error("username") ?>
                           </div>
                           <div class="form-group">
                              <input autocomplete="off" type="text" class="form-control form-control-user" id="email" name="email" placeholder="Email Address" value="<?= set_value('email')?>">
                              <?= form_error("email") ?>
                           </div>
                           <div class="form-group">
                              <input autocomplete="off" type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password">
                              <?= form_error("password") ?>
                           </div>
                           <div class="form-group">
                              <input autocomplete="off" type="password" class="form-control form-control-user" id="repeatpass" name="rPassword" placeholder="Repeat Password">
                              <?= form_error("password") ?>
                           </div>
                           <button type="submit" class="btn btn-primary btn-user btn-block">
                              Register Account
                           </button>
                        </form>
                        <hr>
                        <div class="text-center">
                           <a class="small" href="#">Forgot Password?</a>
                        </div>
                        <div class="text-center">
                           <a class="small" href="<?= base_url("auth") ?>">Already have an account? Login!</a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

   </div>

   <!-- Bootstrap core JavaScript-->
   <script src="<?= base_url("assets/bootstrap/js/jquery-3.4.1.min.js") ?>"></script>
   <script src="<?= base_url("assets/bootstrap/js/bootstrap.bundle.min.js") ?>"></script>

   <!-- Core plugin JavaScript-->
   <script src="<?= base_url("assets/jquery-easing/") ?>jquery.easing.min.js"></script>

   <!-- Custom scripts for all pages-->
   <script src="<?= base_url("assets/") ?>js/sb-admin-2.min.js"></script>

</body>

</html>
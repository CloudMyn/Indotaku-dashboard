  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
     <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog" role="document">
        <div class="modal-content">
           <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                 <span aria-hidden="true">×</span>
              </button>
           </div>
           <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
           <div class="modal-footer">
              <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
              <a class="btn btn-primary" href="<?= base_url("auth/logout") ?>">Logout</a>
           </div>
        </div>
     </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="<?= base_url("assets/bootstrap/js/bootstrap.bundle.min.js") ?>"></script>

  <!-- Core plugin JavaScript-->
  <script src="<?= base_url("assets/jquery-easing/") ?>jquery.easing.min.js"></script>
  
  <!-- Custom scripts for all pages-->
  <script src="<?= base_url("assets/") ?>js/sb-admin-2.min.js"></script>

  <!-- MY JavaScript-->
  <script src="<?= base_url("assets/js/main.js") ?>"></script>

  <script>
     let isActive = false;
     //   let toggleButton = document.getElementById("menu");
     $("#editMode").on("click", function() {
        if (isActive == false) {
           isActive = true;
           $("#btn-submit").attr("type", "submit");
           $("#btn-submit").removeClass("disabled");
           $(".user-input").removeAttr("disabled");
        } else {
           isActive = false;
           $("#btn-submit").removeAttr("type");
           $("#btn-submit").addClass("disabled");
           $(".user-input").attr("disabled", "on");
        }

     });
  </script>

  </body>

  </html>
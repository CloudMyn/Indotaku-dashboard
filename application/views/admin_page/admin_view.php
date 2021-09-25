<!-- Begin Page Content -->
<div class="container-fluid">

   <!-- Page Heading -->
   <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
      <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
   </div>

   <div class="row">

      <div class="col-md-3">
         <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
               <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                     <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Earnings (Monthly)</div>
                     <div class="h5 mb-0 font-weight-bold text-gray-800">$40,000</div>
                  </div>
                  <div class="col-auto">
                     <i class="fas fa-calendar fa-2x text-gray-300"></i>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-3">
         <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
               <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                     <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Users</div>
                     <div class="h5 mb-0 font-weight-bold text-gray-800">23,853</div>
                  </div>
                  <div class="col-auto">
                     <i class="fa fa-users fa-2x text-gray-300"></i>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!-- Earnings (Monthly) Card Example -->
      <div class="col-md-3">
         <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
               <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                     <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Free Space</div>
                     <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                           <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php 
                           $this->load->helper("number");
                           $free_space = disk_free_space(FCPATH);
                           $total_space = disk_total_space(FCPATH);
                           echo byte_format($free_space);
                           ?></div>
                        </div>
                        <!-- <div class="col">
                           <div class="progress progress-sm mr-2">
                              <div class="progress-bar bg-info" role="progressbar" style="width: 60%; max-width: 70%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                           </div>
                        </div> -->
                     </div>
                  </div>
                  <div class="col-auto">
                     <i class="fa fa-database fa-2x text-gray-300" aria-hidden="true"></i>
                  </div>
               </div>
            </div>
         </div>
      </div>


   </div>
   <!-- Row End -->


</div>
<!-- /.container-fluid -->
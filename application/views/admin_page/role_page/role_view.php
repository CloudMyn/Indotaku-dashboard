<!-- Container Fluid -->
<div class="container-fluid">
	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Role</h1>
	</div>

	<div class="row">
		<div class="col-6">
			<!-- Start Card -->
			<div class="card shadow-sm mb-4">
				<div class="card-header">
					<h5 class="m-0 font-weight-bold text-primary col-5">User Menu</h5>
				</div>
				<div class="card-body py-0 pt-3">
					<table class="table table-striped text-center">
						<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col">Role Name</th>
								<th scope="col">is Active</th>
								<th scope="col">User Access</th>
								<th scope="col">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php $id = 1; ?>
							<?php foreach ($roles as $role) : ?>
								<tr>
									<th scope="row"><?= $id++ ?></th>
									<td><?= $role["role_name"]; ?></td>
									<td>
										<?php if ($role["is_active"] == 1) : ?>
											<span class="badge badge-info">Active</span>
										<?php else : ?>
											<span class="badge badge-danger">Disbled</span>
										<?php endif; ?>
									</td>
									<td>
										<a href="<?= base_url("admin/roleMenu")?>" class="badge badge-warning"><i class="fa fa-key"></i> Access Key</a>
									</td>
									<td>
										<a href="#" class="badge badge-success"><i class="fa fa-pencil" aria-hidden="true"></i> Update</a>
									</td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
				<div class="card-footer">

				</div>
			</div>
			<!-- End Card -->
		</div>
		<!-- End Col -->
	</div>
</div>


<script>
	let numb = "<?= disk_total_space(FCPATH) / 1000 ?>";
	numb = parseInt(numb);
</script>
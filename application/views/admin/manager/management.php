<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content  mt-5" id="result">
	<div class="container-fluid mt-5">
		<!-- Start Page Content -->
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<div class="d-flex mb-3" style="justify-content: space-between">
							<h4 class="card-title"><?=$this->lang->line('manager_management')?></h4>
						</div>

                        <?php if (isset($_SESSION['success_manager'])):?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?=$_SESSION['success_manager']?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['success_manager']); endif; ?>

						<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
							<thead>
							<tr>
								<th><?=$this->lang->line('sr_no')?></th>
								<th><?=$this->lang->line('name')?></th>
								<th><?=$this->lang->line('email')?></th>
								<th><?=$this->lang->line('status')?></th>
								<th><?=$this->lang->line('role')?></th>
								<th><?=$this->lang->line('joining_date')?></th>
								<th><?=$this->lang->line('action')?></th>
							</tr>

							</thead>
							<tbody>
							<?php if (isset($dataItem)){
								foreach ($dataItem as $key=>$data):
								?>
								<tr>
									<td><?=++$key?></td>
									<td><?=$data->first_name.' '.$data->last_name?></td>
									<td><?=$data->email?></td>
									<td><span class="badge rounded-pill bg-success">Active</span></td>
									<td><span class="badge rounded-pill bg-info"><?=$data->role_name?></span></td>
									<td><?php echo $data->created_datetime?></td>
									<td>
										<a class="btn btn-outline-secondary btn-sm edit" title="User View" href="<?php echo base_url(); ?>admin/manager/manager-management/asin-permission/<?=$data->unique_id?>">
											<i class="fas fa-user-lock"></i> <?=$this->lang->line('assign_permission')?>
										</a>
									</td>
								</tr>
							<?php endforeach; } ?>
							</tbody>
						</table>

					</div>
				</div>
			</div> <!-- end col -->
		</div> <!-- end row -->
		<!-- End PAge Content -->
	</div>
</div>
<!-- end main content-->

<!--  Modal content for the above example -->
<div class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="myExtraLargeModalLabel">Add New Manager</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form class="needs-validation" method="post" action="<?php echo base_url() ?>update-user-info" novalidate>
					<div class="row">
						<div class="col-md-12">
							<div class="mb-3">
								<label for="validationCustom01" class="form-label">Full Name</label>
								<input type="text" class="form-control" id="validationCustom01" placeholder="Full Name" value="" name="full_name" required>
							</div>
						</div>
						<div class="col-md-12">
							<div class="mb-3">
								<label for="validationCustom01" class="form-label">Email</label>
								<input type="email" class="form-control" id="validationCustom02" placeholder="example@gmail.com" value="" name="email" required>
							</div>
						</div>
						<div class="col-md-12">
							<div class="mb-3">
								<label for="validationCustom03" class="form-label">Password</label>
								<input type="password" class="form-control" id="validationCustom03" placeholder="*********" value="" name="password" required>
							</div>
						</div>
						<div class="col-md-12">
							<div class="mb-3">
								<label for="validationCustom03" class="form-label">Retype Password</label>
								<input type="password" class="form-control" id="validationCustom03" placeholder="*********" value="" name="password" required>
							</div>
						</div>
					</div>

					<div>
						<button class="btn btn-primary" type="submit">Create Manager</button>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

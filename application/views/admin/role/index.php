<style>
	.rounded-pill {
		padding-right: 2.6em!important;
		padding-left: 2.6em!important;
		padding-top: 6px!important;
		padding-bottom: 4px!important;
	}
	.document-table,.document-table th,.document-table td {
		border: 2px solid gray!important;
	}
</style>
<div class="main-content" id="result">
	<div class="page-content">
		<div class="container-fluid">

			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">
							<?php if (isset($_SESSION['success_role'])):?>
								<div class="alert alert-success alert-dismissible fade show" role="alert">
									<?=$_SESSION['success_role']?>
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
								</div>
								<?php unset($_SESSION['success_role']); endif; ?>
							<h4 class="card-title mb-4"><?= $this->lang->line('role_listing') ?></h4>
							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th><?= $this->lang->line('sr') ?></th>
									<th><?= $this->lang->line('role_name') ?></th>
									<th><?= $this->lang->line('create_date') ?></th>
									<th><?= $this->lang->line('status') ?></th>
									<th><?= $this->lang->line('action') ?></th>
								</tr>
								</thead>
								<tbody>

								<?php if (isset($dataItem) && !empty($dataItem)){
									foreach ($dataItem as $key=>$item):
										?>
										<tr>
											<td><?=++$key?></td>
											<td><?=$item->role_name?></td>
											<td><?=$item->created_at?></td>
											<td>
												<?php if ($item->status==1): ?>
													<span class="badge rounded-pill bg-success">Active</span>
												<?php else: ?>
													<span class="badge rounded-pill bg-danger">In Active</span>
												<?php endif; ?>
											</td>
											<td>
												<a class="btn btn-outline-secondary btn-sm edit" title="Edit" href="<?php echo base_url(); ?>admin/role/edit-role/<?=$item->role_id?>">
													<i class=" fas fa-edit"></i>
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
		</div>

	</div>
	<!-- End Page-content -->
</div>
<!-- end main content-->

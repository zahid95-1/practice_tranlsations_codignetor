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
							<?php if (isset($_SESSION['success_group'])):?>
								<div class="alert alert-success alert-dismissible fade show" role="alert">
									<?=$_SESSION['success_group']?>
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
								</div>
								<?php unset($_SESSION['success_group']); endif; ?>
							<h4 class="card-title mb-4"><?=$this->lang->line('internal_transfer_history')?></h4>

							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th><?=$this->lang->line('sr_no')?></th>
									<th><?=$this->lang->line('email')?></th>
									<th><?=$this->lang->line('name')?></th>
									<th><?=$this->lang->line('from_account')?></th>
									<th><?=$this->lang->line('to_account')?></th>
									<th><?=$this->lang->line('amount')?></th>
									<th><?=$this->lang->line('created_at')?></th>
									<th><?=$this->lang->line('status')?></th>
								</tr>
								</thead>
								<tbody>

								<?php if (isset($dataItem) && $dataItem){
									foreach ($dataItem as $key=>$item):
										?>
										<tr>
											<td><?=++$key?></td>
											<td><?=$item->email?></td>
											<td><?=$item->first_name.' '.$item->last_name?></td>
											<td><?=$item->to_account?></td>
											<td><?=$item->from_account?></td>
											<td>$<?=$item->transfer_amount?></td>
											<td><?=$item->created_at?></td>
											<td>
												<?php if ($item->status==1): ?>
													<span class="badge rounded-pill bg-success">Approved</span>
												<?php elseif($item->status==0): ?>
													<span class="badge rounded-pill bg-dark">Pending</span>
												<?php endif; ?>
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

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
							<h4 class="card-title mb-4"><?=$this->lang->line('commission_transfer_history')?></h4>
							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th><?=$this->lang->line('sr_no')?></th>
									<th><?=$this->lang->line('date')?></th>
									<th><?=$this->lang->line('user_id')?></th>
									<th><?=$this->lang->line('email')?></th>
									<th><?=$this->lang->line('ib_name')?></th>
									<th><?=$this->lang->line('from_account')?></th>
									<th><?=$this->lang->line('to_account')?></th>
									<th><?=$this->lang->line('status')?></th>
									<th><?=$this->lang->line('action')?></th>
								</tr>
								</thead>
								<tbody>
								<?php
								if (isset($dataItem) && $dataItem){
									foreach ($dataItem as $key=>$item):
										?>
										<tr>
											<td><?=++$key?></td>
											<td><?=date('Y-m-d H:s',strtotime($item->created_at))?></td>
											<td><?=$item->unique_id?></td>
											<td><?=$item->email?></td>
											<td><?=$item->first_name.' '.$item->last_name?></td>
											<td><?=$item->from_account?></td>
											<td><?=$item->to_account?></td>
											<td>
												<?php if ($item->status==0): ?>
													<span class="badge rounded-pill bg-danger">Pending</span>
												<?php elseif($item->status==1): ?>
													<span class="badge rounded-pill bg-success">Approved</span>
												<?php elseif($item->status==2): ?>
													<span class="badge rounded-pill bg-danger">Rejected</span>
												<?php endif; ?>
											</td>
											<td>
												<a class="btn btn-outline-secondary btn-sm edit" title="User View" href="<?=base_url()?>commission-transfer-details/<?=$item->id?>">
													<i class="fas fa-eye"></i> View Details
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

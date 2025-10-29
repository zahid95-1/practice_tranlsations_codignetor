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
							<h4 class="card-title mb-4">Ib Commission Withdraw History</h4>
							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th>Sr.No</th>
									<th>Bank Ac No/Coin:Address</th>
									<th>MT5 Account ID</th>
									<th>Amount</th>
									<th>Create At</th>
									<th>Admin Note</th>
									<th>Status</th>
								</tr>
								</thead>
								<tbody>

								<?php if (isset($dataItem) && $dataItem){
									foreach ($dataItem as $key=>$item):
										?>
										<tr>
											<td><?=++$key?></td>
											<?php if ($item->withdrawal_type==1):?>
											<td><?=$item->account_number?></td>
											<?php else: ?>
											<td><?=$item->coin_name.': '.$item->wallet_address?></td>
											<?php endif; ?>
											<?php if ($item->ib_withdraw_status==1):?>
												<td style="color: #1cbb8c!important;font-weight: 600"><?=$item->mt5_login_id?>(<span>IB</span>)</td>
											<?php else:?>
												<td style="color: #343a40!important;font-weight: 600"><?=$item->mt5_login_id?></td>
											<?php endif; ?>
											<td>$<?=$item->requested_amount?></td>
											<td><?=date('Y-m-d H:s',strtotime($item->requested_datetime))?></td>
											<td><?=$item->admin_remark?></td>
											<td>
												<?php if ($item->status==1): ?>
													<span class="badge rounded-pill bg-dark">Requested</span>
												<?php elseif($item->status==2): ?>
													<span class="badge rounded-pill bg-success">Paid</span>
												<?php elseif($item->status==3): ?>
													<span class="badge rounded-pill bg-danger">Rejected</span>
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

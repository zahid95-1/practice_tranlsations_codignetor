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
							<h4 class="card-title mb-4"><?= lang('withdraw_history') ?></h4>
							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th><?= lang('sr_no') ?></th>
									<th><?= lang('bank_ac_no_address') ?></th>
									<th><?= lang('mt5_account_id') ?></th>
									<th><?= lang('amount_usd') ?></th>
									<th><?= lang('create_at') ?></th>
									<th><?= lang('admin_note') ?></th>
									<th><?= lang('status') ?></th>
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
										   <?php elseif ($item->withdrawal_type==3):?>
												<td>Cash Payment</td>
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
													<span class="badge rounded-pill bg-dark"><?= lang('requested') ?></span>
												<?php elseif($item->status==2): ?>
													<span class="badge rounded-pill bg-success"><?= lang('paid') ?></span>
												<?php elseif($item->status==3): ?>
													<span class="badge rounded-pill bg-danger"><?= lang('rejected') ?></span>
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

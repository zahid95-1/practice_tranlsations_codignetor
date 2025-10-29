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
							<h4 class="card-title mb-4"><?= $this->lang->line('requested_withdraw_history') ?></h4>

							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th><?= $this->lang->line('sr_no') ?></th>
									<th><?= $this->lang->line('user_id') ?></th>
									<th><?= $this->lang->line('name') ?></th>
									<th><?= $this->lang->line('email') ?></th>
									<th><?= $this->lang->line('mt5_id') ?></th>
									<th><?= $this->lang->line('bank_ac_coin') ?></th>
									<th><?= $this->lang->line('bank_ac_no_address') ?></th>
									<th><?= $this->lang->line('amount') ?></th>
									<th><?= $this->lang->line('request_date') ?></th>
									<th><?= $this->lang->line('user_note') ?></th>
									<th><?= $this->lang->line('status') ?></th>
									<th><?= $this->lang->line('action') ?></th>
								</tr>
								</thead>
								<tbody>

								<?php if (isset($dataItem) && $dataItem){
									foreach ($dataItem as $key=>$item):
										?>
										<tr>
											<td><?=++$key?></td>
											<td><?=$item->unique_id?></td>
											<td><?=$item->first_name.' '.$item->last_name?></td>
											<td><?=$item->email?></td>
											<?php if ($item->ib_withdraw_status==1):?>
												<td style="color: #1cbb8c!important;font-weight: 600"><?=$item->mt5_login_id?>(<span>IB</span>)</td>
											<?php else:?>
												<td style="color: #343a40!important;font-weight: 600"><?=$item->mt5_login_id?></td>
											<?php endif; ?>
											<?php if ($item->withdrawal_type==1):?>
											<td><?=$item->account_name?></td>
											<td><?=$item->account_number?></td>
											<?php elseif ($item->withdrawal_type==3):?>
												<td>Cash Payment</td>
											<?php else: ?>
											<td><?=$item->coin_name?></td>
											<td><?=$item->wallet_address?></td>
											<?php endif; ?>
											<td>$<?=$item->requested_amount?></td>
											<td><?=date('Y-m-d H:s',strtotime($item->requested_datetime))?></td>
											<td><?=$item->user_remark?></td>
											<td>
												<?php if ($item->status==1): ?>
													<span class="badge rounded-pill bg-dark">Requested</span>
												<?php elseif($item->status==2): ?>
													<span class="badge rounded-pill bg-success">Paid</span>
												<?php endif; ?>
											</td>
											<td>
												<a class="btn btn-outline-secondary btn-sm edit" title="User View" href="<?=base_url()?>user-single-withdraw-item-details/<?=$item->id?>">
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

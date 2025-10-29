<?php
$getRateSettings = $this->PaymentModel->getRateSettings();
?>
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
							<h4 class="card-title mb-4"><?= $this->lang->line('approve_withdraw_history') ?></h4>
							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th><?= $this->lang->line('sr_no') ?></th>
									<th><?= $this->lang->line('user_id') ?></th>
									<th><?= $this->lang->line('email') ?></th>
									<th><?= $this->lang->line('name') ?></th>
									<th><?= $this->lang->line('mt5_id') ?></th>
									<th><?= $this->lang->line('bank_account_name') ?></th>
									<th><?= $this->lang->line('bank_account_number') ?></th>
									<th><?= $this->lang->line('amount') ?></th>
									<?php if (ConfigData['enable_deposit_withdraw_rate']): ?>
										<th><?= $this->lang->line('converted_amount') ?></th>
									<?php endif; ?>
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
											<td><?=($item->withdrawal_type==3)?'Cash Payment':$item->account_name?></td>
											<td><?=$item->account_number?></td>
											<td>$<?=$item->requested_amount?></td>
											<?php if (ConfigData['enable_deposit_withdraw_rate'] && $item->account_type_status==2 || $item->account_type_status==1): ?>
											<td>
												<?php
												if ($item->account_type_status==2){
													echo $getRateSettings['symbol'].($getRateSettings['dep_with_rate']*$item->requested_amount);
												}elseif ($item->account_type_status==1){
													echo $getRateSettings['symbol'].(($item->live_rate-2)*$item->requested_amount);
												}else{
													echo '$'.$item->requested_amount;
												}
												?>
											</td>
											<?php else: ?>
											<td>$<?=$item->requested_amount?></td>
											<?php endif; ?>
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
												<a class="btn btn-outline-secondary btn-sm edit"
												   title="<?=$this->lang->line('user_view')?>"
												   href="<?=base_url()?>user-single-withdraw-item-details/<?=$item->id?>">
													<i class="fas fa-eye"></i> <?=$this->lang->line('view_details')?>
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

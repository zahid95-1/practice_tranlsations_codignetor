<?php
$userID = $_SESSION['user_id'];
$this->db->query("update payments  set user_notification = 1 where user_notification = 0 and status = 1 and user_id = $userID ");
$getSettingsModel =$this->db->query("SELECT dep_with_rate,rate_currency FROM setting")->row();
$dep_with_rate=0;
$rate_currency='USD';
$symbol='$';
if ($getSettingsModel){
	$dep_with_rate=$getSettingsModel->dep_with_rate;
	$rate_currency=$getSettingsModel->rate_currency;
	$symbol=$this->PaymentModel->get_currency_symbol($rate_currency);
}
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
                                                        <h4 class="card-title mb-4"><?= lang('deposit_history_listing') ?></h4>
							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
                                                                        <th><?= lang('sr_no') ?></th>
                                                                        <th><?= lang('mt5_account_id') ?></th>
                                                                        <th><?= lang('amount_usd') ?></th>
									<?php if (ConfigData['enable_deposit_withdraw_rate']): ?>
                                                                        <th><?= lang('converted_amount') ?></th>
									<?php endif; ?>
                                                                        <th><?= lang('payment_type') ?></th>
                                                                        <th><?= lang('create_at') ?></th>
                                                                        <th><?= lang('admin_note') ?></th>
                                                                        <th><?= lang('status') ?></th>
                                                                        <th><?= lang('attachment') ?></th>
								</tr>
								</thead>
								<tbody>

								<?php if (isset($dataItem) && $dataItem){
									foreach ($dataItem as $key=>$item):
										?>
										<tr>
											<td><?=++$key?></td>
											<td><?=$item->mt5_login_id?></td>
											<td>$<?=$item->entered_amount?></td>
										<?php if (ConfigData['enable_deposit_withdraw_rate']): ?>
											<?php if ($item->account_type_status==2): ?>
												<td><?=$symbol?><?=$item->entered_amount*$dep_with_rate?></td>
											<?php else: ?>
												<td>$<?=$item->entered_amount?></td>
											<?php endif; ?>
										<?php endif; ?>
											<td>
												<?php
												  if ($item->payment_mode==1){
        echo "<span style='color: #00CC00'>".lang('wire_transfer')."</span>";
												  }elseif ($item->payment_mode==2){
 echo "<span style='color: #00CC00'>".lang('crypto_coin')."</span>";
												  }elseif ($item->payment_mode==3){
 echo "<span style='color: #00CC00'>".lang('paypal')."</span>";
												  }elseif ($item->payment_mode==4){
 echo "<span style='color: #00CC00'>".lang('cash')."</span>";
												  }elseif ($item->payment_mode==5){
 echo "<span style='color: #00CC00'>".lang('internal_transfer')."</span>";
												  }elseif ($item->payment_mode==6){
                                                      echo "<span style='color: #00CC00'>".lang('commission_transfer')."</span>";
                                                  }elseif ($item->payment_mode==7){
 echo "<span style='color: forestgreen'>".lang('stripe')."</span>";
												  }
												?>
											</td>
											<td><?=date('Y-m-d H:s',strtotime($item->created_at))?></td>
											<td><?=$item->remarks?></td>
											<td>
												<?php if ($item->status==0): ?>
                                                                               <span class="badge rounded-pill bg-danger"><?= lang('pending') ?></span>
												<?php elseif($item->status==1 && $item->payment_mode<>2 ): ?>
                                                                               <span class="badge rounded-pill bg-success"><?= lang('approved') ?></span>
												<?php elseif($item->status==1 && $item->payment_mode==2 ): ?>
                                                                               <span class="badge rounded-pill bg-success"><?= lang('confirmed') ?></span>
												<?php elseif($item->status==2): ?>
                                                                               <span class="badge rounded-pill bg-danger"><?= lang('rejected') ?></span>
												<?php endif; ?>
											</td>
											<td>
											    <?php if ($item->payment_mode<>2){ ?>
												<a href="<?=base_url().$item->transaction_proof_attachment?>" download>
                                                                               <button class="btn-sm btn-primary "><i class="fa fa-download"></i> <?= lang('download') ?></button>
												</a>
												<?php } ?>
												<?php if ($item->payment_mode==2){ ?>
												<a href="<?=$item->gateway_url?>" target="_blank">
                                                                               <button class="btn-sm btn-primary "><i class="fa fa-link"></i> <?= lang('gateway_url') ?></button>
												</a>
												<?php } ?>
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

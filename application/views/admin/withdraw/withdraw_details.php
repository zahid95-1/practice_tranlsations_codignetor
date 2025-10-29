<?php
$errorObject=$requestData='';
if (isset($_SESSION['error_wire_transfer'])){
	$errorObject	=json_decode($_SESSION['error_wire_transfer']);
}
$getRateSettings = $this->PaymentModel->getRateSettings();
$getTradingAccount = $this->TradingAccount->getTradingAccountByMt5LoginId($dataItem->mt5_login_id);
?>

<style>
	.page-content {
		/*padding: calc(39px + 24px) calc(24px / 2) 60px calc(24px / 2) !important;*/
	}
	img.kyc_image {
		border: 1px solid #5664d2;
		box-shadow: 5px 10px 20px rgb(86 100 210 / 45%);
	}
</style>
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content" id="result">
	<div class="page-content">
		<div class="container-fluid">

			<!-- start page title -->
			<div class="row">
				<div class="col-12">
					<div class="page-title-box d-sm-flex align-items-center justify-content-between">
						<h4 class="mb-sm-0">WITHDRAW DETAILS FOR (<?=$dataItem->unique_id?>)</h4>
						<div class="page-title-right">
							<ol class="breadcrumb m-0">
								<li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
								<li class="breadcrumb-item active">With Wire Transfer</li>
							</ol>
						</div>
					</div>
				</div>
			</div>
			<!-- end page title -->

			<div class="row">
				<div class="col-lg-12">

					<?php if (isset($_SESSION['success_withdraw_message'])):?>
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							<?=$_SESSION['success_withdraw_message']?>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
						<?php unset($_SESSION['success_withdraw_message']); endif; ?>


					<div class="card">
						<div class="card-body row">
							<div class="col-md-6  mb-3" style="margin-top: 40px;">
								<div class="table-responsive">
									<table class="table table-bordered mb-0">
										<tr>
											<th width="50%">MT5 Account ID: </th>
											<th width="50%">
												<button type="button" class="btn btn-primary btn-sm waves-effect waves-light"><?=$dataItem->mt5_login_id?></button>
												<?php
												if (ConfigData['enable_deposit_withdraw_rate']):
													if ($getTradingAccount->account_type_status==2) :?>
														<button type="button" class="btn btn-sm btn-default bg-gray" style="border: 1px solid gray;">Fixed Rate</button>
													<?php else: ?>
														<button type="button" class="btn btn-success btn-sm waves-effect waves-light">Live Rate</button>
													<?php endif; endif; ?>
											</th>
										</tr>
										<tr>
											<th width="50%">Withdraw Type: </th>
											<th width="50%">
												<?php
												if ($dataItem->withdrawal_type==1){
													echo "<span style='color: green'>Withdraw By Bank</span>";
												}elseif ($dataItem->withdrawal_type==2){
													echo "<span style='color: green'>Withdraw By Coin</span>";
												}elseif ($dataItem->withdrawal_type==3){
													echo "<span style='color: #0a53be'>Withdraw By Cash</span>";
												}elseif ($dataItem->withdrawal_type==4){
													echo "<span style='color: #0a53be'>Acc Balance</span>";
												}
												?>
											</th>
										</tr>

										<tr>
											<th width="50%">Withdraw Code: </th>
											<th width="50%"><?=$dataItem->withdrawal_code?></th>
										</tr>

										<tr>
											<th width="50%">Amount: </th>
											<th width="50%">$<?=$dataItem->requested_amount?></th>
										</tr>

										<?php if ($getTradingAccount->account_type_status==1): ?>
											<tr>
												<th width="50%">Original Live Rate: </th>
												<th width="50%"><?=$getRateSettings['symbol']?><?=number_format($getTradingAccount->live_rate,2)?></th>
											</tr>

										<?php endif; ?>

										<?php
										if (ConfigData['enable_deposit_withdraw_rate'] && !empty($getTradingAccount->account_type_status)): ?>
											<tr>
												<th width="50%">Converted Amount: </th>
												<?php if ($getTradingAccount->account_type_status==2): ?>
													<th width="50%"><?=$getRateSettings['symbol']?><?=($getRateSettings['dep_with_rate']-2)*$dataItem->requested_amount?></th>
												<?php elseif ($getTradingAccount->account_type_status==1): ?>
													<th width="50%"><?=$getRateSettings['symbol']?><?=number_format(($getTradingAccount->live_rate-2)*$dataItem->requested_amount,2)?></th>
												<?php endif;?>
											</tr>
										<?php endif; ?>

										<tr>
											<th width="50%">Withdraw Note: </th>
											<th width="50%"><?=$dataItem->user_remark?></th>
										</tr>
										<tr>
											<th width="50%">Status: </th>
											<th width="50%">
												<?php if ($dataItem->status==1): ?>
													<span class="badge rounded-pill bg-danger" style="font-size: 12px;!important;">Pending</span>
												<?php elseif($dataItem->status==2): ?>
													<span class="badge rounded-pill bg-success" style="font-size: 12px;!important;">Paid</span>
												<?php elseif($dataItem->status==3): ?>
													<span class="badge rounded-pill bg-danger" style="font-size: 12px;!important;">Rejected</span>
												<?php endif; ?>
											</th>
										</tr>
									</table>
								</div>

								<?php if ($dataItem->status!=2): ?>
									<div style="margin-top: 20px;">
										<form class="" action="<?php echo base_url()."change-withdraw-status"?>" method="post" id="changeWithdrawStatus">
											<input type="hidden" value="<?=$dataItem->id?>" name="withdrawId">
											<input type="hidden" value="<?=$dataItem->requested_amount?>" name="enterAmount">
											<input type="hidden" value="<?=$dataItem->mt5_login_id?>" name="mt5_login_id">
											<input type="hidden" value="<?=$dataItem->unique_id?>" name="unique_id">
											<input type="hidden" value="<?=$dataItem->requested_amount?>" name="amount">
											<input type="hidden" value="<?=$dataItem->mt5_login_id?>" name="mt5_login_id">
											<input type="hidden" value="<?=$dataItem->email?>" name="email">

											<div class="row">
												<div class="col-md-12">
													<div class="mb-3">
														<label for="validationCustom01" class="form-label">Payment Status</label>
														<select class="form-select" id="validationCustom04" name="status" required="">
															<option selected="" disabled="" value="">Choose Payment Status</option>
															<option value="1">Pending</option>
															<option value="2">Paid</option>
															<option value="3">Rejected</option>
														</select>
													</div>
												</div>

												<div class="col-md-12">
													<div class="mb-3">
														<label for="validationCustom01" class="form-label">Note</label>
														<textarea class="form-control" rows="5" data-gramm="false" name="remark"></textarea>
													</div>
												</div>

												<button type="submit" class="btn btn-primary waves-effect waves-light">
													<i class="ri-check-line align-middle me-2"></i>Submit
												</button>

											</div>
										</form>
									</div>
								<?php endif; ?>

							</div>
							<div class="col-lg-6" style="margin-top: 38px;">
								<div class="card border border-success">
									<div class="card-header bg-transparent border-success">
										<h5 class="my-0 text-success"><i class="mdi mdi-check-all me-3"></i>User Informations</h5>
									</div>
									<div class="card-body d-flex">
										<div class="col-md-6 col-sm-12">
											<h5 class="card-title">Name : <?=$dataItem->first_name.' '.$dataItem->last_name?></h5>
											<h5 class="card-title">Mobile : <?=$dataItem->mobile?></h5>
											<h5 class="card-title">City : <?=$dataItem->city?></h5>
										</div>

										<div class="col-md-6 col-sm-12">
											<h5 class="card-title">Email : <?=$dataItem->email?></h5>
											<h5 class="card-title">Country : <?=$dataItem->name?></h5>
											<h5 class="card-title">Zip : <?=$dataItem->zip?></h5>
										</div>
									</div>

									<div class="card-header bg-transparent border-success">
										<h5 class="my-0 text-success"><i class="mdi mdi-check-all me-3"></i>Bank Details</h5>
									</div>
									<div class="card-body d-flex">
										<div class="col-md-6 col-sm-12">
											<h5 class="card-title">Account Name : <?=$dataItem->account_name?></h5>
											<h5 class="card-title">IFSC Code : <?=$dataItem->trx_code?></h5>
											<h5 class="card-title">Bank Name : <?=$dataItem->bank_name?></h5>
										</div>

										<div class="col-md-6 col-sm-12">
											<h5 class="card-title">Account Number : <?=$dataItem->account_number?></h5>
											<h5 class="card-title">Bank Address : <?=$dataItem->bank_address?></h5>
										</div>
									</div>

									<div class="card-header bg-transparent border-success">
										<h5 class="my-0 text-success"><i class="mdi mdi-check-all me-3"></i>Coin Details</h5>
									</div>
									<div class="card-body d-flex">
										<div class="col-md-6 col-sm-12">
											<h5 class="card-title">Coin : <?=$dataItem->coin_name?></h5>
										</div>
										<div class="col-md-6 col-sm-12">
											<h5 class="card-title">Wallet Address : <?=$dataItem->wallet_address?></h5>
										</div>
									</div>

									<div class="card-header bg-transparent border-success">
										<h5 class="my-0 text-success"><i class="mdi mdi-check-all me-3"></i>KYC</h5>
									</div>

									<div class="card-body" style="padding-top: 0px!important;">
										<div class="col-md-12 col-sm-12">
											<h5 class="card-title">Identity Proof :
												<?php if ($dataItem->identity_proof):
													$identity_proof	=base_url()."assets/users/kyc/".$dataItem->unique_id.'/'.$dataItem->identity_proof;
													?>
													<a  target="_blank"  href="<?=$identity_proof?>">
														<img src="<?=$identity_proof?>" style="width: 100px;height: 100px;margin-left: 21px;" class="kyc_image">
													</a>
												<?php endif; ?>
											</h5>
											<h5 class="card-title">Residency Proof :
												<?php if ($dataItem->identity_proof):
													$residency_proof	=base_url()."assets/users/kyc/".$dataItem->unique_id.'/'.$dataItem->residency_proof;
													?>

													<a  target="_blank"  href="<?=$residency_proof?>">
														<img src="<?=$residency_proof?>" style="width: 100px;height: 100px;" class="kyc_image">
													</a>
												<?php endif; ?>
											</h5>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
			<!-- end row -->

		</div> <!-- container-fluid -->
	</div>

</div>

<?php unset($_SESSION['error_wire_transfer']); ?>

<?php
$errorObject=$requestData='';
if (isset($_SESSION['error_wire_transfer'])){
	$errorObject	=json_decode($_SESSION['error_wire_transfer']);
}

$groupId			=$dataItem->group_id;
$groupResult		=$this->db->query("SELECT id,group_name FROM `groups` where id='" . $groupId . "'")->row();

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
						<h4 class="mb-sm-0">Deposit DETAILS FOR (<?=$dataItem->unique_id?>)</h4>
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

					<?php if (isset($_SESSION['success_deposit_message'])):?>
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							<?=$_SESSION['success_deposit_message']?>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
						<?php unset($_SESSION['success_deposit_message']); endif; ?>


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
												if ($dataItem->account_type_status==2) :?>
												<button type="button" class="btn btn-sm btn-default bg-gray" style="border: 1px solid gray;">Fixed Rate</button>
												<?php elseif ($dataItem->account_type_status==1): ?>
													<button type="button" class="btn btn-success btn-sm waves-effect waves-light">Live Rate</button>
												<?php endif; endif; ?>
											</th>
										</tr>
										<tr>
											<th width="50%">Account Type: </th>
											<th width="50%"><?=$groupResult->group_name?></th>
										</tr>
										<tr>
											<th width="50%">TnxID: </th>
											<th width="50%"><?=$dataItem->gateway_id?></th>
										</tr>
										<tr>
											<th width="50%">Amount: </th>
											<th width="50%">$<?=$dataItem->entered_amount?></th>
										</tr>
										<?php
										if (ConfigData['enable_deposit_withdraw_rate'] && !empty($getTradingAccount->account_type_status)): ?>
											<tr>
												<th width="50%">Converted Amount: </th>
												<?php if ($dataItem->account_type_status==2): ?>
														<th width="50%"><?=$getRateSettings['symbol']?><?=$getRateSettings['dep_with_rate']*$dataItem->entered_amount?></th>
												<?php elseif ($dataItem->account_type_status==1): ?>
														<th width="50%"><?=$getRateSettings['symbol']?><?=$dataItem->live_rate*$dataItem->entered_amount?></th>
												<?php endif;?>
											</tr>
										<?php endif; ?>
										<tr>
											<th width="50%">Payment Type: </th>
											<th width="50%">
												<?php
												if ($dataItem->payment_mode==1){
													echo "<span style='color: green'>Wire Transfer</span>";
												}elseif ($dataItem->payment_mode==2){
													echo "<span style='color: green'>CryptoCoin</span>";
												}elseif ($dataItem->payment_mode==3){
													echo "<span style='color: #0a53be'>Paypal</span>";
												}elseif ($dataItem->payment_mode==7){
													echo "<span style='color: #00CC00'>Stripe</span>";
												}
												?>
											</th>
										</tr>
										<tr>
											<th width="50%">Transactions Note: </th>
											<th width="50%"><?=$dataItem->transaciton_detail?></th>
										</tr>
										<tr>
											<th width="50%">Status: </th>
											<th width="50%">
												<?php if ($dataItem->status==0): ?>
													<span class="badge rounded-pill bg-danger">Pending</span>
												<?php elseif($dataItem->status==1): ?>
													<span class="badge rounded-pill bg-success">Approved</span>
												<?php elseif($dataItem->status==2): ?>
													<span class="badge rounded-pill bg-danger">Rejected</span>
												<?php endif; ?>
											</th>
										</tr>
										<tr>
											<th width="50%">Attachment: </th>
											<th width="50%">
												<a href="<?=base_url().$dataItem->transaction_proof_attachment?>" download>
													<img src="<?=base_url().$dataItem->transaction_proof_attachment?>" style="height: 150px;width: 100%; border: 1px solid gray;border-radius: 18px;">
												</a>
											</th>
										</tr>
									</table>
								</div>
								<?php if ($dataItem->status!=1): ?>
								<div style="margin-top: 20px;">
									<form class="" action="<?php echo base_url()."change-payment-status"?>" method="post" id="changePaymentStatus">
										<input type="hidden" value="<?=$dataItem->id?>" name="paymentId">
										<input type="hidden" value="<?=$dataItem->entered_amount?>" name="enterAmount">
										<input type="hidden" value="<?=$dataItem->mt5_login_id?>" name="mt5_login_id">
										<div class="row">
											<div class="col-md-12">
												<div class="mb-3">
												<label for="validationCustom01" class="form-label">Payment Status</label>
													<select class="form-select" id="validationCustom04" name="payment_status" required>
														<option selected="" disabled="" value="">Choose Payment Status</option>
														<option value="0">Pending</option>
														<option value="1">Approved</option>
														<option value="2">Rejected</option>
													</select>
												</div>
											</div>

											<div class="col-md-12">
												<div class="mb-3">
													<label for="validationCustom01" class="form-label">Note</label>
													<textarea required class="form-control" rows="5" data-gramm="false" name="remark"></textarea>
												</div>
											</div>

											<button type="submit" class="btn btn-primary waves-effect waves-light" id="submitButton">
												<i class="ri-check-line align-middle me-2"></i>Change Status
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

									<div class="card-body d-flex" style="padding-top: 0px!important;">
										<div class="col-md-6 col-sm-12">
											<h5 class="card-title">Account Name : <?=$dataItem->account_name?></h5>
											<h5 class="card-title">Account Number : <?=$dataItem->account_number?></h5>
											<h5 class="card-title">IFSC Code : <?=$dataItem->trx_code?></h5>
										</div>

										<div class="col-md-6 col-sm-12">
											<h5 class="card-title">Bank Name : <?=$dataItem->bank_name?></h5>
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

<script>
	$(document).ready(function() {

		var form = $('#changePaymentStatus');
		var submitButton = $('#submitButton');

		// Disable the submit button after it has been clicked
		form.on('submit', function() {
			submitButton.prop('disabled', true);
		});

		// Re-enable the submit button if the form is reset
		form.on('reset', function() {
			submitButton.prop('disabled', false);
		});

		// Re-enable the submit button if there is a validation error
		form.on('invalid-form.validate', function() {
			submitButton.prop('disabled', false);
		});
	});
</script>

<?php
$errorObject=$requestData='';
if (isset($_SESSION['error_withdraw'])){
	$errorObject	=json_decode($_SESSION['error_withdraw']);
}

$bankDetails		=isset($dataItem['bankAccount'])?$dataItem['bankAccount']:'';
$coinPaymentAddress	=isset($dataItem['coinPaymentAddress'])?$dataItem['coinPaymentAddress']:'';
$kycAttachment		=isset($dataItem['kycAttachments'])?$dataItem['kycAttachments']:'';
$minWithdraw		=isset($dataItem['minWithdraw'])?$dataItem['minWithdraw']:'';

$getSettingsModel =$this->db->query("SELECT dep_with_rate,rate_currency,kyc_validations FROM setting")->row();
$dep_with_rate=0;
$rate_currency='USD';
$kyc_validations=0;
if ($getSettingsModel){
	$dep_with_rate=$getSettingsModel->dep_with_rate;
	$rate_currency=$getSettingsModel->rate_currency;
	$kyc_validations=$getSettingsModel->kyc_validations;
}

$kycVerificationsStatus=false;
if ($dataItem['kycAttachments']){
	if ($dataItem['kycAttachments']->identity_verified_status==1 && $dataItem['kycAttachments']->residency_verified_status==1){
		$kycVerificationsStatus=true;
	}
}
?>
<link href="<?=base_url()?>assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

<style>
	.page-content {
		/*padding: calc(39px + 24px) calc(24px / 2) 60px calc(24px / 2) !important;*/
	}
	.table-bordered {
		border: 3px solid #eff2f7;
	}
	.card-body.row {
		display: flex;
		flex-direction: row-reverse;
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
						<h4 class="mb-sm-0"><?= lang('withdraw_request') ?></h4>
						<div class="page-title-right">
							<ol class="breadcrumb m-0">
								<li class="breadcrumb-item"><a href="javascript: void(0);"><?= lang('home') ?></a></li>
								<li class="breadcrumb-item active"><?= lang('withdraw_request') ?></li>
							</ol>
						</div>
					</div>
				</div>
			</div>
			<!-- end page title -->

			<div class="row">
				<div class="col-lg-12">

					<?php if (isset($_SESSION['success_withdraw'])):?>
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							<?=$_SESSION['success_withdraw']?>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
						<?php unset($_SESSION['success_withdraw']); endif; ?>

					<div class="card">
						<div class="card-body row">
							<div class="col-lg-6" style="margin-top: 38px;">
								<div class="card border border-danger">
									<div class="card-header bg-transparent border-danger">
										<h5 class="my-0 text-danger"><i class="mdi mdi-block-helper me-3"></i><?= lang('please_follow_steps') ?></h5>
									</div>
									<div class="card-body">
										<h5 class="card-title"><?= lang('wf_step1') ?></h5>
										<h5 class="card-title"><?= lang('wf_step2') ?></h5>
										<h5 class="card-title"><?= lang('wf_support') ?> <?=ConfigData['support_mail']?>.</h5><br/>

										<ul>
											<li class="d-flex"><?= lang('account_id') ?> : <h5 class="card-title" style="margin-left: 10px" id="accountId">XXXXX</h5></li>
											<li class="d-flex"><?= lang('available_balance') ?> : <h5 class="card-title" style="margin-left: 10px">$<span id="totalBalanceAmount">0.00</span></h5></li>
											<li class="d-flex"><?= lang('withdraw_amount') ?> : <h5 class="card-title" id="withdrawAmount">$0.00</h5></li>
											<li class="d-flex"><?= lang('new_balance') ?> : <h5 class="card-title" id="newBalance">$0.00</h5></li>
										</ul>
									</div>
								</div>
							</div>
							<div class="col-md-6">

								<form class="" action="<?php echo base_url()."user/withdraw"?>" method="post" id="depositWireTransfer" enctype="multipart/form-data" >
									<input type="hidden" id="withdrawType" value="1" name="withdraw_type">
									<div class="col-sm-12">
										<label for="example-text-input" class="col-sm-12 col-form-label"><?= lang('label_payout_method') ?><span class="error">*</span></label>
										<div class="d-flex">
											<div class="form-check mb-3">
												<input class="form-check-input" type="radio" name="payout_id" id="formRadios1" checked value="<?=($bankDetails)?$bankDetails->bank_details_id:''?>">
												<label class="form-check-label" for="formRadios1" >
													<?= lang('bank_account') ?>
												</label>
											</div>
											<div class="form-check mb-3" style="margin-left: 20px;">
												<input class="form-check-input" type="radio" name="payout_id" id="formRadios2" value="<?=($coinPaymentAddress)?$coinPaymentAddress->coin_id:''?>">
												<label class="form-check-label" for="formRadios2">
													<?= lang('crypto_wallet') ?>
												</label>
											</div>
											<div class="form-check mb-3" style="margin-left: 20px;">
												<input class="form-check-input" type="radio" name="payout_id" id="formRadios3" value="3">
												<label class="form-check-label" for="formRadios3">
													<?= lang('cash_payment') ?>
												</label>
											</div>
										</div>
										<span class="error"><?=isset($errorObject->payout_id)?$errorObject->payout_id:''?></span>
									</div>

									<?php if ($bankDetails): ?>
									<div class="col-sm-12" id="bankDetails">
										<div class="table-responsive">
											<table class="table table-bordered mb-0">
												<tbody>
												<tr>
													<th width="30%"><?= lang('account_name') ?> : </th>
													<th width="70%"><?=$bankDetails->account_name?></th>
												</tr>
												<tr>
													<th width="30%"><?= lang('account_number') ?> : </th>
													<th width="70%"><?=$bankDetails->account_number?></th>
												</tr>
												<tr>
													<th width="30%"><?= lang('bank_trx_code') ?> : </th>
													<th width="70%"><?=$bankDetails->trx_code?></th>
												</tr>
												<tr>
													<th width="30%"><?= lang('bank_name') ?>: </th>
													<th width="70%"><?=$bankDetails->bank_name?></th>
												</tr>
												<tr>
													<th width="30%"><?= lang('bank_address') ?>: </th>
													<th width="70%"><?=$bankDetails->bank_address?></th>
												</tr>
												</tbody>
											</table>
										</div>
									</div>
									<?php endif; ?>

									<?php if ($coinPaymentAddress): ?>
									<div class="col-sm-12 d-none" id="coinDetails">
										<div class="table-responsive">
											<table class="table table-bordered mb-0">
												<tbody>
												<tr>
													<th width="30%"><?= lang('coin') ?> : </th>
													<th width="70%"><?=$coinPaymentAddress->name?></th>
												</tr>
												<tr>
													<th width="30%"><?= lang('wallet_address') ?>: </th>
													<th width="70%"><?=$coinPaymentAddress->wallet_address?></th>
												</tr>
												</tbody>
											</table>
										</div>
									</div>
									<?php endif; ?>

									<div class="col-sm-12">
										<label for="example-text-input" class="col-sm-12 col-form-label"><?= lang('account_id') ?><span class="error">*</span></label>
										<div class="">
											<select class="form-control select2" name="mt5_login_id" id="mt5_login_id">
												<option value=""><?= lang('select_account_id') ?></option>
												<?php if (isset($dataItem)):foreach ($dataItem['tradeAccount'] as $key=>$item):?>
												<option value="<?=$item->mt5_login_id?>" dataMinDeposit="<?=$item->minimum_deposit?>" data-accunttype="<?=$item->account_type_status?>" data-liverate="<?=$item->live_rate?>">
													<?=$item->mt5_login_id?> <?php
													if (ConfigData['enable_deposit_withdraw_rate']):
														if ($item->account_type_status==2){
															echo "( Fixed Rate )";
														}elseif ($item->account_type_status==1){
															echo "( Live Rate )";
														}
													endif;
													?>
												</option>
												<?php endforeach; endif; ?>
											</select>
										</div>
										<span class="error"><?=isset($errorObject->mt5_login_id)?$errorObject->mt5_login_id:''?></span>
									</div>

									<div class="col-sm-12">
										<label for="example-text-input" class="col-sm-12 col-form-label"><?= lang('amount_usd') ?><span class="error">*</span></label>
										<input class="form-control" type="number" placeholder="" id="amountWithdraw" name="amount" value="" min="<?=$minWithdraw->min_withdrawal?>" title="Minimum withdrawal should be   <?=$minWithdraw->min_withdrawal?>">
										<span class="error" id="amountErr"><?=isset($errorObject->amount)?$errorObject->amount:''?></span>
										<?php if (isset($errorObject->verified_status)): ?>
										<span class="error"><?=$errorObject->verified_status?></span>
										<?php endif; ?>
									</div>

									<?php if (ConfigData['enable_deposit_withdraw_rate']): ?>
									<div class="col-sm-12 d-none" id="convertedAmount">
										<label for="example-text-input" class="col-sm-12 col-form-label"><?= lang('converted_amount') ?>(In <?=$rate_currency?>)</label>
										<input class="form-control" type="text" placeholder="" id="convertedAmountData" name="converted_amount" value="" style="pointer-events: none">
									</div>
									<?php endif; ?>

									<div class="col-sm-12 mt-3">
										<label for="example-text-input" class="col-sm-12 col-form-label"><?= lang('label_note') ?></label>
										<textarea class="form-control" name="meta_descriptions" style="height: 100px;"></textarea>
										<span class="error"><?=isset($errorObject->meta_descriptions)?$errorObject->meta_descriptions:''?></span>
									</div>

									<input type="hidden" name="totalBalance" id="totalbalance">
									<div class="d-grid mb-3 mt-5">
										<?php if (empty($bankDetails) && empty($coinPaymentAddress)) :?>
											<button class="btn btn-primary" type="button" id="withdrawBtn"><?= lang('withdraw_now') ?></button>
										<?php elseif(empty($kycAttachment) && $kyc_validations==1): ?>
											<button class="btn btn-primary" type="button" id="kycVerifiedBtn"><?= lang('withdraw_now') ?></button>
										<?php elseif(!empty($kycAttachment) && $kyc_validations==1 && $kycVerificationsStatus==false): ?>
											<button class="btn btn-primary" type="button" id="kycVerifiedBtnVerified"><?= lang('withdraw_now') ?></button>
										<?php else: ?>
											<button class="btn btn-primary" type="submit"><?= lang('withdraw_now') ?></button>
										<?php endif; ?>
									</div>
								</form>

							</div>
						</div>
					</div>
				</div>

			</div>
			<!-- end row -->

		</div> <!-- container-fluid -->
	</div>

</div>

<?php unset($_SESSION['error_withdraw']); ?>

<!-- Sweet Alerts js -->
<script src="<?=base_url()?>assets/libs/sweetalert2/sweetalert2.min.js"></script>

<!-- Sweet alert init js-->
<script src="<?=base_url()?>assets/js/pages/sweet-alerts.init.js"></script>

<script>
	<?php if ($dep_with_rate): ?>
	let dep_with_rate=<?=$dep_with_rate?>;
	<?php else: ?>
	let dep_with_rate=0;
	<?php endif; ?>

	$(document).on('blur', 'input#amountWithdraw', function() {
		var amount=$(this).val();
		let accountTypeStatus=$('select#mt5_login_id').find(':selected').data('accunttype');
		let liveRate=$('select#mt5_login_id').find(':selected').data('liverate');

		if (amount &&  amount>0 && Number(accountTypeStatus)==2 || Number(accountTypeStatus)==1){
			if (Number(accountTypeStatus)==1){
				dep_with_rate=parseFloat(liveRate).toFixed(2)-2;
			}else {
				<?php if ($dep_with_rate): ?>
				let re_dep_with_rate=<?=$dep_with_rate?>;
				<?php else: ?>
				re_dep_with_rate=0;
				<?php endif; ?>
				dep_with_rate=parseFloat(re_dep_with_rate).toFixed(2)-2;
			}
			$('#convertedAmount').removeClass('d-none');
			let convertedAmount=amount*dep_with_rate;
			$('#convertedAmountData').val(convertedAmount);
		}else {
			$('#convertedAmount').addClass('d-none');
		}
	});

	$(document).on('change', 'select#mt5_login_id', function() {

		var loginID=$(this).val();
		var post_data = {
			'mt5_login_id':loginID,
			'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
		};

		var url = "<?php echo base_url();?>user/withdraw/get-account-balance";

		$.ajax({
			url : url,
			type : 'POST',
			data: post_data,
			success : function(balance)
			{
				$('h5#accountId').html(loginID);
				$('#totalBalanceAmount').html(balance);
				$('#totalbalance').val(balance);

				let accountTypeStatus=$('select#mt5_login_id').find(':selected').data('accunttype')
				if (Number(accountTypeStatus)==2 || Number(accountTypeStatus)==1){
					$('#convertedAmount').removeClass('d-none');
				}else {
					$('#convertedAmount').addClass('d-none');
				}

			}
		});
	});

	$(document).on('blur', 'input#amountWithdraw', function() {

		var oldBalance	=$('input#totalbalance').val();
		var newBalance	=$(this).val();
		var available=Number(oldBalance)-Number(newBalance);

		if (Number(oldBalance)<Number(newBalance)){
			$('span#amountErr').html('Withdraw balance should be less than '+oldBalance)
		}else{
			$('span#amountErr').html('');
			$('h5#withdrawAmount').html('$'+newBalance);
			$('h5#newBalance').html('$'+available);
		}
	});

	$(document).on('click', 'input#formRadios1', function() {
		$('div#coinDetails').addClass('d-none');
		$('div#bankDetails').removeClass('d-none');
		$('input#withdrawType').val(1);
	});

	$(document).on('click', 'input#formRadios2', function() {
		$('div#coinDetails').removeClass('d-none');
		$('div#bankDetails').addClass('d-none');
		$('input#withdrawType').val(2);
	});

	$(document).on('click', 'input#formRadios3', function() {
		$('div#coinDetails').addClass('d-none');
		$('div#bankDetails').addClass('d-none');
		$('input#withdrawType').val(3);
	});

	$(document).on('blur', 'input#amount', function() {
		var amount=$(this).val();
		if (amount &&  amount>0){
			$('#convertedAmount').removeClass('d-none');
			let convertedAmount=amount*dep_with_rate;
			$('#convertedAmountData').val(convertedAmount);
		}else {
			$('#convertedAmount').addClass('d-none');
		}
	});

</script>

<script>
	$('button#withdrawBtn').click(function () {
		Swal.fire(
			{
				title: "Payout Alert?",
				text: 'Bank/Wallet details is missing. Go to your "<?= lang('profile') ?>" section and update.',
				icon: 'question',
				confirmButtonColor: '#5664d2'
			}
		)
	});

	$('button#kycVerifiedBtn').click(function () {
		Swal.fire(
			{
				title: "Kyc Attachment Alert?",
				text: 'Kyc details is missing. Go to your "<?= lang('profile') ?>" section and update.',
				icon: 'question',
				confirmButtonColor: '#5664d2'
			}
		)
	});

	$('button#kycVerifiedBtnVerified').click(function () {
		Swal.fire(
			{
				title: "Kyc Attachment Alert?",
				text: 'Once the Kyc attachment is verified, After that, you can withdraw. Please wait until that time.',
				icon: 'question',
				confirmButtonColor: '#5664d2'
			}
		)
	});
</script>

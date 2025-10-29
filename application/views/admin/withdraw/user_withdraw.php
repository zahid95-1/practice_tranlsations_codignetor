<?php
$errorObject=$requestData='';
if (isset($_SESSION['error_withdraw'])){
	$errorObject	=json_decode($_SESSION['error_withdraw']);
}
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
	.page-content {
		/*padding: calc(39px + 24px) calc(24px / 2) 60px calc(24px / 2) !important;*/
	}
	button.disable-btn {
		background: #5664d2bd;
		pointer-events: none;
	}
</style>
<link href="<?=base_url()?>assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content" id="result">
	<div class="page-content">
		<div class="container-fluid">

			<!-- start page title -->
			<!-- start page title -->
			<div class="row">
				<div class="col-12">
					<div class="page-title-box d-sm-flex align-items-center justify-content-between">
						<h4 class="mb-sm-0"><?=$this->lang->line('make_user_withdraw')?></h4>
						<div class="page-title-right">
							<ol class="breadcrumb m-0">
								<li class="breadcrumb-item">
									<a href="javascript: void(0);"><?=$this->lang->line('home')?></a>
								</li>
								<li class="breadcrumb-item active"><?=$this->lang->line('withdraw')?></li>
							</ol>
						</div>
					</div>
				</div>
			</div>
			<!-- end page title -->

			<div class="row">
				<div class="col-lg-12">
					<?php if (isset($_SESSION['success_withdraw'])): ?>
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							<?=$_SESSION['success_withdraw']?>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?=$this->lang->line('close')?>"></button>
						</div>
						<?php unset($_SESSION['success_withdraw']); ?>
					<?php endif; ?>

					<div class="card">
						<div class="card-body row">
							<div class="col-md-6">
								<form action="<?=base_url('admin/withdraw/user-withdraw-create')?>" method="post" id="userWithdrawForm" enctype="multipart/form-data">
									<input type="hidden" name="withdraw_type" value="1">
									<input type="hidden" name="loadBankAccount" value="">
									<input type="hidden" name="loadCryptoWallet" value="">
									<input type="hidden" name="kycVerifiedStatus" value="">

									<div class="col-sm-12 mb-3">
										<label class="col-form-label"><?=$this->lang->line('label_user_name')?> <span class="error">*</span></label>
										<select class="form-control select2" name="unique_id" id="selectedUser">
											<option value=""><?=$this->lang->line('select_user_name')?></option>
											<?php if (isset($dataItem)): foreach ($dataItem as $item): ?>
												<option value="<?=$item->unique_id?>">
													<?=$item->first_name?> <?=$item->last_name?> (<?=$item->unique_id?>) (<?=$item->group_name?>)
												</option>
											<?php endforeach; endif; ?>
										</select>
										<span class="error"><?=isset($errorObject->unique_id)? $errorObject->unique_id: ''?></span>
									</div>

									<div class="col-sm-12 mb-3">
										<label class="col-form-label"><?=$this->lang->line('label_payout_method')?> <span class="error">*</span></label>
										<div class="d-flex">
											<div class="form-check me-3">
												<input class="form-check-input" type="radio" name="payout_id" id="payout_bank" value="bank">
												<label class="form-check-label" for="payout_bank">
													<?=$this->lang->line('bank_account')?>
												</label>
											</div>
											<div class="form-check me-3">
												<input class="form-check-input" type="radio" name="payout_id" id="payout_crypto" value="crypto">
												<label class="form-check-label" for="payout_crypto">
													<?=$this->lang->line('crypto_wallet')?>
												</label>
											</div>
											<div class="form-check">
												<input class="form-check-input" type="radio" name="payout_id" id="payout_cash" value="cash">
												<label class="form-check-label" for="payout_cash">
													<?=$this->lang->line('cash_payment')?>
												</label>
											</div>
										</div>
										<span class="error"><?=isset($errorObject->payout_id)? $errorObject->payout_id: ''?></span>
									</div>

									<div class="col-sm-12 d-none mb-3" id="bankDetails">
										<div class="table-responsive">
											<table class="table table-bordered mb-0">
												<tbody>
												<tr>
													<th width="30%"><?=$this->lang->line('account_name')?>:</th>
													<td id="accountName"></td>
												</tr>
												<tr>
													<th><?=$this->lang->line('account_number')?>:</th>
													<td id="accountNumber"></td>
												</tr>
												<tr>
													<th><?=$this->lang->line('bank_trx_code')?>:</th>
													<td id="bankTrxCode"></td>
												</tr>
												<tr>
													<th><?=$this->lang->line('bank_name')?>:</th>
													<td id="bankName"></td>
												</tr>
												<tr>
													<th><?=$this->lang->line('bank_address')?>:</th>
													<td id="bank_address"></td>
												</tr>
												</tbody>
											</table>
										</div>
									</div>

									<div class="col-sm-12 d-none mb-3" id="coinDetails">
										<div class="table-responsive">
											<table class="table table-bordered mb-0">
												<tbody>
												<tr>
													<th width="30%"><?=$this->lang->line('coin')?>:</th>
													<td id="coinId"></td>
												</tr>
												<tr>
													<th><?=$this->lang->line('wallet_address')?>:</th>
													<td id="walletAddress"></td>
												</tr>
												</tbody>
											</table>
										</div>
									</div>

									<div class="col-sm-12 mb-3">
										<label class="col-form-label"><?=$this->lang->line('label_account_id')?> <span class="error">*</span></label>
										<select class="form-control select2" name="mt5_login_id" id="mt5_login_id">
											<option value=""><?=$this->lang->line('select_account_id')?></option>
										</select>
										<span class="error"><?=isset($errorObject->mt5_login_id)? $errorObject->mt5_login_id: ''?></span>
									</div>

									<div class="col-sm-12 mb-3">
										<label class="col-form-label"><?=$this->lang->line('label_amount_usd')?> <span class="error">*</span></label>
										<input class="form-control" type="text" name="amount" id="amountWithdraw" placeholder="<?=$this->lang->line('placeholder_amount_usd')?>" required>
										<span class="error" id="amountErr"><?=isset($errorObject->amount)? $errorObject->amount: ''?></span>
										<?php if (isset($errorObject->verified_status)): ?>
											<span class="error"><?=$errorObject->verified_status?></span>
										<?php endif; ?>
									</div>

									<?php if (ConfigData['enable_deposit_withdraw_rate']): ?>
										<div class="col-sm-12 d-none mb-3" id="convertedAmount">
											<label class="col-form-label"><?=$this->lang->line('label_converted_amount')?> (<?=$rate_currency?>)</label>
											<input class="form-control" type="text" id="convertedAmountData" name="converted_amount" readonly>
										</div>
									<?php endif; ?>

									<div class="col-sm-12 mb-3">
										<label class="col-form-label"><?=$this->lang->line('label_note')?></label>
										<textarea class="form-control" name="meta_descriptions" style="height:100px" placeholder="<?=$this->lang->line('placeholder_note')?>"></textarea>
										<span class="error"><?=isset($errorObject->meta_descriptions)? $errorObject->meta_descriptions: ''?></span>
									</div>

									<input type="hidden" name="totalBalance" id="totalbalance">
									<div class="d-grid mb-3 mt-3">
										<button type="submit" class="btn btn-primary disable-btn" id="withdrawBtn">
											<?=$this->lang->line('button_withdraw_now')?>
										</button>
									</div>
								</form>
							</div>

							<div class="col-lg-6 mt-4">
								<div class="card border-danger">
									<div class="card-header bg-transparent border-danger">
										<h5 class="my-0 text-danger">
											<i class="mdi mdi-block-helper me-3"></i>
											<?=$this->lang->line('please_follow_steps')?>
										</h5>
									</div>
									<div class="card-body">
										<h5 class="card-title"><?=$this->lang->line('wf_step1')?></h5>
										<h5 class="card-title"><?=$this->lang->line('wf_step2')?></h5>
										<h5 class="card-title"><?=$this->lang->line('wf_support')?> <?=ConfigData['support_mail']?></h5>
										<br/>
										<ul>
											<li class="d-flex">
												<?=$this->lang->line('account_id')?>:
												<h5 class="card-title ms-2" id="accountId">XXXXX</h5>
											</li>
											<li class="d-flex">
												<?=$this->lang->line('available_balance')?>:
												<h5 class="card-title ms-2">$<span id="totalBalanceAmount">0.00</span></h5>
											</li>
											<li class="d-flex">
												<?=$this->lang->line('withdraw_amount')?>:
												<h5 class="card-title ms-2" id="withdrawAmount">$0.00</h5>
											</li>
											<li class="d-flex">
												<?=$this->lang->line('new_balance')?>:
												<h5 class="card-title ms-2" id="newBalance">$0.00</h5>
											</li>
										</ul>
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

	$(document).on('change', 'select#mt5_login_id', function() {
		let accountTypeStatus	=$('select#mt5_login_id').find(':selected').attr('accountType');
		let liveRate			=$('select#mt5_login_id').find(':selected').attr('liveRate');

		if (Number(accountTypeStatus)==2 || Number(accountTypeStatus)==1){
			$('#convertedAmount').removeClass('d-none');
		}else {
			$('#convertedAmount').addClass('d-none');
		}
	});

	$(document).on('blur', 'input#amountWithdraw', function() {

		var amount				=$(this).val();
		let accountTypeStatus	=$('select#mt5_login_id').find(':selected').attr('accountType')
		let liveRateBalance		=$('select#mt5_login_id').find(':selected').attr('liveRate');

		if (amount &&  amount>0 && Number(accountTypeStatus)==2 || Number(accountTypeStatus)==1){

			if (Number(accountTypeStatus)==1){
				dep_with_rate=liveRateBalance;
			}else {
				<?php if ($dep_with_rate): ?>
				let re_dep_with_rate=<?=$dep_with_rate?>;
				<?php else: ?>
				re_dep_with_rate=0;
				<?php endif; ?>
				dep_with_rate=re_dep_with_rate;
			}

			$('#convertedAmount').removeClass('d-none');
			let convertedAmount=amount*dep_with_rate;
			$('#convertedAmountData').val(convertedAmount);
		}else {
			$('#convertedAmount').addClass('d-none');
		}
	});

	var kycValidations="<?=ConfigData['kyc_validations']?>";

	$(document).on('change', 'select#selectedUser', function() {

		var unique_id=$(this).val();
		var post_data = {
			'unique_id':unique_id,
			'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
		};

		var url = "<?php echo base_url();?>get-user-bank-account-details";

		$.ajax({
			url : url,
			type : 'POST',
			data: post_data,
			success : function(result)
			{
				var obj = JSON.parse(result);

				//Load bank details
				if(obj.bankDetails){
					$('th#accountName').html(obj.bankDetails.account_name);
					$('th#accountNumber').html(obj.bankDetails.account_number);
					$('th#bankTrxCode').html(obj.bankDetails.trx_code);
					$('th#bankName').html(obj.bankDetails.bank_name);
					$('th#bank_address').html(obj.bankDetails.bank_address);
					$('input#formRadios1').val(obj.bankDetails.bank_details_id);
					$('input#loadBankAccount').val(1);
					$('div#bankDetails').removeClass('d-none');
				}else{
					$('th#accountName,th#accountNumber,th#bankTrxCode,th#bankName,th#bank_address,input#formRadios1,input#loadBankAccount').html('');
					$('input#loadBankAccount').val(0);
					$('input#formRadios1').val('');
					$('div#bankDetails').addClass('d-none');
				}

				if(obj.coinPaymentAddress){
					$('input#formRadios2').val(obj.coinPaymentAddress.id);
					$('th#coinId').html(obj.coinPaymentAddress.name);
					$('th#walletAddress').html(obj.coinPaymentAddress.wallet_address);
					$('input#loadCryptoWallet').val(1);
				}else {
					$('input#formRadios2').val('');
					$('th#coinId,th#walletAddress').html('');
					$('input#loadCryptoWallet').val(0);
				}

				//Kyc Load


				if ((obj.bankDetails || obj.coinPaymentAddress)){
					$('div#submitBtnActions').html('<button class="btn btn-primary disable-btn" type="submit" id="depositBtn">Withdraw Now</button>');
				}else if (obj.bankDetails==null || obj.bankDetails==undefined || obj.coinPaymentAddress==null || obj.coinPaymentAddress==undefined || obj.coinPaymentAddress=='' || obj.bankDetails==''){
					$('div#submitBtnActions').html('<button class="btn btn-primary" type="button" id="misisngPayinfoBtn">Withdraw Now</button>');
				}

				if (kycValidations) {
					if (obj.kycAttachments) {
						$('input#kycVerifiedStatus').val(1);
					} else {
						$('input#kycVerifiedStatus').val(0);
					}

					if (obj.kycAttachments=='' || obj.kycAttachments==null || obj.kycAttachments==undefined){
						$('div#submitBtnActions').html('<button class="btn btn-primary" type="button" id="misisngkycBtn">Withdraw Now</button>');
					}
				}


				//Load Account Details
				$('select#mt5_login_id').find('option').remove();
				$('select#mt5_login_id').append($('<option/>', {
					value: '',
					text : 'Select Account ID'
				}));

				if (obj.getTradingAccount.length>0) {
					for (var i = 0; i < obj.getTradingAccount.length; i++) {
						let status='';
						if (obj.getTradingAccount[i]['account_type_status']==2){
							status="( Fixed Rate )";
						}else if (obj.getTradingAccount[i]['account_type_status']==1){
							status='( Live Rate )';
						}

						$('select#mt5_login_id').append($('<option/>', {
							liveRate:obj.getTradingAccount[i]['live_rate'],
							accountType:obj.getTradingAccount[i]['account_type_status'],
							value: obj.getTradingAccount[i]['mt5_login_id'],
							text : obj.getTradingAccount[i]['mt5_login_id']+status
						}));
					}
				}

				enableDisableBtn();
			}
		});
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
				enableDisableBtn();
			}
		});
	});

	$(document).on('blur', 'input#amountWithdraw', function() {

		var oldBalance	=$('input#totalbalance').val();
		var newBalance	=$(this).val();
		var available=Number(oldBalance)-Number(newBalance);

		if (Number(oldBalance)<Number(newBalance)){
			if (Number(newBalance)==0) {
				$('span#amountErr').html('Withdraw balance should greater than 0');
			}else {
				$('span#amountErr').html('Withdraw balance should be less than ' + oldBalance);
			}
			$('button#depositBtn').addClass('disable-btn');
		}else if (Number(newBalance)==0){
			$('span#amountErr').html('Withdraw balance should greater than 0');
			$('button#depositBtn').addClass('disable-btn');
		}else{
			$('span#amountErr').html('');
			$('h5#withdrawAmount').html('$'+newBalance);
			$('h5#newBalance').html('$'+available);
			$('button#depositBtn').removeClass('disable-btn');
		}
	});

	$(document).on('change', 'select#mt5_login_id,select#bankDetails,select#selectedUser', function() {
		enableDisableBtn();
	});

	function enableDisableBtn(){
		if ($('select#mt5_login_id').val()!='' && $('select#bankDetails').val()!='' && $('select#selectedUser').val()!=''){
			$('button#depositBtn').removeClass('disable-btn');
		}else {
			$('button#depositBtn').addClass('disable-btn');
		}
	}

	$(document).on('click', 'input#formRadios1', function() {
		if ($(this).val()) {
			$('div#coinDetails').addClass('d-none');
			$('div#bankDetails').removeClass('d-none');
			$('input#withdrawType').val(1);
		}
	});

	$(document).on('click', 'input#formRadios2', function() {
		if ($(this).val()) {
			$('div#coinDetails').removeClass('d-none');
			$('div#bankDetails').addClass('d-none');
			$('input#withdrawType').val(2);
		}
	});

	$(document).on('click', 'input#formRadios3', function() {
		if ($(this).val()) {
			$('div#coinDetails').addClass('d-none');
			$('div#bankDetails').addClass('d-none');
			$('input#withdrawType').val(3);
		}
	});

	$(document).on('click', 'button#misisngPayinfoBtn', function() {
		Swal.fire(
			{
				title: "Payout Alert?",
				text: 'Please add bank details or crypto wallet address',
				icon: 'question',
				confirmButtonColor: '#5664d2'
			}
		)
	});
	$(document).on('click', 'button#misisngkycBtn', function() {
		Swal.fire(
			{
				title: "Kyc Attachment Alert?",
				text: 'Please add your kyc details',
				icon: 'question',
				confirmButtonColor: '#5664d2'
			}
		)
	});
</script>

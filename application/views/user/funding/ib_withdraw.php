<?php
$errorObject=$requestData='';
if (isset($_SESSION['error_withdraw'])){
	$errorObject	=json_decode($_SESSION['error_withdraw']);
}

$bankDetails		=isset($dataItem['bankAccount'])?$dataItem['bankAccount']:'';
$coinPaymentAddress	=isset($dataItem['coinPaymentAddress'])?$dataItem['coinPaymentAddress']:'';
$kycAttachment		=isset($dataItem['kycAttachments'])?$dataItem['kycAttachments']:'';
?>
<link href="<?=base_url()?>assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

<style>
	.page-content {
		/*padding: calc(39px + 24px) calc(24px / 2) 60px calc(24px / 2) !important;*/
	}
	.table-bordered {
		border: 3px solid #eff2f7;
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
						<h4 class="mb-sm-0">WithDraw IB COMMISSION</h4>
						<div class="page-title-right">
							<ol class="breadcrumb m-0">
								<li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
								<li class="breadcrumb-item active">WithDraw IB COMMISSION</li>
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
							<div class="col-md-6">

								<form class="" action="<?php echo base_url()."user/ib-commission-withdraw"?>" method="post" id="depositWireTransfer" enctype="multipart/form-data" >
									<input type="hidden" id="withdrawType" value="1" name="withdraw_type">
									<div class="col-sm-12">
										<label for="example-text-input" class="col-sm-12 col-form-label">Where do you want to receive Payouts ?<span class="error">*</span></label>
										<div class="d-flex">
											<div class="form-check mb-3">
												<input class="form-check-input" type="radio" name="payout_id" id="formRadios1" checked value="<?=($bankDetails)?$bankDetails->bank_details_id:''?>">
												<label class="form-check-label" for="formRadios1" >
													Bank Account
												</label>
											</div>
											<div class="form-check mb-3" style="margin-left: 20px;">
												<input class="form-check-input" type="radio" name="payout_id" id="formRadios2" value="<?=($coinPaymentAddress)?$coinPaymentAddress->coin_id:''?>">
												<label class="form-check-label" for="formRadios2">
													Crypto Wallet
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
													<th width="30%">Account Name : </th>
													<th width="70%"><?=$bankDetails->account_name?></th>
												</tr>
												<tr>
													<th width="30%">Account No : </th>
													<th width="70%"><?=$bankDetails->account_number?></th>
												</tr>
												<tr>
													<th width="30%">Bank TRX code : </th>
													<th width="70%"><?=$bankDetails->trx_code?></th>
												</tr>
												<tr>
													<th width="30%">Bank Name: </th>
													<th width="70%"><?=$bankDetails->bank_name?></th>
												</tr>
												<tr>
													<th width="30%">Bank Address: </th>
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
													<th width="30%">Coin : </th>
													<th width="70%"><?=$coinPaymentAddress->name?></th>
												</tr>
												<tr>
													<th width="30%">Wallet Address: </th>
													<th width="70%"><?=$coinPaymentAddress->wallet_address?></th>
												</tr>
												</tbody>
											</table>
										</div>
									</div>
									<?php endif; ?>

									<div class="col-sm-12">
										<label for="example-text-input" class="col-sm-12 col-form-label">IB Account ID<span class="error">*</span></label>
										<div class="">
											<select class="form-control select2" name="mt5_login_id" id="mt5_login_id" required>
												<option value="">Select account ID</option>
												<?php if (isset($dataItem)):foreach ($dataItem['tradeAccount'] as $key=>$item):?>
													<option value="<?=$item->mt5_login_id?>" dataMinDeposit="<?=$item->minimum_deposit?>"><?=$item->mt5_login_id?></option>
												<?php endforeach; endif; ?>
											</select>
										</div>
										<span class="error"><?=isset($errorObject->mt5_login_id)?$errorObject->mt5_login_id:''?></span>
									</div>

									<div class="col-sm-12">
										<label for="example-text-input" class="col-sm-12 col-form-label">Amount (In USD)<span class="error">*</span></label>
										<input class="form-control" type="text" placeholder="" id="amountWithdraw" name="amount" value="" required>
										<span class="error" id="amountErr"><?=isset($errorObject->amount)?$errorObject->amount:''?></span>
										<?php if (isset($errorObject->verified_status)): ?>
										<span class="error"><?=str_replace(" field is required", "", $errorObject->verified_status)?></span>
										<?php endif; ?>
									</div>

									<div class="col-sm-12 mt-3">
										<label for="example-text-input" class="col-sm-12 col-form-label">Note<span class="error">*</span></label>
										<textarea class="form-control" name="meta_descriptions" style="height: 100px;" required></textarea>
										<span class="error"><?=isset($errorObject->meta_descriptions)?$errorObject->meta_descriptions:''?></span>
									</div>

									<input type="hidden" name="totalBalance" id="totalbalance">
									<div class="d-grid mb-3 mt-5">
										<?php if (empty($bankDetails) && empty($coinPaymentAddress)) :?>
											<button class="btn btn-primary" type="button" id="withdrawBtn">Withdraw Now</button>
										<?php elseif(empty($kycAttachment)): ?>
											<button class="btn btn-primary" type="button" id="kycVerifiedBtn">Withdraw Now</button>
										<?php else: ?>
											<button class="btn btn-primary" type="submit">Withdraw Now</button>
										<?php endif; ?>
									</div>
								</form>

							</div>
							<div class="col-lg-6" style="margin-top: 38px;">
								<div class="card border border-danger">
									<div class="card-header bg-transparent border-danger">
										<h5 class="my-0 text-danger"><i class="mdi mdi-block-helper me-3"></i>PLEASE FOLLOW THE STEPS FOR WITHDRAW</h5>
									</div>
									<div class="card-body">
										<h5 class="card-title">1. You can not withdraw your amount greater than your available balance.</h5>
										<h5 class="card-title">2. Please double check your bank details.After confirmations you will get amount in that account.</h5>
										<h5 class="card-title">2. In case if you need support please mail to, <?=ConfigData['support_mail']?>.</h5><br/>

										<ul>
											<li class="d-flex">Account ID : <h5 class="card-title" style="margin-left: 10px" id="accountId">XXXXX</h5></li>
											<li class="d-flex">Available Balance : <h5 class="card-title" style="margin-left: 10px">$<span id="totalBalanceAmount">0.00</span></h5></li>
											<li class="d-flex">Withdraw Amount : <h5 class="card-title" id="withdrawAmount">$0.00</h5></li>
											<li class="d-flex">New Balance : <h5 class="card-title" id="newBalance">$0.00</h5></li>
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

</script>

<script>
	$('button#withdrawBtn').click(function () {
		Swal.fire(
			{
				title: "Payout Alert?",
				text: 'Bank/Wallet details is missing. Go to your "Profile" section and update.',
				icon: 'question',
				confirmButtonColor: '#5664d2'
			}
		)
	});

	$('button#kycVerifiedBtn').click(function () {
		Swal.fire(
			{
				title: "Kyc Attachment Alert?",
				text: 'Kyc details is missing. Go to your "Profile" section and update.',
				icon: 'question',
				confirmButtonColor: '#5664d2'
			}
		)
	});
</script>

<?php
$errorObject=$requestData='';
if (isset($_SESSION['error_wire_transfer'])){
	$errorObject	=json_decode($_SESSION['error_wire_transfer']);
}
$getRateSettings = $this->PaymentModel->getRateSettings();
$dep_with_rate=$getRateSettings['dep_with_rate'];
$rate_currency=$getRateSettings['rate_currency'];

?>

<style>
	.page-content {
		/*padding: calc(39px + 24px) calc(24px / 2) 60px calc(24px / 2) !important;*/
	}
	.card-body.row {
		display: flex;
		flex-direction: row-reverse;
	}
	.image-block {
		border: 1px solid gray;
		width: 77%;
		box-shadow: blue;
		border-radius: 12px;
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
                                                <h4 class="mb-sm-0"><?= lang('deposit_with_wire_transfer') ?></h4>
						<div class="page-title-right">
							<ol class="breadcrumb m-0">
                                                                <li class="breadcrumb-item"><a href="javascript: void(0);"><?= lang('home') ?></a></li>
                                                                <li class="breadcrumb-item active"><?= lang('with_wire_transfer') ?></li>
							</ol>
						</div>
					</div>
				</div>
			</div>
			<!-- end page title -->

			<div class="row">
				<div class="col-lg-12">

					<?php if (isset($_SESSION['success_deposit'])):?>
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							<?=$_SESSION['success_deposit']?>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
						<?php unset($_SESSION['success_deposit']); endif; ?>

					<div class="card">
						<div class="card-body row">

							<div class="col-lg-6" style="margin-top: 38px;">
								<div class="card border border-danger">
									<div class="card-header bg-transparent border-danger">
                                                                               <h5 class="my-0 text-danger"><i class="mdi mdi-block-helper me-3"></i><?= lang('local_bank_deposit_steps') ?></h5>
									</div>
									<div class="card-body">
                                                                               <h5 class="card-title"><?= lang('deposit_fund_and_upload_proof') ?></h5>
                                                                               <h5 class="card-title"><?= lang('need_support_email') ?> <?=ConfigData['support_mail']?></h5><br/>
                                                                               <h5 class="my-10 text-danger"><?= lang('clients_from_india_note') ?></h5>
										<ul>
                                                                               <li class="d-flex"><?= lang('account_name') ?> : <h5 class="card-title" style="margin-left: 10px"><?=ConfigData['deposit_bank_name']?></h5></li>
                                                                               <li class="d-flex"><?= lang('account_number') ?> : <h5 class="card-title" style="margin-left: 10px"><?=ConfigData['deposit_bank_ac_no']?></h5></li>
                                                                               <li class="d-flex"><?= lang('ifsc_code') ?> : <h5 class="card-title"><?=ConfigData['deposit_bank_ifc']?></h5></li>
                                                                               <li class="d-flex"><?= lang('account_type') ?> : <h5 class="card-title"><?=ConfigData['deposit_bank_ac_type']?></h5></li>
										</ul>
										<?php if (ConfigData['prefix']=='CFX'): ?>
										<div class="image-block">
											<img src="<?=base_url()."assets/payment_qr/companion_qr_pay.png"?>">
										</div>
										<?php endif; ?>
										<br/>
                                                                               <h5 class="my-10 text-danger"><?= lang('wait_for_admin_approval') ?></h5>
									</div>
								</div>
							</div>

							<div class="col-md-6  mb-3">

								<form class="" action="<?php echo base_url()."user/deposit/deposit-wire-transfer"?>" method="post" id="depositWireTransfer" enctype="multipart/form-data" >
									<div class="col-sm-12">
                                                                               <label for="example-text-input" class="col-sm-12 col-form-label"><?= lang('account_id') ?><span class="error">*</span></label>
										<div class="mb-3">
											<select class="form-control select2" name="mt5_login_id" id="mt5_login_id">
                                                                               <option value=""><?= lang('select_account_id') ?></option>
												<?php if (isset($dataItem)):foreach ($dataItem as $key=>$item):?>
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
										<input class="form-control" type="text" placeholder="" id="amount" name="amount" value="">
										<span class="error"><?=isset($errorObject->amount)?$errorObject->amount:''?></span>
									</div>

									<?php if (ConfigData['enable_deposit_withdraw_rate']): ?>
									<div class="col-sm-12 d-none" id="convertedAmount">
                                                                               <label for="example-text-input" class="col-sm-12 col-form-label"><?= lang('converted_amount') ?>(In <?=$rate_currency?>)</label>
										<input class="form-control" type="text" placeholder="" id="convertedAmountData" name="converted_amount" value="" style="pointer-events: none">
									</div>
									<?php endif; ?>

									<div class="col-sm-12">
                                                                               <label for="example-text-input" class="col-sm-12 col-form-label"><?= lang('upload_deposit_proof') ?> <span class="error">*</span></label>
										<input type="file" class="form-control" id="deposit_proof" name="deposit_proof"  accept="image/*">
										<span class="error"><?=isset($errorObject->deposit_proof)?$errorObject->deposit_proof:''?></span>
									</div>

									<div class="col-sm-12 mt-3">
                                                                               <label for="example-text-input" class="col-sm-12 col-form-label"><?= lang('transaction_reference_number') ?></label>
										<textarea class="form-control" name="meta_descriptions" style="height: 100px;"></textarea>
										<span class="error"><?=isset($errorObject->meta_descriptions)?$errorObject->meta_descriptions:''?></span>
									</div>


									<div class="d-grid mb-3 mt-5">
                                                                               <button class="btn btn-primary" type="submit"><?= lang('deposit_now') ?></button>
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

<?php unset($_SESSION['error_wire_transfer']); ?>

<script>
	<?php if ($dep_with_rate): ?>
	let dep_with_rate=<?=$dep_with_rate?>;
	<?php else: ?>
	let dep_with_rate=0;
	<?php endif; ?>

	$(document).on('change', 'select#mt5_login_id', function() {
		let accountTypeStatus=$(this).find(':selected').data('accunttype')
		if (Number(accountTypeStatus)==2 || Number(accountTypeStatus)==1){
			$('#convertedAmount').removeClass('d-none');
		}else {
			$('#convertedAmount').addClass('d-none');
		}
	});

	$(document).on('blur', 'input#amount', function() {

		var amount					=$(this).val();
		let accountTypeStatus		=$('select#mt5_login_id').find(':selected').data('accunttype');
		let liverateData			=$('select#mt5_login_id').find(':selected').data('liverate');

		if (amount &&  amount>0 && Number(accountTypeStatus)==2 || Number(accountTypeStatus)==1){
			if (Number(accountTypeStatus)==1){
				dep_with_rate=liverateData;
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
			$('#convertedAmountData').val(parseFloat(convertedAmount).toFixed(1));
		}else {
			$('#convertedAmount').addClass('d-none');
		}
	});

</script>

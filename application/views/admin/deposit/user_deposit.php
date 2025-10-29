<?php
$errorObject=$requestData='';
if (isset($_SESSION['error_wire_transfer'])){
	$errorObject	=json_decode($_SESSION['error_wire_transfer']);
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
						<h4 class="mb-sm-0"><?=$this->lang->line('direct_deposit_to_user')?></h4>
						<div class="page-title-right">
							<ol class="breadcrumb m-0">
								<li class="breadcrumb-item"><a href="javascript: void(0);"><?=$this->lang->line('home')?></a></li>
								<li class="breadcrumb-item active"><?=$this->lang->line('with_wire_transfer')?></li>
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
							<div class="col-md-6  mb-3">

								<form action="<?php echo base_url()."admin/deposit/user-deposit-create"?>" method="post" id="depositWireTransfer" enctype="multipart/form-data">
									<div class="col-sm-12">
										<label class="col-sm-12 col-form-label"><?=$this->lang->line('user_name')?> <span class="error">*</span></label>
										<div>
											<select class="form-control select2" name="unique_id" id="selectedUser">
												<option value=""><?=$this->lang->line('select_user')?></option>
												<?php if (isset($dataItem)): foreach ($dataItem as $item): ?>
													<option value="<?=$item->unique_id?>"><?=$item->first_name.' '.$item->last_name .' ('.$item->group_name.')'?></option>
												<?php endforeach; endif; ?>
											</select>
										</div>
										<span class="error"><?=isset($errorObject->mt5_login_id)?$errorObject->mt5_login_id:''?></span>
									</div>

									<div class="col-sm-12">
										<label class="col-sm-12 col-form-label"><?=$this->lang->line('account_id')?> <span class="error">*</span></label>
										<div>
											<select class="form-control select2" name="mt5_login_id" id="mt5_login_id">
												<option value=""><?=$this->lang->line('select_account_id')?></option>
											</select>
										</div>
										<span class="error"><?=isset($errorObject->mt5_login_id)?$errorObject->mt5_login_id:''?></span>
									</div>

									<div class="col-sm-12">
										<label class="col-sm-12 col-form-label"><?=$this->lang->line('amount_usd')?> <span class="error">*</span></label>
										<input class="form-control" type="text" name="amount" id="amount" value="">
										<span class="error"><?=isset($errorObject->amount)?$errorObject->amount:''?></span>
									</div>

									<?php if (ConfigData['enable_deposit_withdraw_rate']): ?>
										<div class="col-sm-12 d-none" id="convertedAmount">
											<label class="col-sm-12 col-form-label"><?=$this->lang->line('converted_amount')?> (In <?=$rate_currency?>)</label>
											<input class="form-control" type="text" id="convertedAmountData" name="converted_amount" value="" style="pointer-events: none">
										</div>
									<?php endif; ?>

									<div class="col-sm-12 mt-3">
										<label class="col-sm-12 col-form-label"><?=$this->lang->line('transaction_reference_number')?> <span class="error">*</span></label>
										<textarea class="form-control" name="meta_descriptions" style="height: 100px;"></textarea>
										<span class="error"><?=isset($errorObject->meta_descriptions)?$errorObject->meta_descriptions:''?></span>
									</div>

									<div class="d-grid mb-3 mt-5">
										<button class="btn btn-primary" type="submit" id="submitButton"><?=$this->lang->line('deposit_now')?></button>
									</div>
								</form>

							</div>
							<div class="col-lg-6" style="margin-top: 38px;">
								<div class="card border border-danger">
									<div class="card-header bg-transparent border-danger">
										<h5 class="my-0 text-danger"><i class="mdi mdi-block-helper me-3"></i><?=$this->lang->line('local_bank_deposit_steps')?></h5>
									</div>
									<div class="card-body">
										<h5 class="card-title"><?=$this->lang->line('deposit_fund_and_upload_proof')?></h5>
										<h5 class="card-title"><?=$this->lang->line('need_support_email')?> <?=ConfigData['support_mail']?></h5><br/>
										<h5 class="my-10 text-danger"><?=$this->lang->line('clients_from_india_note')?></h5>
										<ul>
											<li class="d-flex"><?=$this->lang->line('account_name')?>: <h5 class="card-title" style="margin-left: 10px"><?=ConfigData['deposit_bank_name']?></h5></li>
											<li class="d-flex"><?=$this->lang->line('account_number')?>: <h5 class="card-title" style="margin-left: 10px"><?=ConfigData['deposit_bank_ac_no']?></h5></li>
											<li class="d-flex"><?=$this->lang->line('ifsc_code')?>: <h5 class="card-title"><?=ConfigData['deposit_bank_ifc']?></h5></li>
											<li class="d-flex"><?=$this->lang->line('account_type')?>: <h5 class="card-title"><?=ConfigData['deposit_bank_ac_type']?></h5></li>
										</ul>
										<br/>
										<h5 class="my-10 text-danger"><?=$this->lang->line('wait_for_admin_approval')?></h5>
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
	<?php if ($dep_with_rate): ?>
	let dep_with_rate=<?=$dep_with_rate?>;
	<?php else: ?>
	let dep_with_rate=0;
	<?php endif; ?>

	$(document).on('change', 'select#mt5_login_id', function() {
		let accountTypeStatus=$('select#mt5_login_id').find(':selected').attr('accountType');
		let liveRate=$('select#mt5_login_id').find(':selected').attr('liveRate');

		if (Number(accountTypeStatus)==2 || Number(accountTypeStatus)==1){
			$('#convertedAmount').removeClass('d-none');
		}else {
			$('#convertedAmount').addClass('d-none');
		}
	});

	$(document).on('blur', 'input#amount', function() {
		var amount=$(this).val();
		let accountTypeStatus=$('select#mt5_login_id').find(':selected').attr('accountType');
		let liveRate=$('select#mt5_login_id').find(':selected').attr('liveRate');

		if (amount &&  amount>0 && Number(accountTypeStatus)==2 || Number(accountTypeStatus)==1){
			if (Number(accountTypeStatus)==1){
				dep_with_rate=liveRate;
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
			$('#convertedAmountData').val(parseFloat(convertedAmount).toFixed(2));

		}else {
			$('#convertedAmount').addClass('d-none');
		}
	});

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
				//Load Account Details
				$('select#mt5_login_id').find('option').remove();
				$('select#mt5_login_id').append($('<option/>', {
					value: '',
					text : 'Select Account ID'
				}));
				for(var i=0;i<obj.getTradingAccount.length;i++)
				{
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
		});
	});
</script>
<script>
	$(document).ready(function() {

		var form = $('#depositWireTransfer');
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

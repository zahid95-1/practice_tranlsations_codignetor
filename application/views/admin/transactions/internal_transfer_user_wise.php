<?php
$errorObject=$requestData='';
if (isset($_SESSION['error_transfer'])){
	$errorObject	=json_decode($_SESSION['error_transfer']);
}
?>

<style>
	.page-content {
		/*padding: calc(39px + 24px) calc(24px / 2) 60px calc(24px / 2) !important;*/
	}
	button.disable {
		pointer-events: none;
		background: #4550a8b8!important;
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
						<h4 class="mb-sm-0"><?=$this->lang->line('master_internal_transfer')?></h4>
						<div class="page-title-right">
							<ol class="breadcrumb m-0">
								<li class="breadcrumb-item"><a href="javascript: void(0);"><?=$this->lang->line('home')?></a></li>
								<li class="breadcrumb-item active"><?=$this->lang->line('user_internal_transfer')?></li>
							</ol>
						</div>
					</div>
				</div>
			</div>
			<!-- end page title -->

			<div class="row">
				<div class="col-lg-12">

					<?php if (isset($_SESSION['success_transfer'])): ?>
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							<?=$_SESSION['success_transfer']?>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
						<?php unset($_SESSION['success_transfer']); endif; ?>

					<div class="card">
						<div class="card-body row">
							<div class="col-md-6">

								<form action="<?=base_url('admin/transaction/user-wise-internal-transfer')?>" method="post" id="userInternalTransfer" enctype="multipart/form-data">

									<div class="col-sm-12">
										<label class="col-sm-12 col-form-label"><?=$this->lang->line('from_user_name')?> <span class="error">*</span></label>
										<select class="form-control select44" name="from_unique_id" id="selecteFromdUser">
											<option value=""><?=$this->lang->line('select_user')?></option>
											<?php if (isset($dataItem)): foreach ($dataItem as $item): ?>
												<option value="<?=$item->unique_id?>">
													<?=$item->first_name.' '.$item->last_name .' ('.$item->group_name.')'.' ('.$item->email.')'?>
												</option>
											<?php endforeach; endif; ?>
										</select>
										<span class="error"><?=isset($errorObject->mt5_login_id) ? $errorObject->mt5_login_id : ''?></span>
									</div>

									<div class="col-sm-12">
										<label class="col-sm-12 col-form-label"><?=$this->lang->line('from_account')?> <span class="error">*</span></label>
										<select class="form-control select4" name="from_mt5_login_id" id="from_mt5_login_id">
											<option value=""><?=$this->lang->line('select_from_account')?></option>
										</select>
										<span class="error"><?=isset($errorObject->from_mt5_login_id) ? $errorObject->from_mt5_login_id : ''?></span>
									</div>

									<div class="col-sm-12">
										<label class="col-sm-12 col-form-label"><?=$this->lang->line('to_user_name')?> <span class="error">*</span></label>
										<select class="form-control select2" name="to_unique_id" id="selectedToUser">
											<option value=""><?=$this->lang->line('select_user')?></option>
											<?php if (isset($dataItem)): foreach ($dataItem as $item): ?>
												<option value="<?=$item->unique_id?>">
													<?=$item->first_name.' '.$item->last_name .' ('.$item->group_name.')'.' ('.$item->email.')'?>
												</option>
											<?php endforeach; endif; ?>
										</select>
										<span class="error"><?=isset($errorObject->mt5_login_id) ? $errorObject->mt5_login_id : ''?></span>
									</div>

									<div class="col-sm-12">
										<label class="col-sm-12 col-form-label"><?=$this->lang->line('to_account')?> <span class="error">*</span></label>
										<select class="form-control select2" name="to_mt5_login_id" id="to_mt5_login_id">
											<option value=""><?=$this->lang->line('select_to_account')?></option>
										</select>
										<span class="error"><?=isset($errorObject->to_mt5_login_id) ? $errorObject->to_mt5_login_id : ''?></span>
										<span class="error"><?=isset($errorObject->invalid_id) ? $errorObject->invalid_id : ''?></span>
									</div>

									<div class="col-sm-12">
										<label class="col-sm-12 col-form-label"><?=$this->lang->line('amount_usd')?> <span class="error">*</span></label>
										<input class="form-control" type="text" id="amountTransfer" name="amount" placeholder="<?=$this->lang->line('enter_amount_usd')?>" value="">
										<span class="error" id="amountErr"><?=isset($errorObject->amount) ? $errorObject->amount : ''?></span>
										<?php if (isset($errorObject->verified_status)): ?>
											<span class="error"><?=$errorObject->verified_status?></span>
										<?php endif; ?>
									</div>

									<input type="hidden" name="totalBalance" id="totalbalance">
									<div class="d-grid mb-3 mt-5">
										<button class="btn btn-primary" type="submit" id="transferSubmitBtn"><?=$this->lang->line('submit')?></button>
									</div>
								</form>

							</div>

							<div class="col-lg-6" style="margin-top: 38px;">
								<div class="card border border-danger">
									<div class="card-header bg-transparent border-danger">
										<h5 class="my-0 text-danger"><i class="mdi mdi-block-helper me-3"></i><?=$this->lang->line('withdraw_instructions_title')?></h5>
									</div>
									<div class="card-body">
										<h5 class="card-title">1. <?=$this->lang->line('check_balance_before_transfer')?></h5>
										<h5 class="card-title">2. <?=$this->lang->line('contact_support_if_needed')?> <?=ConfigData['support_mail']?></h5><br/>
										<ul>
											<li class="d-flex"><?=$this->lang->line('account_id')?>: <h5 class="card-title" style="margin-left: 10px" id="accountId">XXXXX</h5></li>
											<li class="d-flex"><?=$this->lang->line('available_balance')?>: <h5 class="card-title" style="margin-left: 10px">$<span id="totalBalanceAmount">0.00</span></h5></li>
											<li class="d-flex"><?=$this->lang->line('withdraw_amount')?>: <h5 class="card-title" id="withdrawAmount">$0.00</h5></li>
											<li class="d-flex"><?=$this->lang->line('new_balance')?>: <h5 class="card-title" id="newBalance">$0.00</h5></li>
										</ul>
									</div>
								</div>
							</div>

						</div>
					</div>

				</div>
			</div>
			<!-- end row -->
		</div>
	</div>

</div>

<?php unset($_SESSION['error_transfer']); ?>

<script>
	$(document).on('change', 'select#from_mt5_login_id', function() {

		//$('select#to_mt5_login_id').val('1001980').remove();

		var loginID=$(this).val();
		//$("select#to_mt5_login_id option[value='"+loginID+"']").remove();
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
				// $("select#to_mt5_login_id option[value='"+loginID+"']").remove();
			}
		});
	});

	$(document).on('blur', 'input#amountTransfer', function() {

		var oldBalance	=$('input#totalbalance').val();
		var newBalance	=$(this).val();
		var available=Number(oldBalance)-Number(newBalance);

		if (Number(oldBalance)<Number(newBalance)){
			 $('#transferSubmitBtn').addClass('disable');
			$('span#amountErr').html('Transfer balance should be less than or equal to '+oldBalance)
		}else{
			$('#transferSubmitBtn').removeClass('disable');
			$('span#amountErr').html('');
			$('h5#withdrawAmount').html('$'+newBalance);
			$('h5#newBalance').html('$'+available);
		}
	});

</script>
<script>
	$(document).on('change', 'select#selecteFromdUser', function() {

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
				$('select#from_mt5_login_id').find('option').remove();
				$('select#from_mt5_login_id').append($('<option/>', {
					value: '',
					text : 'Select Account ID'
				}));
				for(var i=0;i<obj.getTradingAccount.length;i++)
				{
					$('select#from_mt5_login_id').append($('<option/>', {
						value: obj.getTradingAccount[i]['mt5_login_id'],
						text : obj.getTradingAccount[i]['mt5_login_id']
					}));
				}
			}
		});
	});

	//
	$(document).on('change', 'select#selectedToUser', function() {

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
				$('select#to_mt5_login_id').find('option').remove();
				$('select#to_mt5_login_id').append($('<option/>', {
					value: '',
					text : 'Select Account ID'
				}));
				for(var i=0;i<obj.getTradingAccount.length;i++)
				{
					$('select#to_mt5_login_id').append($('<option/>', {
						value: obj.getTradingAccount[i]['mt5_login_id'],
						text : obj.getTradingAccount[i]['mt5_login_id']
					}));
				}
			}
		});
	});
</script>
<script>
	$(document).ready(function() {

		var form = $('#userInternalTransfer');
		var submitButton = $('#transferSubmitBtn');

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

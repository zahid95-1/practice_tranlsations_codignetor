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
						<h4 class="mb-sm-0"><?= lang('internal_transfer') ?></h4>
						<div class="page-title-right">
							<ol class="breadcrumb m-0">
								<li class="breadcrumb-item"><a href="javascript: void(0);"><?= lang('home') ?></a></li>
								<li class="breadcrumb-item active"><?= lang('internal_transfer') ?></li>
							</ol>
						</div>
					</div>
				</div>
			</div>
			<!-- end page title -->

			<div class="row">
				<div class="col-lg-12">

					<?php if (isset($_SESSION['success_transfer'])):?>
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							<?=$_SESSION['success_transfer']?>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
						<?php unset($_SESSION['success_transfer']); endif; ?>

					<div class="card">
						<div class="card-body row">
							<div class="col-md-6">

								<form class="" action="#" method="post" id="depositWireTransfer" enctype="multipart/form-data" >

									<div class="col-sm-12">
										<label for="example-text-input" class="col-sm-12 col-form-label"><?= lang('from_account') ?><span class="error">*</span></label>
										<div class="">
											<select class="form-control select3" name="from_mt5_login_id" id="from_mt5_login_id">
												<option value=""><?= lang('select_from_account') ?></option>
												<?php if (isset($dataItem)):foreach ($dataItem as $key=>$item):?>
													<option value="<?=$item->mt5_login_id?>" dataMinDeposit="<?=$item->minimum_deposit?>"><?=$item->mt5_login_id?></option>
												<?php endforeach; endif; ?>
											</select>
										</div>
										<span class="error"><?=isset($errorObject->from_mt5_login_id)?$errorObject->from_mt5_login_id:''?></span>
									</div>

									<div class="col-sm-12">
										<label for="example-text-input" class="col-sm-12 col-form-label"><?= lang('to_account') ?><span class="error">*</span></label>
										<div class="">
											<select class="form-control select2" name="to_mt5_login_id" id="to_mt5_login_id">
												<option value="">Select <?= lang('to_account') ?> ID</option>
											</select>
										</div>
										<span class="error"><?=isset($errorObject->to_mt5_login_id)?$errorObject->to_mt5_login_id:''?></span>
									</div>

									<div class="col-sm-12">
										<label for="example-text-input" class="col-sm-12 col-form-label"><?= lang('amount_usd') ?><span class="error">*</span></label>
										<input class="form-control" type="text" placeholder="" id="amountTransfer" name="amount" value="">
										<span class="error" id="amountErr"><?=isset($errorObject->amount)?$errorObject->amount:''?></span>
										<?php if (isset($errorObject->verified_status) && $errorObject->verified_status): ?>
											<span class="error"><?=$errorObject->verified_status?></span>
										<?php endif; ?>
									</div>

									<input type="hidden" name="totalBalance" id="totalbalance">
									<div class="d-grid mb-3 mt-5">
										<button class="btn btn-primary" type="submit" disabled><?= lang('submit') ?></button>
									</div>
								</form>
							</div>
							<div class="col-lg-6" style="margin-top: 38px;">
								<div class="card border border-danger">
									<div class="card-header bg-transparent border-danger">
										<h5 class="my-0 text-danger"><i class="mdi mdi-block-helper me-3"></i><?= lang('itf_steps') ?></h5>
									</div>
									<div class="card-body">
										<h5 class="card-title"><?= lang('itf_step1') ?></h5>
										<h5 class="card-title"><?= lang('itf_support') ?> <?=ConfigData['support_mail']?>.</h5><br/>
										<ul>
											<li class="d-flex"><?= lang('account_id') ?> : <h5 class="card-title" style="margin-left: 10px" id="accountId">XXXXX</h5></li>
											<li class="d-flex"><?= lang('available_balance') ?> : <h5 class="card-title" style="margin-left: 10px">$<span id="totalBalanceAmount">0.00</span></h5></li>
											<li class="d-flex"><?= lang('withdraw_amount') ?> : <h5 class="card-title" id="withdrawAmount">$0.00</h5></li>
											<li class="d-flex"><?= lang('new_balance') ?> : <h5 class="card-title" id="newBalance">$0.00</h5></li>
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

<?php unset($_SESSION['error_transfer']); ?>

<script>
	$(document).on('change', 'select#from_mt5_login_id', function() {

		//$('select#to_mt5_login_id').val('1001980').remove();

		var loginID=$(this).val();
		//$("select#to_mt5_login_id option[value='"+loginID+"']").remove();

		loadToAccount(loginID);
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

	function loadToAccount(fromAccountId){

		var post_data = {
			'from_account_id':fromAccountId,
			'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
		};

		var url = "<?php echo base_url();?>user/get-trading-account";

		$.ajax({
			url : url,
			type : 'POST',
			data: post_data,
			success : function(result)
			{
				var obj = JSON.parse(result);

				console.log(obj);


				//Load Account Details
				$('select#to_mt5_login_id').find('option').remove();
				$('select#to_mt5_login_id').append($('<option/>', {
					value: '',
					text : 'Select <?= lang('to_account') ?> ID'
				}));
				for(var i=0;i<obj.tradingtoAcocunt.length;i++)
				{
					$('select#to_mt5_login_id').append($('<option/>', {
						value: obj.tradingtoAcocunt[i]['mt5_login_id'],
						text : obj.tradingtoAcocunt[i]['mt5_login_id']
					}));
				}
			}
		});
	}

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
			$('h5#newBalance').html('$'+parseFloat(available).toFixed(2));
		}
	});

</script>

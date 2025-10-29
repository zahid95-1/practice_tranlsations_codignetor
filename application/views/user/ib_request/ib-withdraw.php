<?php
$errorObject=$requestData='';
if (isset($_SESSION['error_transfer'])){
	$errorObject	=json_decode($_SESSION['error_transfer']);
}
//echo "<pre>";
//print_r($dataItem);
//exit();

$uniqueID = $_SESSION['unique_id'];
$this->db->query("update commission_transfer  set user_notification = 1 where user_notification = 0 and status = 1 and unique_id = '$uniqueID' ");
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
                                                <h4 class="mb-sm-0"><?= lang('commission_transfer_to_mt5') ?></h4>
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
								<form class="" action="<?php echo base_url()."user/commission-transfer"?>" method="post" id="depositWireTransfer" enctype="multipart/form-data" >
                                                                        <div class="col-sm-12">
                                                                                <label for="example-text-input" class="col-sm-12 col-form-label"><?= lang('from_account') ?><span class="error">*</span></label>
                                                                                <div class="">
                                                                                        <select class="form-control select3" name="from_mt5_login_id" id="from_mt5_login_id">
                                                                                                <option value=""><?= lang('select_from_account_id') ?></option>
												<?php if (isset($dataItem)):foreach ($dataItem['trading_account_from'] as $key=>$item):?>
													<option value="<?=$item->mt5_login_id?>"><?=$item->mt5_login_id?></option>
												<?php endforeach; endif; ?>
											</select>
										</div>
										<span class="error"><?=isset($errorObject->from_mt5_login_id)?$errorObject->from_mt5_login_id:''?></span>
									</div>

                                                                        <div class="col-sm-12">
                                                                                <label for="example-text-input" class="col-sm-12 col-form-label"><?= lang('to_account') ?><span class="error">*</span></label>
                                                                                <div class="">
                                                                                        <select class="form-control select2" name="to_mt5_login_id" id="to_mt5_login_id">
                                                                                                <option value=""><?= lang('select_to_account_id') ?></option>
												<?php if (isset($dataItem)):foreach ($dataItem['trading_account'] as $key=>$item):?>
													<option value="<?=$item->mt5_login_id?>"><?=$item->mt5_login_id?></option>
												<?php endforeach; endif; ?>
											</select>
										</div>
										<span class="error"><?=isset($errorObject->to_mt5_login_id)?$errorObject->to_mt5_login_id:''?></span>
									</div>


                                                                        <div class="col-sm-12">
                                                                                <label for="example-text-input" class="col-sm-12 col-form-label"><?= lang('amount_in_usd') ?><span class="error">*</span></label>
										<input class="form-control" type="text" placeholder="" id="amountTransfer" name="amount" value="">
										<span class="error" id="amountErr"><?=isset($errorObject->amount)?$errorObject->amount:''?></span>
										<?php if (isset($errorObject->verified_status) && $errorObject->verified_status): ?>
											<span class="error"><?=str_replace(" field is required", "", $errorObject->verified_status)?></span>
										<?php endif; ?>
									</div>


                                                                        <div class="col-sm-12 mt-3">
                                                                                <label for="example-text-input" class="col-sm-12 col-form-label"><?= lang('note') ?></label>
										<textarea class="form-control" name="meta_descriptions" style="height: 100px;"></textarea>
										<span class="error"><?=isset($errorObject->meta_descriptions)?$errorObject->meta_descriptions:''?></span>
									</div>

									<input type="hidden" name="totalBalance" id="totalbalance">
                                                                        <div class="d-grid mb-3 mt-5">
                                                                                <button class="btn btn-primary" type="submit" id="transferSubmitBtn"><?= lang('submit') ?></button>
									</div>
								</form>

							</div>
							<div class="col-lg-6" style="margin-top: 38px;">
								<div class="card border border-danger">
                                                                        <div class="card-header bg-transparent border-danger">
                                                                                <h5 class="my-0 text-danger"><i class="mdi mdi-block-helper me-3"></i><?= lang('commission_transfer_steps_title') ?></h5>
                                                                        </div>
                                                                        <div class="card-body">
                                                                                <h5 class="card-title"><?= lang('commission_transfer_step_one') ?></h5>
                                                                                <h5 class="card-title"><?= sprintf(lang('commission_transfer_step_two'), ConfigData['support_mail']) ?></h5><br/>
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

			</div>.

			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">

                                                        <h4 class="card-title"><?= lang('commission_transfer_to_mt5_list') ?></h4><hr/>
							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
                                                                <tr>
                                                                        <th><?= lang('sr_no') ?></th>
                                                                        <th><?= lang('date') ?></th>
                                                                        <th><?= lang('from_account') ?></th>
                                                                        <th><?= lang('to_account') ?></th>
                                                                        <th><?= lang('amount') ?></th>
                                                                        <th><?= lang('status') ?></th>
                                                                </tr>
								</thead>
								<tbody>
								<?php
								if (isset($dataItem) && $dataItem['commission_details']){
									foreach ($dataItem['commission_details'] as $key=>$item):
								?>
								<tr role="row" class="odd">
									<td><?=++$key?></td>
									<td><?=date('d-m-Y',strtotime($item->created_at))?></td>
									<td class="sorting_1"><?=$item->from_account ?></td>
									<td class="sorting_1"><?=$item->to_account ?></td>
									<td class="sorting_1">$<?=$item->transfer_amount?></td>
									<td>
                                                                                <?php if ($item->status==0): ?>
                                                                                        <span class="badge rounded-pill bg-danger"><?= lang('pending') ?></span>
                                                                                <?php elseif($item->status==1): ?>
                                                                                        <span class="badge rounded-pill bg-success"><?= lang('approved') ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<?php  endforeach; }?>
								</tbody>

							</table>

						</div>
					</div>
				</div> <!-- end col -->
			</div> <!-- end row -->

		</div> <!-- container-fluid -->
	</div>

</div>

<?php unset($_SESSION['error_transfer']); ?>

<script>
        var balanceWarning = "<?= lang('transfer_balance_should_be_less_or_equal') ?>";

        $(document).on('change', 'select#from_mt5_login_id', function() {

		//$('select#to_mt5_login_id').val('1001980').remove();

		var loginID=$(this).val();
		//$("select#to_mt5_login_id option[value='"+loginID+"']").remove();
		var post_data = {
			'mt5_login_id':loginID,
			'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
		};

		var url = "<?php echo base_url();?>user/get-commission-amount";

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
                        $('span#amountErr').html(balanceWarning.replace('%s', oldBalance))
		}else{
			$('#transferSubmitBtn').removeClass('disable');
			$('span#amountErr').html('');
                        $('h5#withdrawAmount').html('$'+newBalance);
                        $('h5#newBalance').html('$'+available);
                }
        });

</script>


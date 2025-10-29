<?php
$errorObject=$requestData='';
if (isset($_SESSION['error_open_account'])){
	$errorObject	=json_decode($_SESSION['error_open_account']);
}
?>

<style>
	:root {
		--card-line-height: 1.2em;
		--card-padding: 1em;
		--card-radius: 0.5em;
		--color-green: #5664d2;
		--color-gray: #e2ebf6;
		--color-dark-gray: #c4d1e1;
		--radio-border-width: 2px;
		--radio-size: 1.5em;
	}
	select.disable-sections {
		background: #80808012;
		pointer-events: none;
	}
	div.disable-group-div {
		display: none;
	}
	div#groupListed {
		margin-top: 23px;
	}
	.submit-btn-block {
		margin-top: 16px;
	}
	button.disabled-btn {
		pointer-events: none;
	}
</style>
<div class="main-content open-account-area" id="result">
	<div class="page-content">
		<div class="container-fluid">

			<?php if (isset($_SESSION['success_trading_account'])):?>
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					<?=$_SESSION['success_trading_account']?>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
				<?php unset($_SESSION['success_trading_account']); endif; ?>

			<?php if ($errorObject): ?>
				<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<?php foreach ($errorObject as $key=>$data): if ($data && $data!=400): ?>
						<span><?=$data?></span><br/>
					<?php endif; endforeach;?>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
			<?php endif; ?>

			<div class="card">
				<div class="card-body">
					<form class="" action="<?php echo base_url()."admin/account/user-mt5-account-create"?>" method="post" id="createGroupForm">
						<div class="row">
							<div class="col-md-4">
								<label for="example-text-input" class="col-md-4 col-form-label"><?=$this->lang->line('user_name')?><span class="error">*</span></label>
								<div class="">
									<select class="form-control select2" name="unique_id" id="selectedUser" required>
										<option value=""><?=$this->lang->line('select_user')?></option>
										<?php if (isset($dataItem)):foreach ($dataItem['userlist'] as $key=>$item):?>
											<option value="<?=$item->unique_id?>"><?=$item->first_name.' '.$item->last_name .' ('.$item->unique_id.')'?></option>
										<?php endforeach; endif; ?>
									</select>
								</div>
								<span class="error"><?=isset($errorObject->mt5_login_id)?$errorObject->mt5_login_id:''?></span>
							</div>
						</div>

						<div class="row disable-group-div" id="groupListed">
							<?php if ($dataItem):foreach ($dataItem['groupList'] as $key=>$item):?>
								<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
									<label class="card">
										<input name="plan" class="radio selected-<?=$key?>" type="radio" data-key="<?=$key?>" data-groupid="<?=$item->id?>" data-groupname="<?=$item->mt5_group_name?>" id="createGroupCard">
										<span class="plan-details">
								  <span class="plan-type"><?=$item->group_name?></span>
								  <span class="card-text">Minimum Deposit : $<?=$item->minimum_deposit?></span>
								  <span class="card-text">Platform : MT5</span>
									<div class="leverage-card">
										<p class="card-text">Leverage :</p>
										<select class="form-control leverage-field disable-sections" id="leverageSelect">
											<option value="50">50</option>
											<option value="100">100</option>
											<option value="200">200</option>
											<option value="300">300</option>
											<option value="400">400</option>
											<option value="500">500</option>
										</select>
									</div>
								</span>
									</label>
								</div>
							<?php endforeach; endif;?>
						</div>

						<input name="group_id"  type="hidden" value="" id="group_id">
						<input name="group_name"  type="hidden" value="" id="group_name">
						<input name="leverage"  type="hidden" value="50" id="leverage">

						<input name="pass_main"  type="hidden" value="<?=isset($_SESSION['enc_pass'])?unserialize($_SESSION['enc_pass']):''?>" id="pass_main">
						<input name="pass_investor"  type="hidden" value="<?=isset($_SESSION['enc_pass'])?unserialize($_SESSION['enc_pass']):''?>" id="pass_investor">

						<div class="submit-btn-block">
							<button type="submit" class="btn btn-primary waves-effect waves-light disabled-btn" id="createTradeAccount">
								<i class="ri-check-line align-middle me-2"></i><?=$this->lang->line('create_new_account')?>
							</button>
						</div>
					</form>
				</div>
			</div>

		</div>
	</div>
</div>

<?php unset($_SESSION['error_open_account']); ?>

<script>
	$(document).on('click', '#createGroupCard', function () {



		$('#group_id').val($(this).data('groupid'));
		$('#group_name').val($(this).data('groupname'));

		$("#leverageSelect").removeClass('leverageSelect');
		$('#leverageSelect').addClass('disable-sections');
		$(this).closest('div').find('#leverageSelect').addClass('leverageSelect');
		$(this).closest('div').find('#leverageSelect').removeClass('disable-sections');

	});

	$(document).on('change', '#leverageSelect', function () {
		$('#leverage').val($(this).val());
	});
</script>

<script>
	$(document).on('change', 'select#selectedUser', function() {
		$('div#groupListed').removeClass('disable-group-div');
		$('input.selected-0').trigger('click');
		$('button#createTradeAccount').removeClass('disabled-btn');
	});
</script>

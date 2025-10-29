<?php

$errorObject=$requestData='';
if (isset($_SESSION['error_open_account'])){
	$errorObject	=json_decode($_SESSION['error_open_account']);
}
$getSettingsModel =$this->db->query("SELECT dep_with_rate,rate_currency FROM setting")->row();
$dep_with_rate=$rate_currency='';
if ($getSettingsModel){
	$dep_with_rate=$getSettingsModel->dep_with_rate;
	$rate_currency=$getSettingsModel->rate_currency;
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

			<form class="" action="<?php echo base_url()."user/user-create-live-account"?>" method="post" id="createGroupForm">
				<div class="row">
					<?php if ($dataItem):foreach ($dataItem as $key=>$item):?>
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
						<label class="card">
							<input name="plan" class="radio" type="radio" data-key="<?=$key?>" data-groupid="<?=$item->id?>" data-groupname="<?=$item->mt5_group_name?>" id="createGroupCard">
							<span class="plan-details">
								  <span class="plan-type"><?=$item->group_name?></span>
								  <span class="card-text"><?= lang('label_minimum_deposit') ?> : $<?=$item->minimum_deposit?></span>
								  <span class="card-text"><?= lang('platform') ?> : MT5</span>

								  <?php if (ConfigData['prefix']=='IGM' || ConfigData['prefix']=='UFX' || ConfigData['prefix']=='TG'): ?>
									  <span class="card-text"><?= lang('commission') ?> : <?=$item->commission?></span>
                                                                  <span class="card-text"><?= lang('swap') ?> : <?= ($item->swap==1) ? lang('yes') : lang('no') ?></span>
								  <?php endif; ?>

									<div class="leverage-card">
										<p class="card-text"><?= lang('leverage') ?> :</p>
										<select class="form-control leverage-field disable-sections" id="leverageSelect">
										<?php if (ConfigData['prefix']=='IWY'): ?>
											<option value="50">50</option>
											<option value="100">100</option>
											<option value="100">200</option>
										<?php elseif (ConfigData['prefix']=='IGM' || ConfigData['prefix']=='UFX' || ConfigData['prefix']=='TG'): ?>
											<option value="100">100</option>
											<option value="200">200</option>
											<option value="300">300</option>
											<option value="400">400</option>
											<option value="500">500</option>
										<?php else: ?>
											<option value="50">50</option>
											<option value="100">100</option>
											<option value="200">200</option>
											<option value="300">300</option>
											<option value="400">400</option>
											<option value="500">500</option>
										<?php endif; ?>
										</select>
									</div>

									<?php if (ConfigData['enable_deposit_withdraw_rate']): ?>
										<div class="leverage-card">
											<p class="card-text"><?= lang('acc_type') ?> :</p>
											<select class="form-control leverage-field disable-sections" id="accType" name="accountTypeName">
												<?php
												 if (ConfigData['prefix']!='TG'):
												?>
													<option value="1"><?= lang('live_rate') ?></option>
												<?php endif; ?>

													<?php if ($dep_with_rate && $rate_currency){ ?>
													<option value="2" selected><?= lang('fixed_rate') ?> <?php echo "(1 USD=".$dep_with_rate.' '.$rate_currency.')'; ?></option>
													<?php } ?>
											</select>
										</div>
									<?php endif; ?>

								</span>
						</label>
					</div>
					<?php endforeach; endif;?>
				</div>

				<input name="group_id"  type="hidden" value="" id="group_id">
				<input name="group_name"  type="hidden" value="" id="group_name">
				<input name="leverage"  type="hidden" value="50" id="leverage">
				<input name="accountType"  type="hidden" value="2" id="accountType">

				<input name="pass_main"  type="hidden" value="<?=isset($_SESSION['enc_pass'])?unserialize($_SESSION['enc_pass']):''?>" id="pass_main">
				<input name="pass_investor"  type="hidden" value="<?=isset($_SESSION['enc_pass'])?unserialize($_SESSION['enc_pass']):''?>" id="pass_investor">

				<div class="submit-btn-block">
					<button type="submit" class="btn btn-primary waves-effect waves-light">
						<i class="ri-check-line align-middle me-2"></i> <?= lang('create_new_account') ?>
					</button>
				</div>
			</form>

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

		$("#accType").removeClass('accType');
		$('#accType').addClass('disable-sections');
		$(this).closest('div').find('#accType').addClass('accType');
		$(this).closest('div').find('#accType').removeClass('disable-sections');

	});

	$(document).on('change', '#leverageSelect', function () {
		$('#leverage').val($(this).val());
	});

	$(document).on('change', 'select#accType', function () {
		$('#accountType').val($(this).val());
	});

</script>

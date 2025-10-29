<?php
$errorObject=$requestData='';
if (isset($_SESSION['error_group']) && isset($_SESSION['request_data'])){
	$errorObject	=json_decode($_SESSION['error_group']);
	$requestData 	=json_decode($_SESSION['request_data']);
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
						<h4 class="mb-sm-0"><?=$this->lang->line('create_group')?></h4>

						<div class="page-title-right">
							<ol class="breadcrumb m-0">
								<li class="breadcrumb-item">
									<a href="javascript: void(0);"><?=$this->lang->line('home')?></a>
								</li>
								<li class="breadcrumb-item active"><?=$this->lang->line('create_group')?></li>
							</ol>
						</div>

					</div>
				</div>
			</div>
			<!-- end page title -->

			<div class="row">
				<div class="col-lg-12">
					<?php if (isset($errorObject->mt5_error)):?>
						<div class="alert alert-danger alert-dismissible fade show" role="alert">
							<?=$errorObject->mt5_error?>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					<?php endif; ?>

					<div class="card">
						<div class="card-body d-flex">
							<form action="<?=base_url('store-group')?>" method="post" id="createGroupForm">
								<div class="col-md-8 row mb-3">
									<div class="col-sm-12">
										<label for="group_name" class="col-form-label">
											<?=$this->lang->line('label_group_name')?> <span class="error">*</span>
										</label>
										<input
											id="group_name"
											name="group_name"
											type="text"
											class="form-control"
											placeholder="<?=$this->lang->line('placeholder_group_name')?>"
											value="<?=isset($requestData->group_name)? $requestData->group_name: ''?>"
										>
										<span class="error"><?=isset($errorObject->group_name)? $errorObject->group_name: ''?></span>
									</div>

									<div class="col-sm-12">
										<label for="mt5_group_name" class="col-form-label">
											<?=$this->lang->line('label_mt5_group_name')?> <span class="error">*</span>
										</label>
										<input
											id="mt5_group_name"
											name="mt5_group_name"
											type="text"
											class="form-control"
											placeholder="<?=$this->lang->line('placeholder_mt5_group_name')?>"
											value="<?=isset($requestData->mt5_group_name)? $requestData->mt5_group_name: ''?>"
										>
										<span class="error"><?=isset($errorObject->mt5_group_name)? $errorObject->mt5_group_name: ''?></span>
									</div>

									<div class="col-sm-12">
										<label for="minimum_deposit" class="col-form-label">
											<?=$this->lang->line('label_minimum_deposit')?> <span class="error">*</span>
										</label>
										<input
											id="minimum_deposit"
											name="minimum_deposit"
											type="number"
											class="form-control"
											placeholder="<?=$this->lang->line('placeholder_minimum_deposit')?>"
											value="<?=isset($requestData->minimum_deposit)? $requestData->minimum_deposit: ''?>"
										>
										<span class="error"><?=isset($errorObject->minimum_deposit)? $errorObject->minimum_deposit: ''?></span>
									</div>

									<div class="col-sm-12">
										<label for="spread_from" class="col-form-label">
											<?=$this->lang->line('label_spread_from')?> <span class="error">*</span>
										</label>
										<input
											id="spread_from"
											name="spread_from"
											type="text"
											class="form-control"
											placeholder="<?=$this->lang->line('placeholder_spread_from')?>"
											value="<?=isset($requestData->spread_from)? $requestData->spread_from: ''?>"
										>
										<span class="error"><?=isset($errorObject->spread_from)? $errorObject->spread_from: ''?></span>
									</div>

									<div class="col-sm-12">
										<label for="commission" class="col-form-label">
											<?=$this->lang->line('label_commission')?> <span class="error">*</span>
										</label>
										<input
											id="commission"
											name="commission"
											type="text"
											class="form-control"
											placeholder="<?=$this->lang->line('placeholder_commission')?>"
											value="<?=isset($requestData->commission)? $requestData->commission: ''?>"
										>
										<span class="error"><?=isset($errorObject->commission)? $errorObject->commission: ''?></span>
									</div>

									<div class="col-md-12 mt-3">
										<label for="swap" class="form-label">
											<?=$this->lang->line('label_swap')?> <span class="error">*</span>
										</label>
										<select
											id="swap"
											name="swap"
											class="form-select"
											required
										>
											<option disabled selected value="">
												<?=$this->lang->line('choose_swap')?>
											</option>
											<option value="1" <?=isset($requestData->swap) && $requestData->swap==1? 'selected': ''?>>
												<?=$this->lang->line('swap_yes')?>
											</option>
											<option value="2" <?=isset($requestData->swap) && $requestData->swap==2? 'selected': ''?>>
												<?=$this->lang->line('swap_no')?>
											</option>
										</select>
										<span class="error"><?=isset($errorObject->swap)? $errorObject->swap: ''?></span>
									</div>

									<div class="col-sm-12">
										<label class="col-form-label">
											<?=$this->lang->line('label_group_status')?> <span class="error">*</span>
										</label>
										<div class="d-flex">
											<div class="form-check mb-3">
												<input
													class="form-check-input"
													type="radio"
													name="status"
													id="status_active"
													value="1"
													<?=isset($requestData->status) && $requestData->status==1? 'checked': ''?>
												>
												<label class="form-check-label" for="status_active">
													<?=$this->lang->line('radio_active')?>
												</label>
											</div>
											<div class="form-check mb-3 ms-3">
												<input
													class="form-check-input"
													type="radio"
													name="status"
													id="status_inactive"
													value="2"
													<?=isset($requestData->status) && $requestData->status==2? 'checked': ''?>
												>
												<label class="form-check-label" for="status_inactive">
													<?=$this->lang->line('radio_inactive')?>
												</label>
											</div>
										</div>
										<span class="error"><?=isset($errorObject->status)? $errorObject->status: ''?></span>
									</div>

									<div class="d-grid mb-3 mt-5">
										<button type="submit" class="btn btn-primary btn-lg waves-effect waves-light">
											<?=$this->lang->line('button_create_group')?>
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- end row -->

		</div> <!-- container-fluid -->
	</div>
</div>
<!-- end main content-->
<?php unset($_SESSION['error_group']); ?>

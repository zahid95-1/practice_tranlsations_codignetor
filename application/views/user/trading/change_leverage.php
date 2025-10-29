<?php
$errorObject='';
if (isset($_SESSION['error_change_leverage'])){
	$errorObject	=json_decode($_SESSION['error_change_leverage']);
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
						<h4 class="mb-sm-0"><?= lang('update_mt5_leverage') ?></h4>

						<div class="page-title-right">
							<ol class="breadcrumb m-0">
                                                                <li class="breadcrumb-item"><a href="javascript: void(0);"><?= lang('home') ?></a></li>
								<li class="breadcrumb-item active"><?= lang('update_mt5_leverage') ?></li>
							</ol>
						</div>

					</div>
				</div>
			</div>
			<!-- end page title -->

			<div class="row">

				<?php if (isset($_SESSION['success_change_leverage'])):?>
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						<?=$_SESSION['success_change_leverage']?>
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php unset($_SESSION['success_change_leverage']); endif; ?>

				<?php if (isset($errorObject->mt5_error)): ?>
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
						<span><?=$errorObject->mt5_error?></span><br/>
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php endif; ?>

				<form class="" action="<?php echo base_url()."user/update-leverage"?>" method="post" id="updateLeverage">

					<?php if (isset($_SESSION['success_leverage_update'])):?>
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							<?=$_SESSION['success_leverage_update']?>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					<?php unset($_SESSION['success_leverage_update']); endif; ?>

					<div class="col-lg-12">
						<div class="card">
							<div class="card-body d-flex">
								<div class="col-md-4 row mb-3">
									<div class="col-sm-12">
										<label for="example-text-input" class="col-sm-12 col-form-label"><?= lang('label_account_id') ?>*</label>
										<div class="mb-3">
											<select class="form-control select2" name="mt5_login_id">
												<option value=""><?= lang('select_account_id') ?></option>
												<?php if (isset($dataItem)):foreach ($dataItem as $key=>$item):?>
													<option value="<?=$item->mt5_login_id?>"><?=$item->mt5_login_id?></option>
												<?php endforeach; endif; ?>
											</select>
										</div>
										<span class="error"><?=isset($errorObject->mt5_login_id)?$errorObject->mt5_login_id:''?></span>
									</div>

									<div class="col-md-12 mt-3">
										<div class="mb-3">
											<label for="validationCustom04" class="form-label"><?= lang('leverage') ?> <span class="error">*</span></label>
											<select class="form-select select2" id="validationCustom04" name="leverage">
												<option selected disabled value=""><?= lang('choose_leverage') ?></option>
												<?php if (ConfigData['prefix']=='IWY'): ?>
													<option value="50">50</option>
													<option value="100">100</option>
													<option value="100">200</option>
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
										<span class="error"><?=isset($errorObject->leverage)?$errorObject->leverage:''?></span>
									</div>

									<div class="d-grid mb-3 mt-5">
										<button class="btn btn-primary" type="submit"><?= lang('update_mt5_leverage') ?></button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<!-- end row -->

		</div> <!-- container-fluid -->
	</div>

</div>
<!-- end main content-->
<?php unset($_SESSION['error_change_leverage']); ?>

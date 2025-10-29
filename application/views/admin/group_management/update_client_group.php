<?php
$errorObject='';
if (isset($_SESSION['error_client_group'])){
	$errorObject	=json_decode($_SESSION['error_client_group']);
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
						<h4 class="mb-sm-0"><?=$this->lang->line('update_mt5_client_group')?></h4>

						<div class="page-title-right">
							<ol class="breadcrumb m-0">
								<li class="breadcrumb-item">
									<a href="javascript: void(0);"><?=$this->lang->line('home')?></a>
								</li>
								<li class="breadcrumb-item active"><?=$this->lang->line('update_mt5_client_group')?></li>
							</ol>
						</div>

					</div>
				</div>
			</div>
			<!-- end page title -->

			<div class="row">
				<form action="<?=base_url('change-client-group')?>" method="post" id="updateClientGroup">
					<?php if (isset($errorObject->mt5_error)): ?>
						<div class="alert alert-danger alert-dismissible fade show" role="alert">
							<?=$errorObject->mt5_error?>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?=$this->lang->line('close')?>"></button>
						</div>
					<?php endif; ?>

					<?php if (isset($_SESSION['success_client_group'])): ?>
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							<?=$_SESSION['success_client_group']?>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?=$this->lang->line('close')?>"></button>
						</div>
						<?php unset($_SESSION['success_client_group']); ?>
					<?php endif; ?>

					<div class="col-lg-12">
						<div class="card">
							<div class="card-body d-flex">
								<div class="col-md-4 row mb-3">
									<div class="col-sm-12">
										<label for="mt5_login_id" class="col-form-label">
											<?=$this->lang->line('label_user_name')?> <span class="error">*</span>
										</label>
										<div class="mb-3">
											<select class="form-control select2" name="mt5_login_id" id="mt5_login_id">
												<option value=""><?=$this->lang->line('select_user')?></option>
												<?php if (isset($dataItem['userList'])): foreach ($dataItem['userList'] as $item): ?>
													<option value="<?=$item->mt5_login_id?>">
														<?=$item->first_name?> <?=$item->last_name?> (<?=$item->mt5_login_id?>) (<?=$item->group_name?>)
													</option>
												<?php endforeach; endif; ?>
											</select>
										</div>
										<span class="error"><?=isset($errorObject->mt5_login_id)? $errorObject->mt5_login_id: ''?></span>
									</div>

									<div class="col-sm-12 mt-3">
										<label for="group_id" class="form-label">
											<?=$this->lang->line('label_select_group')?> <span class="error">*</span>
										</label>
										<select class="form-select select2" id="group_id" name="group_id">
											<option disabled selected value=""><?=$this->lang->line('choose_group')?></option>
											<?php if (isset($dataItem['groupList'])): foreach ($dataItem['groupList'] as $item): ?>
												<option value="<?=$item->id?>"><?=$item->group_name?></option>
											<?php endforeach; endif; ?>
										</select>
										<span class="error"><?=isset($errorObject->group_id)? $errorObject->group_id: ''?></span>
									</div>

									<div class="d-grid mb-3 mt-5">
										<button type="submit" class="btn btn-primary">
											<?=$this->lang->line('button_update_client_group')?>
										</button>
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
<?php unset($_SESSION['error_client_group']); ?>

<?php
$errorObject=$requestData='';
if (isset($_SESSION['error_new_user']) && isset($_SESSION['error_new_user'])){
    $errorObject	=json_decode($_SESSION['error_new_user']);
}

$getRoleList =$this->db->query("SELECT * FROM `roles` where status=1")->result();
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
						<h4 class="mb-sm-0"><?=$this->lang->line('create_manager')?></h4>
					</div>
				</div>
			</div>
			<!-- end page title -->

			<div class="row">
				<div class="col-lg-12">
					<?php if (isset($errorObject->mt5_error)): ?>
						<div class="alert alert-danger alert-dismissible fade show" role="alert">
							<?=$errorObject->mt5_error?>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					<?php endif; ?>

					<div class="card">
						<div class="card-body d-flex">
							<form action="<?=base_url('admin/create-manager')?>" method="post" id="createManager">
								<div class="col-md-8 row mb-3">

									<div class="col-sm-12">
										<label class="col-sm-12 col-form-label"><?=$this->lang->line('first_name')?> <span class="error">*</span></label>
										<input class="form-control" type="text" name="first_name" placeholder="<?=$this->lang->line('enter_first_name')?>">
										<span class="error"><?=isset($errorObject->first_name)?$errorObject->first_name:''?></span>
									</div>

									<div class="col-sm-12">
										<label class="col-sm-12 col-form-label"><?=$this->lang->line('last_name')?> <span class="error">*</span></label>
										<input class="form-control" type="text" name="last_name" placeholder="<?=$this->lang->line('enter_last_name')?>">
										<span class="error"><?=isset($errorObject->last_name)?$errorObject->last_name:''?></span>
									</div>

									<div class="col-sm-12">
										<label class="col-sm-12 col-form-label"><?=$this->lang->line('email')?> <span class="error">*</span></label>
										<input class="form-control" type="email" name="email" placeholder="<?=$this->lang->line('enter_email')?>">
										<span class="error"><?=isset($errorObject->email)?$errorObject->email:''?></span>
									</div>

									<div class="col-sm-12">
										<label class="col-sm-12 col-form-label"><?=$this->lang->line('phone')?> <span class="error">*</span></label>
										<input class="form-control" type="text" name="phone" placeholder="<?=$this->lang->line('enter_phone')?>">
										<span class="error"><?=isset($errorObject->phone)?$errorObject->phone:''?></span>
									</div>

									<div class="col-sm-12">
										<label class="col-sm-12 col-form-label"><?=$this->lang->line('password')?> <span class="error">*</span></label>
										<input class="form-control" type="password" name="password" placeholder="<?=$this->lang->line('enter_password')?>">
										<span class="error"><?=isset($errorObject->password)?$errorObject->password:''?></span>
									</div>

									<div class="col-md-12 mt-3">
										<div class="mb-3">
											<label for="validationCustom04" class="form-label"><?=$this->lang->line('role')?> <span class="error">*</span></label>
											<select class="form-select" id="validationCustom04" name="role">
												<option value=""><?=$this->lang->line('select_role')?></option>
												<?php foreach ($getRoleList as $data): ?>
													<option value="<?=$data->role_id?>"><?=$data->role_name?></option>
												<?php endforeach; ?>
											</select>
											<span class="error"><?=isset($errorObject->role)?$errorObject->role:''?></span>
										</div>
									</div>

									<div class="d-grid mb-3 mt-5">
										<button type="submit" class="btn btn-primary btn-lg waves-effect waves-light"><?=$this->lang->line('create_manager')?></button>
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
<?php unset($_SESSION['error_new_user']); ?>

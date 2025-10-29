<?php
$errorObject=$requestData='';
if (isset($_SESSION['error_new_user'])){
	$errorObject	=json_decode($_SESSION['error_new_user']);
}

?>

<?php
$getCountry = $this->db->query("SELECT id,nicename FROM country");
?>
<style>
	.page-content {
		/*padding: calc(39px + 24px) calc(24px / 2) 60px calc(24px / 2);*/
	}
</style>
<div class="main-content" id="result">
	<div class="page-content">
		<div class="container-fluid">

			<!-- start page title -->
			<div class="row">
				<div class="col-12">
					<div class="page-title-box d-sm-flex align-items-center justify-content-between">
						<h4 class="mb-sm-0"><?=$this->lang->line('registered_account')?></h4>
						<div class="page-title-right">
							<ol class="breadcrumb m-0">
								<li class="breadcrumb-item"><a href="javascript: void(0);"><?=$this->lang->line('home')?></a></li>
								<li class="breadcrumb-item active"><?=$this->lang->line('registered_account')?></li>
							</ol>
						</div>

					</div>
				</div>
			</div>
			<!-- end page title -->
			<!-- end row -->
			<div class="row">
				<div class="col-xl-12">
					<div class="card">
						<div class="card-body">
							<?php
							echo validation_errors();

							if(isset($errorMsg))
							{ ?>
								<div class="error-msg">
									<?php echo $errorMsg; ?>
								</div>
								<?php
								unset($errorMsg);
							}
							?>
							<form class="custom-validation" action="<?php echo base_url()."save-new-user"?>" method="post">
								<div class="auth-form-group-custom mb-4">
									<i class="ri-bring-forward auti-custom-input-icon"></i>
									<label><?=$this->lang->line('referral_id')?></label>
									<input type="text" class="form-control" id="parent_id" placeholder="<?=$this->lang->line('placeholder_referral_id')?>" value="<?=ConfigData['admin_prefix']?>" name="parent_id" readonly>
								</div>

								<div class="auth-form-group-custom mb-4">
									<i class="ri-user-2-line auti-custom-input-icon"></i>
									<label><?=$this->lang->line('first_name')?></label>
									<input type="text" class="form-control" id="first_name" placeholder="<?=$this->lang->line('placeholder_first_name')?>"  name="first_name" required>
								</div>

								<div class="auth-form-group-custom mb-4">
									<i class="ri-user-2-line auti-custom-input-icon"></i>
									<label><?=$this->lang->line('last_name')?></label>
									<input type="text" class="form-control" id="last_name" placeholder="<?=$this->lang->line('placeholder_last_name')?>" name="last_name" required>
								</div>

								<div class="auth-form-group-custom mb-4">
									<i class="ri-flag-2-line auti-custom-input-icon"></i>
									<label><?=$this->lang->line('country')?></label>
									<select name="country" id="country" class="form-control">
										<option><?=$this->lang->line('select_country')?></option>
										<?php
										foreach($getCountry->result() as $Country){
											$countryId = $Country->id ;
											$countryName = $Country->nicename ;
											?>
											<option value="<?php echo $countryId ?>"><?php echo $countryName ?></option>
											<?php
										}
										?>
									</select>
								</div>

								<div class="auth-form-group-custom mb-4">
									<i class="ri-mail-line auti-custom-input-icon"></i>
									<label><?=$this->lang->line('email')?></label>
									<input type="email" class="form-control" id="useremail" placeholder="<?=$this->lang->line('placeholder_email')?>" name="email" required>
									<?php if (isset($errorObject->unable_save)) :?>
									<span style="color:red;"><?=$errorObject->unable_save?></span>
									<?php endif; ?>
								</div>

								<div class="auth-form-group-custom mb-4">
									<i class="ri-lock-2-line auti-custom-input-icon"></i>
									<label><?=$this->lang->line('password')?></label>
									<input pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" type="password" class="form-control" id="userpassword" placeholder="<?=$this->lang->line('placeholder_password')?>" name="password" required>
								</div>

								<div class="auth-form-group-custom mb-4">
									<i class="ri-phone-fill auti-custom-input-icon"></i>
									<label><?=$this->lang->line('phone')?></label>
									<input type="text" class="form-control" id="phone" placeholder="<?=$this->lang->line('placeholder_phone')?>" name="phone" required>
								</div>

								<div class="auth-form-group-custom mb-4">
									<i class=" ri-server-line auti-custom-input-icon"></i>
									<label><?=$this->lang->line('birth_date')?></label>
									<input class="form-control" type="date" value="2011-08-19" id="example-date-input" name="birth_date">
								</div>

								<div class="mb-0 col-md-6">
									<div>
										<button type="submit" class="btn btn-primary waves-effect waves-light me-1">
											<?=$this->lang->line('create_user')?>
										</button>
									</div>
								</div>
							</form>

						</div>
					</div>
				</div> <!-- end col -->
			</div> <!-- end row -->

		</div> <!-- container-fluid -->
	</div>
</div>

<?php unset($_SESSION['error_new_user']); ?>
<script>
	 $('#useremail').parsley();
	 $('#phone').parsley();
</script>

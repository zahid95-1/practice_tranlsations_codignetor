<?php
$getReferralId =isset($_REQUEST["reffid"])?$_REQUEST["reffid"]:ConfigData['admin_prefix']; //Admin Key
$getReferralLinkId =isset($_REQUEST["link"])?$_REQUEST["link"]:0; //Link Key
/*===================GetUserInfo=========================*/
$getUserData = $this->db->query("SELECT* FROM users u WHERE unique_id = '$getReferralId'")->row();
$getCountry = $this->db->query("SELECT* FROM country ")->result();
$errorObject=$requestData='';
if (isset($_SESSION['error_new_registration'])){
	$errorObject	=json_decode($_SESSION['error_new_registration']);
}
?>
<?php
$signUpBgImage ='assets/images/Sign Up.jpg';
$getSettingsModel =$this->db->query("SELECT sign_up_bg_image,logo_image,favicon_image,meta_descriptions,meta_title FROM setting")->row();

if ( $getSettingsModel ){
	if ($getSettingsModel->sign_up_bg_image) {
		$signUpBgImage = base_url() . "assets/settings/bg_image/" . $getSettingsModel->sign_up_bg_image;
	}
}

$logoImage =base_url()."assets/front_end_logo/".ConfigData['frontend_logo']."";
$favIconImage=base_url()."assets/images/logo-light.png";

$metaTitle=ConfigData['site_name'];
$metaDescriptions=ConfigData['site_name'];
if ( $getSettingsModel ){
	if ($getSettingsModel->favicon_image) {
		$favIconImage=base_url()."assets/settings/logo/".$getSettingsModel->favicon_image;
	}
	if ($getSettingsModel->meta_title){
		$metaTitle=$getSettingsModel->meta_title;
	}
	if ($getSettingsModel->meta_descriptions){
		$metaDescriptions=$getSettingsModel->meta_descriptions;
	}
}
?>
<!doctype html>
<html lang="en">
<head>

	<meta charset="utf-8" />
	<title><?=$metaTitle?> | Registrations</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta content="<?=$metaDescriptions?>" name="description" />
	<meta content="Themesdesign" name="author" />
	<!-- App favicon -->
	<link rel="shortcut icon" href="<?=$favIconImage?>">

	<!-- Bootstrap Css -->
	<link href="<?=base_url()?>/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
	<!-- Icons Css -->
	<link href="<?=base_url()?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
	<!-- App Css-->
	<link href="<?=base_url()?>assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

	<link href="<?=base_url()?>/assets/css/custom.css?t=34435" rel="stylesheet" type="text/css" />
	<link
			rel="stylesheet"
			href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css"
	/>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>

	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body class="auth-body-bg">
<style>
	.auth-form-group-custom.mb-4 div {
		display: none;
	}
	input.error {
		border: 1px solid red;
	}
</style>

<style>
	.authentication-bg-1{
		background-image: url("<?=$signUpBgImage?>");
		height: 100vh;
		background-size: cover;
		background-position: center; }
		.auth-form-group-custom .form-control {
		height: 55px;
		padding-top: 28px;
		padding-left: 59px;
	}
	.mb-4{
		margin-bottom: 10px!important;
	}
	.iti--allow-dropdown .iti__flag-container:hover .iti__selected-flag{
		background: none;
	}
	.iti--allow-dropdown .iti__flag-container, .iti--separate-dial-code .iti__flag-container {
		right: auto;
		left: 13px!important;
	}
	.auth-form-group-custom.mb-4 div {
		display: block!important;
	}
</style>

<div class="container-fluid p-0">
	<div class="row g-0">
		<div class="col-lg-4">
			<div class="authentication-page-content p-4 d-flex align-items-center min-vh-100">
				<div class="w-100">
					<div class="row justify-content-center">
						<div class="col-lg-9">
							<div>
								<div class="text-center">
									<div style="" class="registrations-logo">
										<a href="<?=base_url()?>" class="logo"><img src="<?=$logoImage?>" height="100%" width="60%" alt="logo"></a>
									</div>

									<h4 class="font-size-18"><?=$this->lang->line('register_now');?></h4>
								</div>

								<div class="p-2">
									<?php echo validation_errors(); ?>
									<form class="" action="<?php echo base_url()."user-create-v2/?reffid=$getReferralId&link=$getReferralLinkId"?>" method="post" id="registrationForm">

										<div class="auth-form-group-custom mb-4">
											<i class="ri-lock-2-line auti-custom-input-icon"></i>
											<label for="userpassword"><?=$this->lang->line('password');?> <span style="color: red">*</span></label>
											<input type="password" class="form-control" id="password_error" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=!]).{8,}"  title="<?php echo $this->lang->line('password_requirements'); ?>"   min-length="8" max-length="8" required placeholder="<?php echo $this->lang->line('enter_password'); ?>" name="password">
											<?php if (isset($errorObject->password)) :?>
												<span style="color:red;"><?=$errorObject->password?></span>
											<?php endif; ?>
										</div>

										<?php if (ConfigData['activate_sate_city']===true){ ?>
										<div class="auth-form-group-custom mb-4">
											<i class="ri-arrow-left-circle-line auti-custom-input-icon"></i>
											<label for="state_error"><?php echo $this->lang->line('state'); ?></label>
											<input type="text" class="form-control" id="state_error" placeholder="<?php echo $this->lang->line('enter_state_name'); ?>" name="state">
											<span class="state-error error"></span>
										</div>

										<div class="auth-form-group-custom mb-4">
											<i class="ri-arrow-left-circle-line auti-custom-input-icon"></i>
											<label for="city_error"><?php echo $this->lang->line('city'); ?></label>
											<input type="text" class="form-control" id="city_error" placeholder="<?php echo $this->lang->line('enter_city_name'); ?>" name="city">
											<span class="city-error error"></span>
										</div>
										<?php } ?>

										<div class="auth-form-group-custom mb-4">
											<i class="ri-lock-2-line auti-custom-input-icon"></i>
											<label for=""><?php echo $this->lang->line('country'); ?> <span style="color: red">*</span></label>
											<select class="form-control" name="country" id="country">
												<?php foreach($getCountry as $getCountryData){ ?>
													<option value="<?php echo $getCountryData->id  ?>" datacode="<?=$getCountryData->iso?>"><?php echo $getCountryData->name ?></option>
												<?php } ?>
											</select>
											<span class="password-error error"></span>
										</div>

										<div class="row mb-4">
											<input id="phone" type="number" name="phone" class="form-control" />
											<?php if (isset($errorObject->phone)) :?>
												<span style="color:red;"><?=$errorObject->phone?></span>
											<?php endif; ?>
										</div>

										<div class="auth-form-group-custom mb-4">
											<i class=" ri-server-line auti-custom-input-icon"></i>
											<label for="first_name"><?php echo $this->lang->line('birth_date'); ?></label>
											<input class="form-control" type="date" value="2011-08-19" id="birth_date_error" name="birth_date">
											<span class="birth_date-error error"></span>
										</div>

										<div class="mb-3 auth-form-group-custom mb-4">
											<div class="g-recaptcha" data-sitekey="<?=ConfigData['recaptcha_site_key']?>"></div>
											<?php if (isset($errorObject->grecaptcharesponse)){ ?>
												<span style="color: red"><?=$errorObject->grecaptcharesponse?></span>
											<?php } ?>
											<?php if (isset($errorObject->grecaptcharesponse)) :?>
												<span style="color:red;"><?=$errorObject->grecaptcharesponse?></span>
											<?php endif; ?>
										</div>

										<div class="text-center" style="margin-bottom: 10px;">
											<span id="successMessage" style="color:green;"></span>
										</div>

										<input type="checkbox" id="tnc" name="tnc" required>
										<label for="tnc"><a href="<?=ConfigData['terms_conditions']?>" target="_blank"><?php echo $this->lang->line('accept_terms_conditions'); ?></a></label>
										<br>
										<div class="text-center">
											<button class="btn btn-primary w-md waves-effect waves-light" type="submit" id="registrationFormBtn" style="width: 100%!important;margin-top: 11px;"><?php echo $this->lang->line('register_now'); ?></button>
										</div>
									</form>
								</div>

								<div class="text-center">
									<p><?php echo $this->lang->line('already_have_account'); ?> ? <a href="<?=base_url()?>login" class="fw-medium text-primary"> <?php echo $this->lang->line('login_here'); ?> </a> </p>
								</div>
							</div>

							<div style="margin: 0 auto;text-align: center">
								<form method="get" action="<?=base_url('LanguageSwitcher/switchLang')?>">
									<select class="form-control" name="language" onchange="window.location.href='<?=base_url()?>LanguageSwitcher/switchLang/'+this.value;" style="width: 40%">
										<option value="english" <?=($this->session->userdata('site_lang')=='english')?'selected':''?>>English</option>
										<option value="chinese" <?=($this->session->userdata('site_lang')=='chinese')?'selected':''?>>中文</option>
									</select>
								</form>
							</div>


						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-8 login-img">
			<div class="authentication-bg-1 position-relative">
				<div class="bg-overlay"></div>
			</div>
		</div>
	</div>
</div>

<?php unset($_SESSION['error_new_registration']); ?>

<!-- JAVASCRIPT -->
<!-- bs custom file input plugin -->
<script src="<?=base_url()?>/assets/libs/bs-custom-file-input/bs-custom-file-input.min.js"></script>

<script src="<?=base_url()?>/assets/js/pages/form-element.init.js"></script>

<script src="<?=base_url()?>/assets/libs/jquery/jquery.min.js"></script>
<script src="<?=base_url()?>/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?=base_url()?>/assets/libs/metismenu/metisMenu.min.js"></script>
<script src="<?=base_url()?>/assets/libs/simplebar/simplebar.min.js"></script>
<script src="<?=base_url()?>/assets/libs/node-waves/waves.min.js"></script>

<script src="<?=base_url()?>/assets/js/app.js"></script>
<script type="text/javascript">
	const countrySelect = document.querySelector("#country");
	const phoneInputField = document.querySelector("#phone");
	const phoneInput = window.intlTelInput(phoneInputField, {
		utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js",
	});

	countrySelect.addEventListener("change", function() {
		var countryCode 	=$('select#country').find(':selected').attr('datacode')||'';
		phoneInput.setCountry(countryCode); // Set the country code for the phone input
	});


</script>

</body>
</html>

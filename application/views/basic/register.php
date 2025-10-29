<?php
$getReferralId =isset($_REQUEST["reffid"])?$_REQUEST["reffid"]:ConfigData['admin_prefix']; //Admin Key
$getReferralLinkId =isset($_REQUEST["link"])?$_REQUEST["link"]:0; //Link Key


/*===================GetUserInfo=========================*/
$getUserData = $this->db->query("SELECT* FROM users u WHERE unique_id = '$getReferralId'")->row();

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

$hideBtn=false;
if (ConfigData['prefix'] != 'UFX'){
	$hideBtn=true;
}elseif (ConfigData['prefix'] != 'TG'){
	$hideBtn=true;
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

									<h4 class="font-size-18"><?=$this->lang->line('signup_title')?></h4>
								</div>

								<div class="p-2">
									<?php echo validation_errors(); ?>
									<form class="" action="<?php echo base_url()."user-create-second-one?reffid=$getReferralId&link=$getReferralLinkId"?>" method="post" id="registrationForm">

										<div class="auth-form-group-custom mb-4">
											<i class="ri-bring-forward auti-custom-input-icon"></i>
											<label for="first_name"><?=$this->lang->line('referral_id')?></label>
											<input type="text" class="form-control" id="parent_id" placeholder="<?=$this->lang->line('referral_id_placeholder')?>" value="<?php echo $getReferralId ?>" name="parent_id" readonly>
										</div>

										<div class="auth-form-group-custom mb-4">
											<input type="hidden" class="form-control" id="link_id" placeholder="Refferral ID" value="<?php echo $getReferralLinkId ?>" name="link_id" readonly>
										</div>

										

										<div class="auth-form-group-custom mb-4">
											<i class="ri-user-2-line auti-custom-input-icon"></i>
											<label for="first_name"><?=$this->lang->line('first_name')?> <span style="color: red">*</span></label>
											<input type="text" class="form-control" id="first_name_error" placeholder="<?=$this->lang->line('first_name_placeholder')?>" name="first_name" value="<?=isset($_SESSION['auth_first_name'])?$_SESSION['auth_first_name']:''?>">
											<?php if (isset($errorObject->first_name)) :?>
												<span style="color:red;"><?=$errorObject->first_name?></span>
											<?php endif; ?>
										</div>

										<div class="auth-form-group-custom mb-4">
											<i class="ri-user-2-line auti-custom-input-icon"></i>
											<label for="last_name"><?=$this->lang->line('last_name')?></label>
											<input type="text" class="form-control" id="last_name_error" placeholder="<?=$this->lang->line('last_name_placeholder')?>" name="last_name" value="<?=isset($_SESSION['auth_last_name'])?$_SESSION['auth_last_name']:''?>">
											<span class="last_name-error error"></span>
										</div>


										<div class="auth-form-group-custom mb-4">
											<i class="ri-mail-line auti-custom-input-icon"></i>
											<label for="useremail"><?=$this->lang->line('email')?><span style="color: red">*</span></label>
											<input type="email" class="form-control" id="email_error" placeholder="<?=$this->lang->line('email_placeholder')?>" name="email" value="<?=isset($_SESSION['auth_email'])?$_SESSION['auth_email']:''?>">
											<?php if (isset($errorObject->email)) :?>
												<span style="color:red;"><?=$errorObject->email?></span>
											<?php endif; ?>
										</div>


										<div class="text-center">
											<button class="btn btn-primary w-md waves-effect waves-light" type="submit" id="registrationFormBtn" style="width: 100%!important;margin-top: 11px;"><?=$this->lang->line('register_button')?></button>
										</div>
										<?php if ($hideBtn!=true): ?>
										<div class="MuiBox-root css-1ol8d1k" style="margin-top: 20px">
											<div class="MuiDivider-root MuiDivider-fullWidth MuiDivider-withChildren css-m1idq3" role="separator">
												<span class="MuiDivider-wrapper css-c1ovea">OR</span>
											</div>
										</div>


										<div class="MuiBox-root css-1ol8d1k"  style="margin-bottom:  20px;margin-top: 10px;">
											<div class="social-media-login MuiBox-root css-k9nmvd">
												<a href="<?=$googleBtn?>">
													<div class="pointer MuiBox-root css-bmusio">
														<img src="https://d1j61bbz9a40n6.cloudfront.net/website/home/v4/sign_in_method/Google.svg" style="width: 48px;">
													</div>
												</a>
<!--												<div class="pointer MuiBox-root css-bmusio">-->
<!--													<img src="https://d1j61bbz9a40n6.cloudfront.net/website/home/v4/sign_in_method/apple.svg" style="width: 48px;height: 48px;">-->
<!--												</div>-->
<!--												<a href="--><?//=$facebookBtn?><!--">-->
<!--													<div class="pointer MuiBox-root css-bmusio">-->
<!--														<img src="https://upload.wikimedia.org/wikipedia/commons/b/b8/2021_Facebook_icon.svg" style="width: 48px;">-->
<!--													</div>-->
<!--												</a>-->
											</div>
										</div>
										<?php endif; ?>
									</form>
								</div>

								<div class="text-center">
									<p><?=$this->lang->line('already_have_account')?> ? <a href="<?=base_url()?>login" class="fw-medium text-primary"><?=$this->lang->line('login_here')?></a> </p>
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
<?php
unset($_SESSION['error_new_registration']);
unset($_SESSION['auth_email']);
unset($_SESSION['auth_first_name']);
unset($_SESSION['auth_last_name']);
?>

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

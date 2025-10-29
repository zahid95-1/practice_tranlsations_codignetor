<?php
$loginBgImage ='assets/images/authentication-bg.jpg';
$getSettingsModel =$this->db->query("SELECT login_bg_image,logo_image,favicon_image,meta_descriptions,meta_title FROM setting")->row();

if ( $getSettingsModel ){
	if ($getSettingsModel->login_bg_image) {
		$loginBgImage = base_url() . "assets/settings/bg_image/" . $getSettingsModel->login_bg_image;
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

$errorObject=$requestData='';
if (isset($_SESSION['error_login'])){
	$errorObject	=json_decode($_SESSION['error_login']);
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
	<title><?=$metaTitle?> | login</title>
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

	<link href="<?=base_url()?>/assets/css/custom.css?t=45" rel="stylesheet" type="text/css" />

	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	
	 <!-- Google Translate Widget Script -->
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',  // Set the default page language to English
                includedLanguages: 'zh-CN,zh-TW',  // Only include Simplified and Traditional Chinese
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false  // Prevent auto-translation on page load
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    
</head>

<body class="auth-body-bg">
<style>
	.authentication-bg-1{
		background-image: url("<?=$loginBgImage?>");
		height: 100vh;
		background-size: cover;
		background-position: center; }
</style>
<div class="home-btn d-none d-sm-block">
	<a href="<?=base_url()?>"><i class="mdi mdi-home-variant h2 text-white"></i></a>
</div>
<div>
	<div class="container-fluid p-0">
		<div class="row g-0">
			<div class="col-lg-4">
				<div class="authentication-page-content p-4 d-flex align-items-center min-vh-100">
					<div class="w-100">
						<div class="row justify-content-center">
							<div class="col-lg-9">
								<div>
									<div class="text-center">
										<div style="" class="login-logo">
											<a href="<?=base_url()?>" class="logo"><img src="<?=$logoImage?>"  width="60%" alt="logo"></a>
										</div>

										<h4 class="font-size-18 mt-4"><?=$this->lang->line('login_title')?></h4>
									</div>

									<div class="p-2">
										<?php if ($this->session->flashdata("error")): ?>
											<div class="alert alert-danger" role="alert">
												<?=$this->session->flashdata("error")?>
											</div>
										<?php endif; ?>

										<?php if ($this->session->flashdata("msg")): ?>
											<div class="alert alert-success" role="alert">
												<?=$this->session->flashdata("msg")?>
											</div>
										<?php endif; ?>

										<form method="post" autocomplete="off" action="<?php echo base_url(); ?>login-validate">

											<?php if ($this->session->flashdata('smsg')): ?>
												<div class="alert alert-success" role="alert">
													<?php echo $this->session->flashdata('smsg'); ?>
												</div>
											<?php endif; ?>

											<div class="mb-3 auth-form-group-custom mb-4">
												<i class="ri-user-2-line auti-custom-input-icon"></i>
												<label for="username"><?=$this->lang->line('username')?></label>
												<input type="text" class="form-control" id="username" placeholder="<?=$this->lang->line('username_placeholder')?>" name="username">
												<?php if (isset($errorObject->username)){ ?>
													<span style="color: red"><?=$errorObject->username?></span>
												<?php } ?>
											</div>

											<div class="mb-3 auth-form-group-custom mb-4">
												<i class="ri-lock-2-line auti-custom-input-icon"></i>
												<label for="userpassword"><?=$this->lang->line('password')?></label>
												<input type="password" class="form-control" id="userpassword" placeholder="<?=$this->lang->line('password_placeholder')?>" name="password">
												<?php if (isset($errorObject->password)){ ?>
													<span style="color: red"><?=$errorObject->password?></span>
												<?php } ?>
											</div>

											<div class="mb-3 auth-form-group-custom mb-4">
												<div class="g-recaptcha" data-sitekey="<?=ConfigData['recaptcha_site_key']?>" ></div>
												<?php if (isset($errorObject->grecaptcharesponse)){ ?>
													<span style="color: red"><?=$errorObject->grecaptcharesponse?></span>
												<?php } ?>
											</div>

											<div class="mt-4 text-center">
												<button class="btn btn-primary w-md waves-effect waves-light" type="submit" style="width: 100%!important;margin-top: 11px;">Log In</button>
											</div>
											<?php if ($hideBtn!=true): ?>
											<div class="MuiBox-root css-1ol8d1k" style="margin-top: 15px">
												<div class="MuiDivider-root MuiDivider-fullWidth MuiDivider-withChildren css-m1idq3" role="separator">
													<span class="MuiDivider-wrapper css-c1ovea">OR</span>
												</div>
											</div>

											<div class="MuiBox-root css-1ol8d1k"  style="margin-bottom:  15px;margin-top: 10px;">
												<div class="social-media-login MuiBox-root css-k9nmvd">
													<a href="<?=$googleBtn?>">
														<div class="pointer MuiBox-root css-bmusio">
															<img src="https://d1j61bbz9a40n6.cloudfront.net/website/home/v4/sign_in_method/Google.svg" style="width: 48px;">
														</div>
													</a>
<!--													<div class="pointer MuiBox-root css-bmusio">-->
<!--														<img src="https://d1j61bbz9a40n6.cloudfront.net/website/home/v4/sign_in_method/apple.svg" style="width: 48px;height: 48px;">-->
<!--													</div>-->
<!--													<a href="--><?//=$facebookBtn?><!--">-->
<!--														<div class="pointer MuiBox-root css-bmusio">-->
<!--															<img src="https://upload.wikimedia.org/wikipedia/commons/b/b8/2021_Facebook_icon.svg" style="width: 48px;">-->
<!--														</div>-->
<!--													</a>-->
												</div>
											</div>

											<?php endif; ?>

											<div class="mt-4 text-center">
												<a href="<?=base_url()?>forgot-password" class="text-muted"><i class="mdi mdi-lock me-1"></i> <?=$this->lang->line('forgot_password')?> ?</a>
											</div>
										</form>
									</div>

									<div class=" text-center">
										<p><?=$this->lang->line('no_account')?> ? <a href="<?php echo base_url(); ?>register?reffid=<?=ConfigData['admin_prefix']."&link=1" ?>" class="fw-medium text-primary"> <?=$this->lang->line('register')?> </a> </p>
										<p>© <script>document.write(new Date().getFullYear())</script> . <?=$this->lang->line('footer')?></p>
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
			</div>
			<div class="col-lg-8 login-img">
				<div class="authentication-bg-1">
					<div class="bg-overlay"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
unset($_SESSION['error_login']);
?>

<!-- JAVASCRIPT -->
<script src="<?=base_url()?>/assets/libs/jquery/jquery.min.js"></script>
<script src="<?=base_url()?>/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?=base_url()?>/assets/libs/metismenu/metisMenu.min.js"></script>
<script src="<?=base_url()?>/assets/libs/simplebar/simplebar.min.js"></script>
<script src="<?=base_url()?>/assets/libs/node-waves/waves.min.js"></script>
</body>
</html>


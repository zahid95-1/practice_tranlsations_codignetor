<?php
$errorObject=$requestData='';
if (isset($_SESSION['error_password'])){
	$errorObject 	=json_decode($_SESSION['error_password']);
}

$loginBgImage 		='assets/images/authentication-bg.jpg';
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
?>
<!doctype html>
<html lang="en">
<head>

	<meta charset="utf-8" />
	<title><?=$metaTitle?> | Change password</title>
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

	<link href="<?=base_url()?>/assets/css/custom.css" rel="stylesheet" type="text/css" />

</head>

<body class="auth-body-bg">



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
										<div>
											<a href="<?=base_url()?>" class="logo"><img src="<?=$logoImage?>" height="100%" width="80%" alt="logo"></a>
										</div>
										<h4 class="font-size-18 mt-4">Change Password !</h4>
									</div>

									<div class="p-2 mt-5">
										<?php echo '<p class="text-danger">'.$this->session->flashdata("error").'</p>';  ?>

										<form method="post" autocomplete="off" action="<?php echo base_url(); ?>reset-password">
											<input type="hidden" name="token" value="<?php echo $_REQUEST['token']?>">
											<b style="color:green;"><?php echo $this->session->flashdata('smsg'); ?></b>
											<b style="color:red;"><?php echo $this->session->flashdata('fmsg'); ?></b>

											<div class="mb-3 auth-form-group-custom mb-4">
												<i class="ri-lock-2-line auti-custom-input-icon"></i>
												<label for="userpassword">Password</label>
												<input type="password" class="form-control" id="userpassword" placeholder="Enter password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"  title="Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters"   min-length="8" max-length="8" required>
												<?php if (isset($errorObject->password)){ ?>
													<span style="color: red"><?=$errorObject->password?></span>
												<?php }?>
											</div>

											<div class="mt-4 text-center">
												<button class="btn btn-primary w-md waves-effect waves-light" type="submit">Save Changes</button>
											</div>

										</form>
									</div>

									<div class="mt-5 text-center">
										<p>If you have an account ? <a href="<?php echo base_url(); ?>login" class="fw-medium text-primary"> Login </a> </p>
										<p>Don't have an account ? <a href="<?php echo base_url(); ?>register?reffid=CTS956631" class="fw-medium text-primary"> Register </a> </p>
										<p>© <script>document.write(new Date().getFullYear())</script> . Crafted with <i class="mdi mdi-heart text-danger"></i> by Mewoc Technologies</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-8">
				<div class="authentication-bg">
					<div class="bg-overlay"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php	unset($_SESSION['error_password']); ?>

<!-- JAVASCRIPT -->
<script src="<?=base_url()?>/assets/libs/jquery/jquery.min.js"></script>
<script src="<?=base_url()?>/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?=base_url()?>/assets/libs/metismenu/metisMenu.min.js"></script>
<script src="<?=base_url()?>/assets/libs/simplebar/simplebar.min.js"></script>
<script src="<?=base_url()?>/assets/libs/node-waves/waves.min.js"></script>

<script src="<?=base_url()?>/assets/js/app.js"></script>

</body>
</html>


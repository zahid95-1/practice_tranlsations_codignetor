<?php
$getReferralId =isset($_REQUEST["reffid"])?$_REQUEST["reffid"]:ConfigData['admin_prefix']; //Admin Key
/*===================GetUserInfo=========================*/
$getUserData = $this->db->query("SELECT* FROM users u WHERE unique_id = '$getReferralId'")->row();
$getCountry = $this->db->query("SELECT* FROM country ")->result();

$loadforgotpassword=false;
if (isset($_REQUEST['token']) && isset($_REQUEST['type']) && $_REQUEST['type']=='forgotpass'){
	$loadforgotpassword=true;
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

<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/timeedge.css">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&display=swap" rel="stylesheet">
	<link
			rel="stylesheet"
			href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css"
	/>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>

	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
<div class="main" style="width: 415px;min-width: 415px;">

	<div style="" class="registrations-logo">
		<a href="<?=base_url()?>" class="logo"><img src="<?=$logoImage?>" height="100%" width="210px" alt="logo"></a>
	</div>

	<div class="container a-container" id="a-container" style="left: 0px;width: unset">

		<div class="success-message" id="successAlert" style="display: none">
			<div class="alert alert-success" role="alert">
				Congratulation! You have successfully change your password.Login Now.
			</div>
			<a href="<?php echo base_url().'login';?>"><button class="switch__button button submit" type="button" id="" style="margin-left: 93px;">Sign In</button></a>
		</div>

		<form class="form"  action="<?php echo base_url()."reset-password-2"?>" method="post" id="changePasswordForm"  style="<?=($loadforgotpassword==true)?'display:none':''?>">
			<input type="hidden" name="token" value="<?php echo $_REQUEST['token']?>">
			<div style="display: flex">
				<h2 style="margin-right: 10px;">[</h2><span class="form__span">Change Password account</span><h2 style="margin-left: 10px;">]</h2>
			</div>
			<div style="color: red">
				<span class="grecaptcharesponse-error error"></span>
			</div>

			<div class="auth-form-group-custom mb-4">
				<input type="password" class="form__input" id="password_error" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"  title="Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters"   min-length="8" max-length="8"  placeholder="Enter Strong password*" name="password" required>
				<span class="password-error error"></span>
			</div>

			<button class="switch__button button submit" type="submit" id="changePasswordFormbtn">Change Password</button>
		</form >
	</div>
</div>

<?php
unset($_SESSION['error_login']);
?>

<!-- JAVASCRIPT -->
<script src="<?=base_url()?>/assets/libs/jquery/jquery.min.js"></script>

<script>
	$("form#changePasswordForm").submit(function(e) {
		e.preventDefault();

		var form = $(this);
		var actionUrl = form.attr('action');

		$.ajax({
			type: "POST",
			url: actionUrl,
			data: form.serialize(), // serializes the form's elements.
			success: function(result)
			{
				var obj = JSON.parse(result);

				if(obj.status===200){
					$('button#changePasswordForm').removeClass('disabled');
					$('button#changePasswordForm').html("CHANGE PASSWORD");
					$('div#successAlert').show();
					$("form#changePasswordForm").hide();
				}else{
					$.each(obj, function (index,value) {
						if (value && (index=='grecaptcharesponse' || index=='email')){
							$('span.grecaptcharesponse-error.error').html(value);
						}

						if (value) {
							//$('span.' + index + '-error.error').html(value);
							$('input#' + index + '_error').addClass('error');
						}else{
							//	$('span.' + index + '-error.error').html('');
							$('input#' + index + '_error').removeClass('error');
						}
					});

					$('button#changePasswordForm').html("CHANGE PASSWORD");
					$('button#changePasswordForm').removeClass('disabled');
				}
			},error: function (){
				alert("Something went wrong")
			},
			beforeSend: function (xhr){
				$('button#changePasswordForm').addClass('disabled');
				$('button#changePasswordForm').html("<span class='fa fa-spin fa-spinner'></span> CHANGE PASSWORD");
			}
		});

	});

</script>

</body>
</html>


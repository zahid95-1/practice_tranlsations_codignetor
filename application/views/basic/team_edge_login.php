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
<div class="main">
	<div class="container a-container" id="a-container">

		<div class="success-message" id="successAlert" style="display: none">
			<div class="alert alert-success" role="alert">
				Congratulation! You have successfully registered.
			</div>
		</div>
		<form class="form"  action="<?php echo base_url()."user-save-login-v2?reffid=$getReferralId&link=1"?>" method="post" id="registrationForm"  style="<?=($loadforgotpassword==true)?'display:none':''?>">
			<input type="hidden" value="1" name="link_id"/>
			<div style="display: flex">
				<h2 style="margin-right: 10px;">[</h2><span class="form__span">Register account</span><h2 style="margin-left: 10px;">]</h2>
			</div>
			<div style="color: red">
				<span class="grecaptcharesponse-error error"></span>
			</div>

			<div class="auth-form-group-custom mb-4">
				<input type="text" class="form__input" id="parent_id" placeholder="Refferral ID" value="<?php echo $getReferralId ?>" name="parent_id" readonly>
			</div>

			<div class="auth-form-group-custom mb-4">
				<input type="text" class="form__input" id="first_name_error" placeholder="Enter First*" name="first_name">
				<span class="first_name-error error"></span>
			</div>

			<div class="auth-form-group-custom mb-4">
				<input type="text" class="form__input" id="last_name_error" placeholder="Enter last Name" name="last_name">
				<span class="last_name-error error"></span>
			</div>


			<div class="auth-form-group-custom mb-4">
				<input type="email" class="form__input" id="email_error" placeholder="Enter email*" name="email">
				<span class="email-error error"></span>
			</div>

			<div class="auth-form-group-custom mb-4">
				<input type="password" class="form__input" id="password_error" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"  title="Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters"   min-length="8" max-length="8"  placeholder="Enter Strong password*" name="password" required>
				<span class="password-error error"></span>
			</div>

			<div class="auth-form-group-custom mb-4">
				<select class="form__input" name="country" id="country">
					<?php foreach($getCountry as $getCountryData){ ?>
						<option value="<?php echo $getCountryData->id  ?>" datacode="<?=$getCountryData->iso?>"><?php echo $getCountryData->name ?></option>
					<?php } ?>
				</select>
				<span class="password-error error"></span>
			</div>

			<div class="row mb-4">
				<input id="phone" type="number" name="phone" class="form__input" />
			</div>

			<div class="auth-form-group-custom mb-4">
				<input class="form__input" type="date" value="2011-08-19" id="birth_date_error" name="birth_date">
				<span class="birth_date-error error"></span>
			</div>

			<div class="mb-3 auth-form-group-custom mb-4">
				<div class="g-recaptcha" data-sitekey="<?=ConfigData['recaptcha_site_key']?>"></div>
				<?php if (isset($errorObject->grecaptcharesponse)){ ?>
					<span style="color: red"><?=$errorObject->grecaptcharesponse?></span>
				<?php } ?>
			</div>

			<div class="text-center" style="margin-bottom: 10px;">
				<span id="successMessage" style="color:green;"></span>
			</div>

			<div class="auth-form-group-custom mb-4">
				<input type="checkbox" id="tnc" name="tnc" required>
				<label for="tnc">I accept the <a href="<?=ConfigData['terms_conditions']?>" target="_blank">TERMS & CONDITIONS</a></label>
			</div>
			<button class="switch__button button submit" type="submit" id="registrationFormBtn">SIGN UP</button>
		</form >
	</div>


	<div class="container b-container" id="b-container">

		<form method="post" autocomplete="off" action="<?php echo base_url(); ?>login-validate-2" class="form" id="loginForm">
			<h2 class="form_title title" style="margin: 0;padding: 0">Sign in to Website</h2>
			<span id="forgotPasswordSuccessMessage"></span>
			<h6 class="singmessage-error error" style="color: red"></h6>

			<div class="mb-3 auth-form-group-custom mb-4">
				<input type="text" class="form__input" id="username_sing_in_field" placeholder="Enter your email or mobile no" name="username">
				<span class="username_sing_in_error error"></span>
			</div>

			<div class="mb-3 auth-form-group-custom mb-4">
				<input type="password" class="form__input" id="password_sing_in_field"  placeholder="Enter password" name="password">
				<span class="password_sing_in_error error"></span>
			</div>

			<div class="mb-3 auth-form-group-custom mb-4">
				<div class="g-recaptcha" data-sitekey="<?=ConfigData['recaptcha_site_key']?>" ></div>
				<?php if (isset($errorObject->grecaptcharesponse)){ ?>
					<span style="color: red"><?=$errorObject->grecaptcharesponse?></span>
				<?php } ?>
				<span class="grecaptcharesponse_sing_in_error error"></span>
			</div>

			<button class="switch__button button submit" type="submit" id="loginFormBtn">SIGN IN</button>

			<a class="form__link" style="cursor: pointer" id="forgotPassLink">Forgot your password?</a>

		</form>

		<form method="post" autocomplete="off" action="<?php echo base_url(); ?>forgot-password-2" class="form" id="forgotPasswordForm" style="display:none;">
			<h2 class="form_title title" style="margin: 0;padding: 0">Forgot Password !</h2>
			<div class="mb-3 auth-form-group-custom mb-4">
				<i class="ri-user-2-line auti-custom-input-icon"></i>
				<input type="text" class="form__input" id="username_f_pass_sing_in_error" placeholder="Enter your email." name="username">
				<span class="username_f_pass_sing_in_error error"></span>
			</div>


			<button class="switch__button button submit" type="submit" id="forgotPasswordBtn">Next</button>

			<a class="form__link" style="cursor: pointer" id="singInBtnLink">Sign In</a>

		</form>

	</div>
	<div class="switch" id="switch-cnt">
		<div class="switch__circle"></div>
		<div class="switch__circle switch__circle--t"></div>
		<div class="switch__container" id="switch-c1">
			<div style="" class="registrations-logo">
				<a href="<?=base_url()?>" class="logo"><img src="<?=$logoImage?>" height="100%" width="210px" alt="logo"></a>
			</div>
			<h2 class="switch__title title">Welcome Back !</h2>
			<p class="switch__description description">To keep connected with us please login with your personal info</p>
			<button class="switch__button button switch-btn">SIGN IN</button>
		</div>
		<div class="switch__container is-hidden" id="switch-c2">
			<div style="" class="registrations-logo">
				<a href="<?=base_url()?>" class="logo"><img src="<?=$logoImage?>" height="100%" width="210px" alt="logo"></a>
			</div>
			<h2 class="switch__title title">Hello Friend !</h2>
			<p class="switch__description description">Enter your personal details and start journey with us</p>
			<button class="switch__button button switch-btn">SIGN UP</button>
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

<script src="<?=base_url()?>/assets/js/app.js"></script>

<script>
	let switchCtn = document.querySelector("#switch-cnt");
	let switchC1 = document.querySelector("#switch-c1");
	let switchC2 = document.querySelector("#switch-c2");
	let switchCircle = document.querySelectorAll(".switch__circle");
	let switchBtn = document.querySelectorAll(".switch-btn");
	let aContainer = document.querySelector("#a-container");
	let bContainer = document.querySelector("#b-container");
	let allButtons = document.querySelectorAll(".submit");

	let getButtons = (e) => e.preventDefault();

	let changeForm = (e) => {
		switchCtn.classList.add("is-gx");
		setTimeout(function () {
			switchCtn.classList.remove("is-gx");
		}, 1500);

		switchCtn.classList.toggle("is-txr");
		switchCircle[0].classList.toggle("is-txr");
		switchCircle[1].classList.toggle("is-txr");

		switchC1.classList.toggle("is-hidden");
		switchC2.classList.toggle("is-hidden");
		aContainer.classList.toggle("is-txl");
		bContainer.classList.toggle("is-txl");
		bContainer.classList.toggle("is-z200");
	};

	let mainF = (e) => {

		for (var i = 0; i < allButtons.length; i++)
			//allButtons[i].addEventListener("click", getButtons);
		for (var i = 0; i < switchBtn.length; i++) {
			console.log(switchBtn[i]);
			switchBtn[i].addEventListener("click", changeForm);
		}
	};

	window.addEventListener("load", mainF);

</script>

<script>

	$(document).on('click', '#singInBtnLink', function () {
		$('form#forgotPasswordForm').hide();
		$('form#loginForm').show();
	});

	$(document).on('click', '#forgotPassLink', function () {
		$('form#loginForm').hide();
		$('form#forgotPasswordForm').show();
	});



	$(document).on('click', 'button.switch__button.button.switch-btn', function () {
		$('div#successAlert').hide();
		$("form#registrationForm").show();
	});

	var loginUrl		="<?=base_url()?>"+"/login";

	/**
	 * Form Submit Event And Maintain The Error Functionality
	 * Once Submit perfectly then redirect to user panel for login.
	 */
	$("form#registrationForm").submit(function(e) {
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
					$('button#registrationFormBtn').removeClass('disabled');
					$('button#registrationFormBtn').html("SIGN UP");
					$('div#successAlert').show();
					$("form#registrationForm").hide();
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

					$('button#registrationFormBtn').html("SIGN UP");
					$('button#registrationFormBtn').removeClass('disabled');
				}
			},error: function (){
				alert("Something went wrong")
			},
			beforeSend: function (xhr){
				$('button#registrationFormBtn').addClass('disabled');
				$('button#registrationFormBtn').html("<span class='fa fa-spin fa-spinner'></span> SIGN UP");
			}
		});

	});

	$("form#loginForm").submit(function(e) {
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
					if (obj.redirect) {
						window.location.href = obj.redirect;
					}
				}else{
					$.each(obj, function (index,value) {

						if (index=='message' && value){
							$('h6.singmessage-error.error').html(value);
						}
						if (value) {
							$('span.' + index + '_sing_in_error.error').html(value);
							$('input#' + index + '_sing_in_field').addClass('error');
						}else{
							$('span.' + index + '_sing_in_error.error').html('');
							$('input#' + index + '_sing_in_field').removeClass('error');
						}
					});

					$('button#loginFormBtn').html("SIGN IN");
					$('button#loginFormBtn').removeClass('disabled');
				}
			},error: function (){
				alert("Something went wrong")
			},
			beforeSend: function (xhr){
				$('button#loginFormBtn').addClass('disabled');
				$('button#loginFormBtn').html("<span class='fa fa-spin fa-spinner'></span> SIGN IN");
			}
		});

	});

	$("form#forgotPasswordForm").submit(function(e) {
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
					$('#forgotPasswordSuccessMessage').html(obj.message);
					$('form#forgotPasswordForm').hide();
					$('form#loginForm').show();

					$('button#forgotPasswordBtn').html("Next");
					$('button#forgotPasswordBtn').removeClass('disabled');

				}else{
					$.each(obj, function (index,value) {

						if (index=='message' && value){
							$('h6.singmessage-error.error').html(value);
						}
						if (value) {
							$('span.' + index + '_f_pass_sing_in_error.error').html(value);
							$('input#' + index + '_f_pass_sing_in_error').addClass('error');
						}else{
							$('span.' + index + '_sing_in_error.error').html('');
							$('input#' + index + '_sing_in_field').removeClass('error');
						}
					});

					$('button#forgotPasswordBtn').html("Next");
					$('button#forgotPasswordBtn').removeClass('disabled');
				}
			},error: function (){
				alert("Something went wrong")
			},
			beforeSend: function (xhr){
				$('button#forgotPasswordBtn').addClass('disabled');
				$('button#forgotPasswordBtn').html("<span class='fa fa-spin fa-spinner'></span>Next");
			}
		});

	});

</script>

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


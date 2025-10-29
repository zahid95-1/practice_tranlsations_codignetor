<?php
$getSettingsModel =$this->db->query("SELECT * FROM setting")->row();
$currency=$currencySymbol=$emailHost=$emailPort=$emailEnc=$emailUsername=$emailPassword='';
$smsUsername=$smsToken=$smsSenderId='';
$metatitle=$metaDescriptions='';
$logoImageUrl=$faviconImageUrl=$loginBgImageUrl=$signupBgImageurl='';
$copyrightStatus=$copyrightText='';
$dep_with_rate=0.00;
$rate_currency='';

$paypalClientId=$paypalClientSecret=$enableDisableStatus=$enableDisableKyc='';
$stripeClientId=$stripeSecret=$enableDisableStripe='';

if ($getSettingsModel){
	$from_currency			=$getSettingsModel->from_currency;
	$to_currency			=$getSettingsModel->to_currency;
	$from_currency_symbol	=$getSettingsModel->from_currency_symbol;
	$to_currency_symbol	    =$getSettingsModel->to_currency_symbol;
	$emailHost				=$getSettingsModel->email_host;
	$emailPort				=$getSettingsModel->email_port;
	$emailEnc				=$getSettingsModel->email_enc;
	$emailUsername			=$getSettingsModel->email_user_name;
	$emailPassword			=$getSettingsModel->email_password;
	$smsUsername			=$getSettingsModel->sms_user_name;
	$smsToken			    =$getSettingsModel->sms_token;
	$smsSenderId			=$getSettingsModel->sms_sender_id;
	$logoImageUrl			=$getSettingsModel->logo_image;
	$faviconImageUrl	    =$getSettingsModel->favicon_image;

	$paypalClientId	    =$getSettingsModel->paypal_client_id;
	$paypalClientSecret	    =$getSettingsModel->paypal_client_secret;
	$enableDisableStatus	    =$getSettingsModel->paypal_status;
	$enableDisableKyc	    =$getSettingsModel->kyc_validations;

	$stripeClientId	    =$getSettingsModel->stripe_client_id;
	$stripeSecret	    =$getSettingsModel->stripe_client_secret;
	$enableDisableStripe	    =$getSettingsModel->stripe_status;



	$loginBgImageUrl	    =$getSettingsModel->login_bg_image;
	$signupBgImageurl	    =$getSettingsModel->sign_up_bg_image;

	$metatitle	    =$getSettingsModel->meta_title;
	$metaDescriptions	    =$getSettingsModel->meta_descriptions;

	$copyrightStatus	    =$getSettingsModel->copy_right_display_status;
	$copyrightText	    	=$getSettingsModel->copy_right_text;

	$dep_with_rate	    	=$getSettingsModel->dep_with_rate;
	$rate_currency	    	=$getSettingsModel->rate_currency;

	$getminWithdrawal_amt   =$getSettingsModel->min_withdrawal;
}
?>
<style>
	span.select2.select2-container.select2-container--default {
		width: 100%!important;
	}
</style>
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content  mt-5" id="result">
	<div class="container-fluid mt-5">
		<!-- Start Page Content -->
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<!-- Nav tabs -->
						<ul class="nav nav-tabs" id="myTab" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" id="smtp-tab" data-toggle="tab" href="#smtp" role="tab" aria-controls="smtp" aria-selected="true">
									<?= $this->lang->line('smtp_tab') ?>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="sms-tab" data-toggle="tab" href="#sms" role="tab" aria-controls="sms" aria-selected="false">
									<?= $this->lang->line('sms_tab') ?>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="logo-tab" data-toggle="tab" href="#logo" role="tab" aria-controls="logo" aria-selected="false">
									<?= $this->lang->line('logo_favicon_tab') ?>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="currency-tab" data-toggle="tab" href="#currency" role="tab" aria-controls="currency" aria-selected="false">
									<?= $this->lang->line('currency_tab') ?>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="copyright-tab" data-toggle="tab" href="#copyright" role="tab" aria-controls="copyright" aria-selected="false">
									<?= $this->lang->line('copyright_tab') ?>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="meta-tab" data-toggle="tab" href="#meta" role="tab" aria-controls="meta" aria-selected="false">
									<?= $this->lang->line('meta_tab') ?>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="loginpage-tab" data-toggle="tab" href="#loginpage" role="tab" aria-controls="loginpage" aria-selected="false">
									<?= $this->lang->line('login_signup_bg_tab') ?>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="withdraw-tab" data-toggle="tab" href="#withdrawpage" role="tab" aria-controls="withdrawpage" aria-selected="false">
									<?= $this->lang->line('min_withdraw_kyc_tab') ?>
								</a>
							</li>
							<?php if (ConfigData['enable_deposit_withdraw_rate']): ?>
								<li class="nav-item">
									<a class="nav-link" id="dpwd-tab" data-toggle="tab" href="#dpwd" role="tab" aria-controls="dpwd" aria-selected="false">
										<?= $this->lang->line('deposit_withdraw_rate_tab') ?>
									</a>
								</li>
							<?php endif; ?>
							<li class="nav-item">
								<a class="nav-link" id="paypal-tab" data-toggle="tab" href="#paypalSettings" role="tab" aria-controls="paypalSettings" aria-selected="false">
									<?= $this->lang->line('payment_settings_tab') ?>
								</a>
							</li>
						</ul>


						<!-- Tab panes -->
						<div class="tab-content">

							<div class="pt-5 tab-pane active" id="smtp" role="tabpanel" aria-labelledby="smtp-tab">
								<div class="container">
									<form method="post" autocomplete="off" action="<?= base_url(); ?>save-email-configurations" id="emailSettingFrom">
										<div class="row col-md-12 settings-box">

											<!-- Email Method -->
											<div class="form-group col-md-6">
												<label><?=  $this->lang->line('email_sending_method') ?></label>
												<select name="email_method" class="form-control">
													<option value="smtp"><?=  $this->lang->line('email_sending_method') ?></option>
												</select>
											</div>

											<!-- Host -->
											<div class="form-group col-md-6">
												<label><?=  $this->lang->line('smtp_host') ?></label>
												<input type="text" name="email_host" id="email_host" class="form-control"
													   placeholder="<?=  $this->lang->line('smtp_host_placeholder') ?>" value="<?= $emailHost ?>" required>
											</div>

											<!-- Port -->
											<div class="form-group col-md-6">
												<label><?=  $this->lang->line('smtp_port') ?></label>
												<input type="text" name="email_port" id="email_port" class="form-control"
													   placeholder="<?=  $this->lang->line('smtp_port_placeholder') ?>" value="<?= $emailPort ?>" required>
											</div>

											<!-- Encryption -->
											<div class="form-group col-md-6">
												<label><?=  $this->lang->line('smtp_encryption') ?></label>
												<select class="form-control" name="enc">
													<option value="ssl" <?= ($emailEnc == 'ssl') ? 'selected' : '' ?>><?=  $this->lang->line('smtp_encryption_ssl') ?></option>
													<option value="tls" <?= ($emailEnc == 'tls') ? 'selected' : '' ?>><?=  $this->lang->line('smtp_encryption_tls') ?></option>
												</select>
											</div>

											<!-- Username -->
											<div class="form-group col-md-6">
												<label><?=  $this->lang->line('smtp_username') ?></label>
												<input type="text" name="email_user_name" id="mail_username" class="form-control"
													   placeholder="<?=  $this->lang->line('smtp_username_placeholder') ?>" value="<?= $emailUsername ?>" required>
											</div>

											<!-- Password -->
											<div class="form-group col-md-6">
												<label><?=  $this->lang->line('smtp_password') ?></label>
												<input type="text" name="email_password" id="mail_password" class="form-control"
													   placeholder="<?=  $this->lang->line('smtp_password_placeholder') ?>" value="<?= $emailPassword ?>" required>
											</div>

											<!-- Message Box -->
											<div class="form-group col-md-12">
												<p style="color:green;" id="emailSuccessMessageBox"></p>
											</div>

											<!-- Submit Button -->
											<div class="form-group col-md-6">
												<button type="submit" name="login" id="emailSettingFrom" class="btn btn-warning btn-flat">
													<?= ($emailUsername) ?  $this->lang->line('smtp_update') :  $this->lang->line('smtp_submit') ?>
												</button>
											</div>

										</div>
									</form>
								</div>
							</div>
							<div class="pt-5 tab-pane" id="sms" role="tabpanel" aria-labelledby="sms-tab">
								<div class="container">
									<form method="post" autocomplete="off" action="<?= base_url(); ?>save-sms" id="smsSetupForm">
										<div class="row settings-box">

											<!-- SMS Method -->
											<div class="form-group col-md-6">
												<label><?=  $this->lang->line('sms_sending_method') ?></label>
												<select name="sms_method" class="form-control">
													<option value="exposys"><?=  $this->lang->line('sms_exposys') ?></option>
												</select>
											</div>

											<!-- Username -->
											<div class="form-group col-md-6">
												<label><?=  $this->lang->line('sms_username') ?></label>
												<input type="text" name="sms_user_name" id="sms_user_name" class="form-control"
													   placeholder="<?=  $this->lang->line('sms_username_placeholder') ?>" value="<?= $smsUsername ?>" required>
											</div>

											<!-- Token -->
											<div class="form-group col-md-6">
												<label><?=  $this->lang->line('sms_token') ?></label>
												<input type="text" name="sms_token" id="sms_token" class="form-control"
													   placeholder="<?=  $this->lang->line('sms_token_placeholder') ?>" value="<?= $smsToken ?>" required>
											</div>

											<!-- Sender Key -->
											<div class="form-group col-md-6">
												<label><?=  $this->lang->line('sms_sender_id') ?></label>
												<input type="text" name="sms_sender_id" id="sms_sender_id" class="form-control"
													   placeholder="<?=  $this->lang->line('sms_sender_id_placeholder') ?>" value="<?= $smsSenderId ?>" required>
											</div>

											<!-- Success Message -->
											<div class="form-group col-md-12" style="margin: 0">
												<p style="color:green;" id="successMessageBoxSms"></p>
											</div>

											<!-- Submit Button -->
											<div class="form-group col-md-6">
												<button type="submit" name="login" id="smsSetupForm_Btn" class="btn btn-warning btn-flat">
													<?= ($smsUsername) ?  $this->lang->line('sms_update') :  $this->lang->line('sms_submit') ?>
												</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="tab-pane pt-5" id="logo" role="tabpanel" aria-labelledby="logo-tab">
								<div class="container">
									<form method="post" autocomplete="off" action="<?= base_url(); ?>save-logo" id="logoandFaviconForm" enctype='multipart/form-data'>
										<div class="row settings-box">

											<!-- Site Logo -->
											<div class="form-group col-md-6">
												<label><?=  $this->lang->line('logo_site_logo') ?></label>
												<?php if (empty($logoImageUrl)) { ?>
													<div id="dropZoon_0" class="upload-area__drop-zoon drop-zoon">
														<span class="drop-zoon__icon"><i class='bx bxs-file-image'></i></span>
														<p class="drop-zoon__paragraph"><?=  $this->lang->line('logo_drop_here') ?></p>
														<span id="loadingText_0" class="drop-zoon__loading-text"><?=  $this->lang->line('logo_wait') ?></span>
														<img src="" alt="<?=  $this->lang->line('logo_preview') ?>" id="previewImage_0" class="drop-zoon__preview-image" draggable="false">
														<input type="file" id="fileInput_0" class="drop-zoon__file-input" accept="image/*" name="logo_url">
													</div>
												<?php } else {
													$logoImageUrlLink = base_url() . "assets/settings/logo/" . $logoImageUrl;
													?>
													<div id="dropZoon_0" class="upload-area__drop-zoon drop-zoon">
														<span class="drop-zoon__icon" style="display:none;"><i class='bx bxs-file-image'></i></span>
														<p class="drop-zoon__paragraph" style="display:none;"><?=  $this->lang->line('logo_drop_here') ?></p>
														<span id="loadingText_0" class="drop-zoon__loading-text"><?=  $this->lang->line('logo_wait') ?></span>
														<img src="<?= $logoImageUrlLink ?>" alt="<?=  $this->lang->line('logo_preview') ?>" id="previewImage_0" class="drop-zoon__preview-image" draggable="false" style="display: block">
														<input type="file" id="fileInput_0" class="drop-zoon__file-input" accept="image/*" name="logo_url">
														<input type="hidden" name="previouslogoImageurl" value="<?= $logoImageUrl ?>">
													</div>
												<?php } ?>
											</div>

											<!-- Favicon -->
											<div class="form-group col-md-6">
												<label><?=  $this->lang->line('logo_favicon') ?></label>
												<?php if (empty($faviconImageUrl)) { ?>
													<div id="dropZoon_1" class="upload-area__drop-zoon drop-zoon">
														<span class="drop-zoon__icon"><i class='bx bxs-file-image'></i></span>
														<p class="drop-zoon__paragraph"><?=  $this->lang->line('logo_favicon_drop') ?></p>
														<span id="loadingText_1" class="drop-zoon__loading-text"><?=  $this->lang->line('logo_wait') ?></span>
														<img src="" alt="<?=  $this->lang->line('logo_preview') ?>" id="previewImage_1" class="drop-zoon__preview-image" draggable="false">
														<input type="file" id="fileInput_1" class="drop-zoon__file-input" accept="image/*" name="favicon_image">
													</div>
												<?php } else {
													$faviconImageUrllink = base_url() . "assets/settings/logo/" . $faviconImageUrl;
													?>
													<div id="dropZoon_1" class="upload-area__drop-zoon drop-zoon">
														<span class="drop-zoon__icon" style="display:none;"><i class='bx bxs-file-image'></i></span>
														<p class="drop-zoon__paragraph" style="display:none;"><?=  $this->lang->line('logo_favicon_drop') ?></p>
														<span id="loadingText_1" class="drop-zoon__loading-text"><?=  $this->lang->line('logo_wait') ?></span>
														<img src="<?= $faviconImageUrllink ?>" alt="<?=  $this->lang->line('logo_preview') ?>" id="previewImage_1" class="drop-zoon__preview-image" draggable="false" style="display: block">
														<input type="file" id="fileInput_1" class="drop-zoon__file-input" accept="image/*" name="favicon_image">
														<input type="hidden" name="previousFaviconImageurl" value="<?= $faviconImageUrl ?>">
													</div>
												<?php } ?>
											</div>

											<!-- Success Message -->
											<div class="form-group col-md-12" style="margin: 0">
												<p style="color:green;" id="successMessageBoxLogoAndFavicon"></p>
											</div>

											<!-- Submit -->
											<div class="form-group col-md-6">
												<button type="submit" name="login" id="logoandFaviconForm_btn" class="btn btn-warning btn-flat">
													<?= $logoImageUrl ?  $this->lang->line('logo_update') :  $this->lang->line('logo_submit') ?>
												</button>
											</div>

										</div>
									</form>
								</div>
							</div>
							<div class="pt-5  tab-pane" id="currency" role="tabpanel" aria-labelledby="currency-tab">
								<div class="container">
									<form method="post" autocomplete="off" action="<?php echo base_url(); ?>save-currency" id="currenceySetupForm">
										<div class="row settings-box">

											<div class="form-group col-md-6 ">
												<label>From Currency</label>
												<input type="text" name="from_currency" id="from_currency" class="form-control" placeholder="INR" value="<?=$from_currency?>" required>
											</div>

											<div class="form-group col-md-6 ">
												<label>From Currency Symbol</label>
												<input type="text" name="from_currency_symbol" id="from_currency_symbol" class="form-control" placeholder="₹" value="<?=$from_currency_symbol?>" required>
											</div>

											<div class="form-group col-md-6 ">
												<label>To Currency</label>
												<input type="text" name="to_currency" id="to_currency" class="form-control" placeholder="INR" value="<?=$to_currency?>" required>
											</div>

											<div class="form-group col-md-6 ">
												<label>To Currency Symbol</label>
												<input type="text" name="to_currency_symbol" id="to_currency_symbol" class="form-control" placeholder="₹" value="<?=$to_currency_symbol?>" required>
											</div>

											<div class="form-group col-md-12" style="margin: 0">
												<p style="color:green;" id="successMessageBox"></p>
											</div>

											<div class="form-group col-md-6 ">
												<button type="submit" name="login" id="currenceySetupForm_btn" class="btn btn-warning btn-flat"><?php if ($currency){echo "Update";}else{echo  "Submit";} ?></button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="pt-5 tab-pane" id="copyright" role="tabpanel" aria-labelledby="copyright-tab">
								<div class="container">
									<form method="post" autocomplete="off" action="<?= base_url(); ?>save-copy-right" id="copyRightForm">
										<div class="row settings-box">

											<div class="form-group col-md-12">
												<label><?=  $this->lang->line('copy_display_question') ?></label>
												<select name="copy_right_display_status" class="form-control">
													<option value=""><?=  $this->lang->line('select_option') ?></option>
													<option value="1" <?= ($copyrightStatus == 1) ? "selected" : "" ?>><?=  $this->lang->line('yes') ?></option>
													<option value="2" <?= ($copyrightStatus == 2) ? "selected" : "" ?>><?=  $this->lang->line('no') ?></option>
												</select>
											</div>

											<div class="form-group col-md-12">
												<label><?=  $this->lang->line('copy_content') ?></label>
												<input type="text" name="copy_right_text" id="copy_right_text" class="form-control" placeholder="<?=  $this->lang->line('copy_content_placeholder') ?>" value="<?= $copyrightText ?>" required>
											</div>

											<div class="form-group col-md-12" style="margin: 0">
												<p style="color:green;" id="successMessageCopyRightBox"></p>
											</div>

											<div class="form-group col-md-6">
												<button type="submit" name="login" id="copyRightForm_btn" class="btn btn-warning btn-flat">
													<?= $copyrightText ?  $this->lang->line('update') :  $this->lang->line('submit') ?>
												</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="pt-5 tab-pane" id="meta" role="tabpanel" aria-labelledby="meta-tab">
								<div class="container">
									<form method="post" autocomplete="off" action="<?= base_url(); ?>save-meta-data" id="metaDescriptionForm">
										<div class="row settings-box">

											<div class="form-group col-md-12">
												<label><?=  $this->lang->line('meta_title_label') ?></label>
												<input type="text" name="meta_title" id="meta_title" class="form-control"
													   placeholder="<?=  $this->lang->line('meta_title_placeholder') ?>" value="<?= $metatitle ?>" required>
											</div>

											<div class="form-group col-md-12">
												<label><?=  $this->lang->line('meta_description_label') ?></label>
												<textarea class="form-control" name="meta_descriptions" style="height: 100px;"
														  placeholder="<?=  $this->lang->line('meta_description_placeholder') ?>"><?= $metaDescriptions ?></textarea>
											</div>

											<div class="form-group col-md-12" style="margin: 0">
												<p style="color:green;" id="successMessageMetabox"></p>
											</div>

											<div class="form-group col-md-6">
												<button type="submit" name="login" id="metaDescriptionForm_btn" class="btn btn-warning btn-flat">
													<?= $metatitle ?  $this->lang->line('update') :  $this->lang->line('submit') ?>
												</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="pt-5 tab-pane" id="loginpage" role="tabpanel" aria-labelledby="loginpage-tab">
								<div class="container">
									<form method="post" autocomplete="off" action="<?= base_url(); ?>save-bg-image" id="loginAndSignUpBgImageForm" enctype='multipart/form-data'>
										<div class="row settings-box">

											<!-- Login Background -->
											<div class="form-group col-md-6">
												<label><?=  $this->lang->line('login_bg_label') ?></label>
												<?php if (empty($loginBgImageUrl)) { ?>
													<div id="dropZoon_2" class="upload-area__drop-zoon drop-zoon">
														<span class="drop-zoon__icon"><i class='bx bxs-file-image'></i></span>
														<p class="drop-zoon__paragraph"><?=  $this->lang->line('login_bg_placeholder') ?></p>
														<span id="loadingText_2" class="drop-zoon__loading-text"><?=  $this->lang->line('loading_text') ?></span>
														<img src="" alt="Preview Image" id="previewImage_2" class="drop-zoon__preview-image" draggable="false">
														<input type="file" id="fileInput_2" class="drop-zoon__file-input" accept="image/*" name="login_bg_image">
													</div>
												<?php } else {
													$loginBgImageUrllink = base_url() . "assets/settings/bg_image/" . $loginBgImageUrl;
													?>
													<div id="dropZoon_2" class="upload-area__drop-zoon drop-zoon">
														<span class="drop-zoon__icon" style="display:none;"><i class='bx bxs-file-image'></i></span>
														<p class="drop-zoon__paragraph" style="display:none;"><?=  $this->lang->line('login_bg_placeholder') ?></p>
														<span id="loadingText_2" class="drop-zoon__loading-text"><?=  $this->lang->line('loading_text') ?></span>
														<img src="<?= $loginBgImageUrllink ?>" alt="Preview Image" id="previewImage_2" class="drop-zoon__preview-image" draggable="false" style="display: block">
														<input type="file" id="fileInput_2" class="drop-zoon__file-input" accept="image/*" name="login_bg_image">
														<input type="hidden" name="previousLoginBgImage" value="<?= $loginBgImageUrl ?>">
													</div>
												<?php } ?>
											</div>

											<!-- Signup Background -->
											<div class="form-group col-md-6">
												<label><?=  $this->lang->line('signup_bg_label') ?></label>
												<?php if (empty($signupBgImageurl)) { ?>
													<div id="dropZoon_3" class="upload-area__drop-zoon drop-zoon">
														<span class="drop-zoon__icon"><i class='bx bxs-file-image'></i></span>
														<p class="drop-zoon__paragraph"><?=  $this->lang->line('signup_bg_placeholder') ?></p>
														<span id="loadingText_3" class="drop-zoon__loading-text"><?=  $this->lang->line('loading_text') ?></span>
														<img src="" alt="Preview Image" id="previewImage_3" class="drop-zoon__preview-image" draggable="false">
														<input type="file" id="fileInput_3" class="drop-zoon__file-input" accept="image/*" name="sign_up_bg_image">
													</div>
												<?php } else {
													$signupBgImageurlLink = base_url() . "assets/settings/bg_image/" . $signupBgImageurl;
													?>
													<div id="dropZoon_3" class="upload-area__drop-zoon drop-zoon">
														<span class="drop-zoon__icon" style="display:none;"><i class='bx bxs-file-image'></i></span>
														<p class="drop-zoon__paragraph" style="display:none;"><?=  $this->lang->line('signup_bg_placeholder') ?></p>
														<span id="loadingText_3" class="drop-zoon__loading-text"><?=  $this->lang->line('loading_text') ?></span>
														<img src="<?= $signupBgImageurlLink ?>" alt="Preview Image" id="previewImage_3" class="drop-zoon__preview-image" draggable="false" style="display: block">
														<input type="file" id="fileInput_3" class="drop-zoon__file-input" accept="image/*" name="sign_up_bg_image">
														<input type="hidden" name="previousSignupImageUrl" value="<?= $signupBgImageurl ?>">
													</div>
												<?php } ?>
											</div>

											<div class="form-group col-md-12" style="margin: 0">
												<p style="color:green;" id="successMessageBoxLoginAndSignupBgImage"></p>
											</div>

											<div class="form-group col-md-6">
												<button type="submit" name="login" id="loginAndSignUpBgImageForm_btn" class="btn btn-warning btn-flat">
													<?= $signupBgImageurl ?  $this->lang->line('update') :  $this->lang->line('submit') ?>
												</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="pt-5 tab-pane" id="dpwd" role="tabpanel" aria-labelledby="currency-tab">
								<div class="container">
									<form method="post" autocomplete="off" action="<?= base_url(); ?>save-withdraw-deposit-rate" id="depositWithdrawRate">
										<div class="row settings-box">

											<!-- From Currency (readonly) -->
											<div class="form-group col-md-4" style="margin-top: 10px;">
												<label><?=  $this->lang->line('from_currency_label') ?></label>
												<input type="text" class="form-control" placeholder="<?=  $this->lang->line('from_currency_placeholder') ?>" value="1 USD" readonly>
											</div>

											<!-- Converted Amount -->
											<div class="form-group col-md-4" style="margin-top: 10px;">
												<label><?=  $this->lang->line('converted_amount_label') ?><span class="error">*</span></label>
												<input type="text" name="dep_with_rate" id="dep_with_rate" class="form-control" placeholder="<?=  $this->lang->line('converted_amount_placeholder') ?>" value="<?= $dep_with_rate ?>" required>
											</div>

											<!-- Currency Dropdown -->
											<div class="col-md-4">
												<label class="col-md-4 col-form-label"><?=  $this->lang->line('currency_label') ?><span class="error">*</span></label>
												<div>
													<select class="form-control select2" name="rate_currency" id="rate_currency" required>
														<option value=""><?=  $this->lang->line('select_currency') ?></option>
														<?php if (isset($dataItem)): foreach ($dataItem as $key => $item): ?>
															<option value="<?= $key ?>" <?= $rate_currency == $key ? 'selected' : '' ?>><?= $key ?></option>
														<?php endforeach; endif; ?>
													</select>
												</div>
												<span class="error"><?= isset($errorObject->mt5_login_id) ? $errorObject->mt5_login_id : '' ?></span>
											</div>

											<!-- Success Message -->
											<div class="form-group col-md-12" style="margin-top: 15px;">
												<p style="color:green;" id="successMessageBox"></p>
											</div>

											<!-- Submit Button -->
											<div class="form-group col-md-6">
												<button type="submit" id="depositWithdrawRateBtn" class="btn btn-warning btn-flat">
													<?= $dep_with_rate ?  $this->lang->line('update') :  $this->lang->line('submit') ?>
												</button>
											</div>

										</div>
									</form>
								</div>
							</div>
							<div class="pt-5 tab-pane" id="paypalSettings" role="tabpanel" aria-labelledby="paypal-tab">
								<div class="container">
									<form method="post" autocomplete="off" action="<?= base_url(); ?>save-paypal-settings" id="paymentSettingsForm">
										<div class="row settings-box">

											<!-- PayPal Settings Title -->
											<div>
												<h4 style="text-align: center; margin-bottom: 10px;"><?=  $this->lang->line('paypal_settings_title') ?></h4>
											</div>

											<!-- PayPal Client ID -->
											<div class="form-group col-md-6" style="margin-bottom: 10px;">
												<label><?=  $this->lang->line('paypal_client_id') ?> <span class="error">*</span></label>
												<input type="text" name="paypal_client_id" id="paypal_client_id" class="form-control" placeholder="<?=  $this->lang->line('paypal_client_id_placeholder') ?>" value="<?= $paypalClientId ?>">
											</div>

											<!-- PayPal Client Secret -->
											<div class="form-group col-md-6" style="margin-bottom: 10px;">
												<label><?=  $this->lang->line('paypal_client_secret') ?> <span class="error">*</span></label>
												<input type="text" name="paypal_client_secret" id="paypal_client_secret" class="form-control" placeholder="<?=  $this->lang->line('paypal_client_secret_placeholder') ?>" value="<?= $paypalClientSecret ?>">
											</div>

											<!-- PayPal Enable/Disable -->
											<div class="col-sm-12" style="margin-bottom: 10px;">
												<label class="col-sm-12 col-form-label"><?=  $this->lang->line('paypal_status_label') ?> <span class="error">*</span></label>
												<div class="d-flex">
													<div class="form-check mb-3">
														<input class="form-check-input" type="radio" name="paypal_status" id="paypal_status_enable" value="1" <?= isset($enableDisableStatus) && $enableDisableStatus == 1 ? 'checked' : '' ?>>
														<label class="form-check-label" for="paypal_status_enable"><?=  $this->lang->line('enable') ?></label>
													</div>
													<div class="form-check mb-3" style="margin-left: 10px;">
														<input class="form-check-input" type="radio" name="paypal_status" id="paypal_status_disable" value="2" <?= isset($enableDisableStatus) && $enableDisableStatus == 2 ? 'checked' : '' ?>>
														<label class="form-check-label" for="paypal_status_disable"><?=  $this->lang->line('disable') ?></label>
													</div>
												</div>
											</div>

											<hr>

											<!-- Stripe Settings Title -->
											<div>
												<h4 style="text-align: center; margin-bottom: 10px;"><?=  $this->lang->line('stripe_settings_title') ?></h4>
											</div>

											<!-- Stripe Public Key -->
											<div class="form-group col-md-6" style="margin-bottom: 10px;">
												<label><?=  $this->lang->line('stripe_public_key') ?> <span class="error">*</span></label>
												<input type="text" name="stripe_client_id" id="stripe_client_id" class="form-control" placeholder="<?=  $this->lang->line('stripe_public_key_placeholder') ?>" value="<?= $stripeClientId ?>">
											</div>

											<!-- Stripe Secret Key -->
											<div class="form-group col-md-6" style="margin-bottom: 10px;">
												<label><?=  $this->lang->line('stripe_secret_key') ?> <span class="error">*</span></label>
												<input type="text" name="stripe_client_secret" id="stripe_client_secret" class="form-control" placeholder="<?=  $this->lang->line('stripe_secret_key_placeholder') ?>" value="<?= $stripeClientId ?>">
											</div>

											<!-- Stripe Enable/Disable -->
											<div class="col-sm-12" style="margin-bottom: 10px;">
												<label class="col-sm-12 col-form-label"><?=  $this->lang->line('stripe_status_label') ?> <span class="error">*</span></label>
												<div class="d-flex">
													<div class="form-check mb-3">
														<input class="form-check-input" type="radio" name="stripe_status" id="stripe_status_enable" value="1" <?= isset($enableDisableStripe) && $enableDisableStripe == 1 ? 'checked' : '' ?>>
														<label class="form-check-label" for="stripe_status_enable"><?=  $this->lang->line('enable') ?></label>
													</div>
													<div class="form-check mb-3" style="margin-left: 10px;">
														<input class="form-check-input" type="radio" name="stripe_status" id="stripe_status_disable" value="2" <?= isset($enableDisableStripe) && $enableDisableStripe == 2 ? 'checked' : '' ?>>
														<label class="form-check-label" for="stripe_status_disable"><?=  $this->lang->line('disable') ?></label>
													</div>
												</div>
											</div>

											<!-- Success Message -->
											<div class="form-group col-md-12">
												<p style="color:green;" id="successMessageBox"></p>
											</div>

											<!-- Submit Button -->
											<div class="form-group col-md-6">
												<button type="submit" class="btn btn-warning btn-flat">
													<?= ($stripeClientId || $paypalClientId) ?  $this->lang->line('update') :  $this->lang->line('submit') ?>
												</button>
											</div>

										</div>
									</form>
								</div>
							</div>
							<div class="pt-5 tab-pane" id="withdrawpage" role="tabpanel" aria-labelledby="withdraw-tab">
								<div class="container">
									<form method="post" autocomplete="off" action="<?= base_url(); ?>save-min-withdraw" id="withdrawalAmt">
										<div class="row settings-box">

											<!-- Min Withdrawal Amount -->
											<div class="form-group col-md-4" style="margin-top: 10px;">
												<label><?=  $this->lang->line('min_withdrawal_amount') ?> <span class="error">*</span></label>
												<input type="number" name="min_withdraw_amt" id="min_withdraw_amt" class="form-control" min="0" placeholder="<?=  $this->lang->line('min_withdrawal_amount_placeholder') ?>" value="<?= $getminWithdrawal_amt ?>" required>
											</div>

											<!-- KYC Validation Enable/Disable -->
											<div class="col-sm-12" style="margin-top: 30px;">
												<label class="col-sm-12 col-form-label"><?=  $this->lang->line('enable_disable_kyc') ?> <span class="error">*</span></label>
												<div class="d-flex">
													<div class="form-check mb-3">
														<input class="form-check-input" type="radio" name="kyc_validations" id="kyc_validations_status_active" value="1" <?= isset($enableDisableKyc) && $enableDisableKyc == 1 ? 'checked' : '' ?>>
														<label class="form-check-label" for="kyc_validations_status_active"><?=  $this->lang->line('enable') ?></label>
													</div>
													<div class="form-check mb-3" style="margin-left: 10px;">
														<input class="form-check-input" type="radio" name="kyc_validations" id="kyc_validations_status_inactive" value="0" <?= isset($enableDisableKyc) && $enableDisableKyc == 0 ? 'checked' : '' ?>>
														<label class="form-check-label" for="kyc_validations_status_inactive"><?=  $this->lang->line('disable') ?></label>
													</div>
												</div>
											</div>

											<!-- Success Message -->
											<div class="form-group col-md-12" style="margin-top: 15px;">
												<p style="color:green;" id="successMessageBoxWithdraw"></p>
											</div>

											<!-- Submit Button -->
											<div class="form-group col-md-6">
												<button type="submit" name="login" id="WithdrawBtn" class="btn btn-warning btn-flat">
													<?= $getminWithdrawal_amt ?  $this->lang->line('update') :  $this->lang->line('submit') ?>
												</button>
											</div>

										</div>
									</form>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End PAge Content -->
	</div>
</div>
<!-- end main content-->

<!-- JAVASCRIPT -->
<script src="<?=base_url()?>assets/libs/jquery/jquery.min.js"></script>
<script src="<?=base_url()?>assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?=base_url()?>assets/js/drag-drop-logo.js"></script>

<script>

	$('#myTab a').on('click', function (e) {
		e.preventDefault()
		$(this).tab('show')
	})

	/*------Currency Setup Form Submit- > Ajax Calling Function-------------*/
	$("form#depositWithdrawRate").submit(function(e) {
		e.preventDefault();

		var form = $(this);
		var actionUrl = form.attr('action');

		$.ajax({
			type: "POST",
			url: actionUrl,
			data: form.serialize(), // serializes the form's elements.
			success: function(response)
			{
				if (response){
					$('button#depositWithdrawRateBtn').html("Update");
					$('p#successMessageBox').html('Successfully Save Currency');
					setTimeout(function() {
						$('p#successMessageBox').html('');
					}, 5000);
				}
			},error: function (){
				alert("Something went wrong");
				$('button#depositWithdrawRateBtn').html("Submit");
			},
			beforeSend: function (xhr){
				$('button#depositWithdrawRateBtn').html("<span class='fa fa-spin fa-spinner'></span> Processing...");
			}
		});
	});

	/*------SMTP Setup Form Submit- > Ajax Calling Function-------------*/
	$("form#emailSettingFrom").submit(function(e) {
		e.preventDefault();

		var form = $(this);
		var actionUrl = form.attr('action');

		$.ajax({
			type: "POST",
			url: actionUrl,
			data: form.serialize(), // serializes the form's elements.
			success: function(response)
			{
				if (response){
					$('button#currenceySetupForm_btn').html("Update");
					$('p#emailSuccessMessageBox').html('Successfully Save SMTP Configurations');
					setTimeout(function() {
						$('p#emailSuccessMessageBox').html('');
					}, 5000);
				}
			},error: function (){
				alert("Something went wrong");
				$('button#currenceySetupForm_btn').html("Submit");
			},
			beforeSend: function (xhr){
				$('button#currenceySetupForm_btn').html("<span class='fa fa-spin fa-spinner'></span> Processing...");
			}
		});
	});

	/*------SMS Setup Form Submit- > Ajax Calling Function-------------*/
	$("form#smsSetupForm").submit(function(e) {
		e.preventDefault();

		var form = $(this);
		var actionUrl = form.attr('action');

		$.ajax({
			type: "POST",
			url: actionUrl,
			data: form.serialize(), // serializes the form's elements.
			success: function(response)
			{
				if (response){
					$('button#smsSetupForm_Btn').html("Update");
					$('p#successMessageBoxSms').html('Successfully Save SMTP Configurations');
					setTimeout(function() {
						$('p#successMessageBoxSms').html('');
					}, 5000);
				}
			},error: function (){
				alert("Something went wrong");
				$('button#smsSetupForm_Btn').html("Submit");
			},
			beforeSend: function (xhr){
				$('button#smsSetupForm_Btn').html("<span class='fa fa-spin fa-spinner'></span> Processing...");
			}
		});
	});

	/*------Logo and Favicon Submit- > Ajax Calling Function-------------*/
	$("form#logoandFaviconForm").submit(function(e) {
		e.preventDefault();

		var xhr = new XMLHttpRequest()

		var formData = new FormData($(this)[0]);
		formData.append('file',xhr.file);

		var form = $(this);
		var actionUrl = form.attr('action');

		$.ajax({
			type: "POST",
			url: actionUrl,
			processData: false,
			contentType: false,
			cache: false,
			data: formData, // serializes the form's elements.
			success: function(response)
			{
				if (response){
					$('button#logoandFaviconForm_btn').html("Update");
					$('p#successMessageBoxLogoAndFavicon').html('Successfully Save Logo And Favicon Configurations');
					setTimeout(function() {
						$('p#successMessageBoxLogoAndFavicon').html('');
					}, 5000);
				}
			},error: function (){
				alert("Something went wrong");
				$('button#logoandFaviconForm_btn').html("Submit");
			},
			beforeSend: function (xhr){
				$('button#logoandFaviconForm_btn').html("<span class='fa fa-spin fa-spinner'></span> Processing...");
			}
		});
	});


	/*------Login Bg image and SignUp bg image Submit- > Ajax Calling Function-------------*/
	$("form#loginAndSignUpBgImageForm").submit(function(e) {
		e.preventDefault();

		var xhr = new XMLHttpRequest()

		var formData = new FormData($(this)[0]);
		formData.append('file',xhr.file);

		var form = $(this);
		var actionUrl = form.attr('action');

		$.ajax({
			type: "POST",
			url: actionUrl,
			processData: false,
			contentType: false,
			cache: false,
			data: formData, // serializes the form's elements.
			success: function(response)
			{
				if (response){
					$('button#loginAndSignUpBgImageForm_btn').html("Update");
					$('p#successMessageBoxLoginAndSignupBgImage').html('Successfully Save Logo And Favicon Configurations');
					setTimeout(function() {
						$('p#successMessageBoxLoginAndSignupBgImage').html('');
					}, 5000);
				}
			},error: function (){
				alert("Something went wrong");
				$('button#loginAndSignUpBgImageForm_btn').html("Submit");
			},
			beforeSend: function (xhr){
				$('button#loginAndSignUpBgImageForm_btn').html("<span class='fa fa-spin fa-spinner'></span> Processing...");
			}
		});
	});


	/*------MetaTitle Form Submit- > Ajax Calling Function-------------*/
	$("form#metaDescriptionForm").submit(function(e) {
		e.preventDefault();

		var form = $(this);
		var actionUrl = form.attr('action');

		$.ajax({
			type: "POST",
			url: actionUrl,
			data: form.serialize(), // serializes the form's elements.
			success: function(response)
			{
				if (response){
					$('button#metaDescriptionForm_btn').html("Update");
					$('p#successMessageMetabox').html('Successfully Save Meta Informations');
					setTimeout(function() {
						$('p#successMessageMetabox').html('');
					}, 5000);
				}
			},error: function (){
				alert("Something went wrong");
				$('button#metaDescriptionForm_btn').html("Submit");
			},
			beforeSend: function (xhr){
				$('button#metaDescriptionForm_btn').html("<span class='fa fa-spin fa-spinner'></span> Processing...");
			}
		});
	});

	/*------Save Copy Right Form Submit- > Ajax Calling Function-------------*/
	$("form#copyRightForm").submit(function(e) {
		e.preventDefault();

		var form = $(this);
		var actionUrl = form.attr('action');

		$.ajax({
			type: "POST",
			url: actionUrl,
			data: form.serialize(), // serializes the form's elements.
			success: function(response)
			{
				if (response){
					$('button#copyRightForm_btn').html("Update");
					$('p#successMessageCopyRightBox').html('Successfully Save Copyright Content');
					setTimeout(function() {
						$('p#successMessageCopyRightBox').html('');
					}, 5000);
				}
			},error: function (){
				alert("Something went wrong");
				$('button#copyRightForm_btn').html("Submit");
			},
			beforeSend: function (xhr){
				$('button#copyRightForm_btn').html("<span class='fa fa-spin fa-spinner'></span> Processing...");
			}
		});
	});

	/*------Currency Setup Form Submit- > Ajax Calling Function-------------*/
	$("form#currenceySetupForm").submit(function(e) {
		e.preventDefault();

		var form = $(this);
		var actionUrl = form.attr('action');

		$.ajax({
			type: "POST",
			url: actionUrl,
			data: form.serialize(), // serializes the form's elements.
			success: function(response)
			{
				if (response){
					$('button#currenceySetupForm_btn').html("Update");
					$('p#successMessageBox').html('Successfully Save Currency');
					setTimeout(function() {
						$('p#successMessageBox').html('');
					}, 5000);
				}
			},error: function (){
				alert("Something went wrong");
				$('button#currenceySetupForm_btn').html("Submit");
			},
			beforeSend: function (xhr){
				$('button#currenceySetupForm_btn').html("<span class='fa fa-spin fa-spinner'></span> Processing...");
			}
		});
	});

	$('#myTab a').on('click', function (e) {
		e.preventDefault()
		$(this).tab('show')
	})
</script>
</body>

</html>

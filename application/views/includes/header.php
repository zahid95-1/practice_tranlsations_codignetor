<?php
/*===================GetUserInfo=========================*/
$unique_id=$_SESSION['unique_id'];

$checkKycAttachment=$this->db->query("SELECT * FROM kyc_attachment where user_id='".$unique_id."'")->result();

$logoImage =base_url()."assets/images/logo-light.png";
$favIconImage=base_url()."assets/images/logo-light.png";

$getSettingsModel =$this->db->query("SELECT logo_image,favicon_image,meta_descriptions,meta_title FROM setting")->row();

$metaTitle=ConfigData['site_name'];
$metaDescriptions=ConfigData['site_name'];
if ( $getSettingsModel ){
	if ($getSettingsModel->logo_image) {
		$logoImage=base_url()."assets/settings/logo/".$getSettingsModel->logo_image;
	}

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

$getDepositNotification =$this->db->query("SELECT count(1) as unseen FROM payments where status = 0 ")->row();
$getWithdrawalPendingNotification =$this->db->query("SELECT count(1) as unseen FROM withdrawal where status = 1 ")->row();
$getNewRegNotification =$this->db->query("SELECT count(1) as unseen FROM users where notification = 0 ")->row();
$getInternaltransferNotification =$this->db->query("SELECT count(1) as unseen FROM internal_transfer where notification = 0 ")->row();
$getComtransferNotification =$this->db->query("SELECT count(1) as unseen FROM commission_transfer where status = 0 ")->row();

$userID = $_SESSION['user_id'];
$uniqueID = $_SESSION['unique_id'];
$getUserPaymentNotification =$this->db->query("SELECT count(1) as unseen FROM payments where user_notification = 0 and user_id = $userID and status = 1")->row();

$getUserIbComNotification =$this->db->query("SELECT count(1) as unseen FROM commission_transfer where user_notification = 0 and unique_id = '$uniqueID' and status = 1")->row();
?>
<!doctype html>
<html lang="en">

<head>

	<meta charset="utf-8" />
	<title><?=$metaTitle?> | <?=(isset($title)?$title:'')?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta content="<?=$metaDescriptions?>" name="description" />
	<meta content="Themesdesign" name="author" />
	<!-- App favicon -->
	<link rel="shortcut icon" href="<?=$favIconImage?>">

	<!-- Bootstrap Css -->
	<link href="<?=base_url()?>/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
	<!-- Icons Css -->
	<link href="<?=base_url()?>/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
	<!-- App Css-->
	<link href="<?=base_url()?>/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
	<!-- Custom Css -->
	<link href="<?=base_url()?>/assets/css/custom.css" rel="stylesheet" type="text/css" />

	<!-- DataTables -->
	<link href="<?=base_url()?>assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	<link href="<?=base_url()?>assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	<link href="<?=base_url()?>assets/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

	<!-- Responsive datatable examples -->
	<link href="<?=base_url()?>assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet"
		  type="text/css" />

	<script src="<?=base_url()?>assets/libs/jquery/jquery.min.js"></script>

	<link href="<?=base_url()?>assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
	
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
  <script>
  window.addEventListener('load', () => {
    setTimeout(() => {
      const frame = document.querySelector('.goog-te-banner-frame');
      if (frame) frame.remove();

      document.body.style.top = '0px';
    }, 10); // Delay to allow banner to appear before removing
  });
</script>



</head>

<body data-sidebar="dark">
	
<?php if(ConfigData['prefix']=='IGM' || ConfigData['prefix']=='TEC' || ConfigData['prefix']=='CFX' || ConfigData['prefix']=='IFX' || ConfigData['prefix']=='UFX' || ConfigData['prefix']=='TG'){ ?>
	<style>
	.navbar-brand-box {
	    background: white!important;
	}
	</style>
<?php } ?>

<div id="preloader">
	<div id="status">
		<div class="spinner">
			<i class="ri-loader-line spin-icon"></i>
		</div>
	</div>
</div>

<input type="hidden" value="dashboard" id="loadedDashboardContent">
<input type="hidden" value="<?=base_url()?>" id="baseUrl">


<!-- Begin page -->
<div id="layout-wrapper">

	<header id="page-topbar">
		<div class="navbar-header">
			<div class="d-flex">
				<!-- LOGO -->
				<div class="navbar-brand-box">
					<a href="<?=base_url()?>dashboard" class="logo logo-dark">
						<span class="logo-lg">
                                <img src="<?=$logoImage?>" alt="logo-dark" height="20" style="width:100%">
                            </span>
					</a>

					<a href="<?=base_url()?>admin/dashboard" class="logo logo-light">
						<span class="logo-lg">
                                <img src="<?=$logoImage?>" alt="logo-light" height="40" style="width:100%">
                            </span>
					</a>
				</div>

				<button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect"
						id="vertical-menu-btn">
					<i class="ri-menu-2-line align-middle"></i>
				</button>

				<?php if ($_SESSION['role']==0 && ConfigData['set_cron_job_trigger']==true) :?>
					<div class="mt-3" id="cronbtn">
						<a class="btn btn-sm edit" title="User View" href="<?php echo base_url() ?>get-trigger-live-trade">
							<button type="button" class="btn btn-primary btn-rounded waves-effect waves-light">Reload Live Trade</button>
						</a>
					</div>
					<div class="mt-3" id="cronbtn">
						<a class="btn btn-sm edit" title="User View" href="<?php echo base_url() ?>IBManagementController/generateIBCommissionCurrencyBased_latest">
							<button type="button" class="btn btn-primary btn-rounded waves-effect waves-light">Generate IB Commission</button>
						</a>
					</div>
				<?php endif; ?>

				<div style="margin-top: 21px;text-align: center;display: flex">
					<label style="    margin-top: 7px;
    margin-right: 13px;">Change Language</label>
					<form method="get" action="<?=base_url('LanguageSwitcher/switchLang')?>">
						<select class="form-select" name="language" onchange="window.location.href='<?=base_url()?>LanguageSwitcher/switchLang/'+this.value;" style="width: 100%">
							<option value="english" <?=($this->session->userdata('site_lang')=='english')?'selected':''?>>English</option>
							<option value="chinese" <?=($this->session->userdata('site_lang')=='chinese')?'selected':''?>>中文</option>
						</select>
					</form>
				</div>

				<!--<?php if ($_SESSION['role']==0 && ConfigData['reload_symbols_btn']==true) :?>-->
				<!--<div class="mt-3" id="cronbtn">-->
				<!--	<a class="btn btn-sm edit" title="User View" href="<?php echo base_url() ?>get-trigger-symbols">-->
				<!--		<button type="button" class="btn btn-primary btn-rounded waves-effect waves-light">Reload Symbols</button>-->
				<!--	</a>-->
				<!--</div>-->
				<!--<?php endif; ?>-->

				<?php
				if (isset($_SESSION['login_from'])){ ?>
					<div class="mt-3">
						<a class="btn btn-sm edit" title="User View" href="<?php echo base_url(); ?>login-admin-profile">
							<button type="button" class="btn btn-primary btn-rounded waves-effect waves-light">Back To Admin portal</button>
						</a>
					</div>
				<?php } ?>

				<?php
				if (isset($_SESSION['open_ib']) && $_SESSION['open_ib']==true){ ?>
					<div class="mt-3">
						<a class="btn btn-sm edit" title="User View" href="<?php echo base_url(); ?>user/back-user-panel">
							<button type="button" class="btn btn-primary btn-rounded waves-effect waves-light">Back To Client Portal</button>
						</a>
					</div>
				<?php }else{ ?>
					<?php
				  if (isset($_SESSION['ib_status']) && $_SESSION['ib_status']==1){ ?>
					<div class="mt-3">
						<a class="btn btn-sm edit" title="User View" href="<?php echo base_url(); ?>user/open-ib-panel">
							<button type="button" class="btn btn-primary btn-rounded waves-effect waves-light">IB Portal</button>
						</a>
					</div>
				<?php } ?>

			 <?php	} ?>
				
				
			</div>

			<div class="d-flex">

				<div class="dropdown d-inline-block d-lg-none ms-2">
					<button type="button" class="btn header-item noti-icon waves-effect"
							id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
							aria-expanded="false">
						<i class="ri-search-line"></i>
					</button>
					<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
						 aria-labelledby="page-header-search-dropdown">

						<form class="p-3">
							<div class="mb-3 m-0">
								<div class="input-group">
									<input type="text" class="form-control" placeholder="Search ...">
									<div class="input-group-append">
										<button class="btn btn-primary" type="submit"><i
													class="ri-search-line"></i></button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>


				<div class="dropdown d-none d-lg-inline-block ms-1" style="align-items: center; display: flex!important;top: 7px;">
					<div class="form-check form-switch mb-3 dark-mode-switch" id="darkModeOpen">
						<label class="form-check-label" style="margin-right: 11px;">Dark Mode</label>
					</div>

					<div class="form-check form-switch mb-3 light-mode-switch" id="lightModeOpen">
						<input class="form-check-input theme-choice" type="checkbox" id="light-mode-switch" checked="">
						<label class="form-check-label" for="light-mode-switch">Light Mode</label>
					</div>
					<div class="form-check form-switch mb-3 dark-mode-switch d-none" id="darkModeOpen">
						<input class="form-check-input theme-choice" type="checkbox" id="dark-mode-switch" data-bsstyle="assets/css/bootstrap-dark.min.css" data-appstyle="assets/css/app-dark.min.css">
						<label class="form-check-label" for="dark-mode-switch">Dark Mode</label>
					</div>
				</div>

				<div class="dropdown d-none d-lg-inline-block ms-1">
					<button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
						<i class="ri-fullscreen-line"></i>
					</button>
				</div>

				<div class="dropdown d-inline-block">
					<button type="button" class="btn header-item noti-icon waves-effect"
							id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
						<i class="ri-notification-3-line"></i>
						<span class="noti-dot"></span>
					</button>
					<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
						 aria-labelledby="page-header-notifications-dropdown">
						<div class="p-3">
							<div class="row align-items-center">
								<div class="col">
									<h6 class="m-0"> Notifications </h6>
								</div>
								<div class="col-auto">
									<a href="#!" class="small"> View All</a>
								</div>
							</div>
						</div>
						<?php if($_SESSION['role'] == 0) {?>
						<?php if($getDepositNotification->unseen > 0){ ?>
						<div data-simplebar style="max-height: 230px;">
						</div>
						<div class="p-2 border-top">
							<div class="d-grid">
								<a class="btn btn-sm btn-link font-size-16" href="<?php echo base_url()."admin/deposit/pending-deposit-list" ?>">
									See New Pending Deposits 
								</a>
							</div>
						</div>
						<?php } ?>
						<?php if($getWithdrawalPendingNotification->unseen > 0){ ?>
						<div data-simplebar style="max-height: 230px;">
						</div>
						<div class="p-2 border-top">
							<div class="d-grid">
								<a class="btn btn-sm btn-link font-size-16" href="<?php echo base_url()."admin/withdraw/user-request-withdraw-list" ?>">
									See New Pending Withdrawal Request 
								</a>
							</div>
						</div>
						<?php } ?>
						<?php if($getNewRegNotification->unseen > 0){ ?>
						<div data-simplebar style="max-height: 230px;">
						</div>
						<div class="p-2 border-top">
							<div class="d-grid">
								<a class="btn btn-sm btn-link font-size-16" href="<?php echo base_url()."admin/account/registered-account" ?>">
									See New Registrations
								</a>
							</div>
						</div>
						<?php } ?>
						
						<?php if($getInternaltransferNotification->unseen > 0){ ?>
						<div data-simplebar style="max-height: 230px;">
						</div>
						<div class="p-2 border-top">
							<div class="d-grid">
								<a class="btn btn-sm btn-link font-size-16" href="<?php echo base_url()."admin/transaction/internal-transfer-data-list" ?>">
									See New Internal Transfer Details
								</a>
							</div>
						</div>
						<?php } ?>
						<?php if($getComtransferNotification->unseen > 0){ ?>
						<div data-simplebar style="max-height: 230px;">
						</div>
						<div class="p-2 border-top">
							<div class="d-grid">
								<a class="btn btn-sm btn-link font-size-16" href="<?php echo base_url()."admin/transaction/internal-transfer-data-list" ?>">
									See New Commission Transfer Details
								</a>
							</div>
						</div>
						<?php } ?>
						<?php } ?>

						<?php if($_SESSION['role'] == 1){ ?>
							<?php if($getUserPaymentNotification->unseen > 0){ ?>
							<div data-simplebar style="max-height: 230px;">
							</div>
							<div class="p-2 border-top">
								<div class="d-grid">
									<a class="btn btn-sm btn-link font-size-16" href="<?php echo base_url()."user/deposit/history" ?>">
										See Your Approval Deposit Details
									</a>
								</div>
							</div>
							<?php } ?>
							<?php if($getUserIbComNotification->unseen > 0){ ?>
							<div data-simplebar style="max-height: 230px;">
							</div>
							<div class="p-2 border-top">
								<div class="d-grid">
									<a class="btn btn-sm btn-link font-size-16" href="<?php echo base_url()."user/ib-withdraw" ?>">
										See Your IB Commission Approval Details
									</a>
								</div>
							</div>
							<?php } ?>

							
						<?php } ?>
						
						<div data-simplebar style="max-height: 230px;">
						</div>
						<div class="p-2 border-top">
							<div class="d-grid">
								<a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
									<i class="mdi mdi-arrow-right-circle me-1"></i> View More..
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="dropdown d-inline-block user-dropdown">
					<button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
							data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<?php
						if(count($checkKycAttachment) > 0){
							foreach($checkKycAttachment as $getcheckKycAttachment){
								$profile_image=base_url()."assets/users/kyc/".$unique_id.'/'.$getcheckKycAttachment->profile_image;
							}
						}else{
							$profile_image = base_url()."assets/images/users/avatar-1.jpg";
						}

						?>
						<img class="rounded-circle header-profile-user" src="<?=$profile_image; ?>"
							 alt="Header Avatar">
						<span class="d-none d-xl-inline-block ms-1"><?php echo $_SESSION['username'] ?></span>
						<i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
					</button>
					<div class="dropdown-menu dropdown-menu-end">
						<!-- item-->
						<?php if($_SESSION['role'] == 1){ ?>
							<a class="dropdown-item" href="<?php echo base_url()."user/kyc" ?>"><i class="ri-user-line align-middle me-1"></i> <?= lang('profile') ?></a>
							<a class="dropdown-item" href="<?php echo base_url()."user/deposit" ?>"><i class="ri-wallet-2-line align-middle me-1"></i> Deposit Fund</a>
<!--							<a class="dropdown-item" href="--><?php //echo base_url()."delete-account" ?><!--"><i class="ri-delete-bin-5-fill align-middle me-1"></i> Delete Account</a>-->
						<?php }elseif($_SESSION['role'] == 0){ ?>
							<a class="dropdown-item" href="<?php echo base_url()."registered-account" ?>"><i class="ri-user-line align-middle me-1"></i> <?= lang('profile') ?></a>
							<a class="dropdown-item" href="<?php echo base_url()."user-deposit-create" ?>"><i class="ri-wallet-2-line align-middle me-1"></i> Deposit Fund</a>
							
						<?php } ?>
						
						<!-- <a class="dropdown-item d-block" href="#"><span
									class="badge bg-success float-end mt-1">11</span><i
									class="ri-settings-2-line align-middle me-1"></i> Settings</a>
						<a class="dropdown-item" href="#"><i class="ri-lock-unlock-line align-middle me-1"></i> Lock
							screen</a>
 -->						<div class="dropdown-divider"></div>
						<a class="dropdown-item text-danger" href="<?=base_url()?>/logout"><i
									class="ri-shut-down-line align-middle me-1 text-danger"></i> Logout</a>
					</div>
				</div>
			</div>
		</div>
	</header>

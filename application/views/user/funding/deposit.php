<?php
$getSettingsModel =$this->db->query("SELECT paypal_client_id,paypal_client_secret,paypal_status,stripe_client_id,stripe_client_secret,stripe_status FROM setting")->row();
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
                                                <h4 class="mb-sm-0"><?= lang('deposit') ?></h4>

						<div class="page-title-right">
							<ol class="breadcrumb m-0">
                                                                <li class="breadcrumb-item"><a href="javascript: void(0);"><?= lang('home') ?></a></li>
                                                                <li class="breadcrumb-item active"><?= lang('update_mt5_password') ?></li>
							</ol>
						</div>

					</div>
				</div>
			</div>
			<!-- end page title -->

			<div class="row deposit-block">
				<div class="col-lg-4 col-sm-12 col-md-4">
					<div class="card">
						<div class="card-body">
							<div class="title">
                                                                <h4><?= lang('deposit_with_wire_transfer') ?></h4>
							</div>
						   <div class="image-block">
							   <img src="<?=base_url()."assets/images/wire-transfer.png"?>">
						   </div>
							<div class="deposit-btn-sections">
                                                                <a href="<?=base_url()."user/deposit/wire-transfer"?>" class="btn btn-primary waves-effect waves-light"><?= lang('deposit_now') ?><i class="fas fa-angle-double-right"></i></a>
							</div>
						</div>
					</div>
				</div>

				<!--<div class="col-lg-4 col-sm-12 col-md-4">-->
				<!--	<div class="card">-->
				<!--		<div class="card-body">-->
				<!--			<div class="title">-->
				<!--				<h4>Deposit With Crypto Payment</h4>-->
				<!--			</div>-->
				<!--			<div class="image-block">-->
				<!--				<img src="<?=base_url()."assets/images/crypto.png"?>" class="border-css">-->
				<!--			</div>-->
				<!--			<div class="deposit-btn-sections">-->
				<!--				<?php if (ConfigData['enable_disable_crypto']==true): ?>-->
				<!--				<a href="<?php echo base_url() ?>crypto-payment" class="btn btn-primary waves-effect waves-light">Deposit Now<i class="fas fa-angle-double-right"></i></a>-->
				<!--			   <?php else: ?>-->
				<!--				<a href="javascript:void(0);" class="btn btn-primary waves-effect waves-light">Deposit Now<i class="fas fa-angle-double-right"></i></a>-->
				<!--				<?php endif; ?>-->
				<!--			</div>-->
				<!--		</div>-->
				<!--	</div>-->
				<!--</div>-->

				<?php if ($getSettingsModel->paypal_status==1 && $getSettingsModel->paypal_client_id!='' && $getSettingsModel->paypal_client_secret!=''): ?>
				<div class="col-lg-4 col-sm-12 col-md-4">
					<div class="card">
						<div class="card-body">
							<div class="title">
                                                                <h4><?= lang('deposit_with_paypal') ?></h4>
							</div>
							<div class="image-block">
								<img src="<?=base_url()."assets/images/paypal-logo.png"?>" class="border-css">
							</div>
							<div class="deposit-btn-sections">
                                                                <a href="<?=base_url()."user/deposit/paypal"?>" class="btn btn-primary waves-effect waves-light"><?= lang('deposit_now') ?><i class="fas fa-angle-double-right"></i></a>
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>

				<?php if ($getSettingsModel->stripe_status==1 && $getSettingsModel->stripe_client_id!='' && $getSettingsModel->stripe_client_secret!=''): ?>
					<div class="col-lg-4 col-sm-12 col-md-4">
						<div class="card">
							<div class="card-body">
								<div class="title">
                                                                        <h4><?= lang('deposit_with_stripe') ?></h4>
								</div>
								<div class="image-block">
									<img src="<?=base_url()."assets/images/stripe.png"?>" class="border-css">
								</div>
								<div class="deposit-btn-sections">
                                                                               <a href="<?=base_url()."user/deposit/stripe"?>" class="btn btn-primary waves-effect waves-light"><?= lang('deposit_now') ?><i class="fas fa-angle-double-right"></i></a>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>

			</div>
			<!-- end row -->

		</div> <!-- container-fluid -->
	</div>

</div>

<?php
/*===================GetUserInfo=========================*/
$unique_id=$_SESSION['unique_id'];
$getCountry = $this->db->query("SELECT id,nicename FROM country");
if(isset($caddress)){
	$walletaddress = $caddress->wallet_address;
}else{

}

?>
<style>
	.bg-danger {
		background-color: #ff3d60!important;
		width: 43%;
		padding: 8px;
	}
	.rounded-pill {
		padding-right: 2.6em!important;
		padding-left: 2.6em!important;
		padding-top: 6px!important;
		padding-bottom: 4px!important;
	}
	.document-table,.document-table th,.document-table td {
		border: 2px solid gray!important;
	}
</style>
<div class="main-content" id="result">
	<div class="page-content">
		<div class="container-fluid">

			<!-- start page title -->
			<div class="row">
				<div class="col-12">
					<div class="page-title-box d-sm-flex align-items-center justify-content-between">
						<h4 class="mb-sm-0">Deposit</h4>
						<div class="page-title-right">
							<ol class="breadcrumb m-0">
								<li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
								<li class="breadcrumb-item active">Crypto</li>
							</ol>
						</div>

					</div>
				</div>
			</div>
			<!-- end page title -->

			<div class="row">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-body">
							 <p style="color:green;"><?php echo $this->session->flashdata('msg'); ?></p> 

							<form method="post" class="form-control" autocomplete="off" action="<?php echo base_url(); ?>checkout-coinpayment-amount"   data-key-index="0">

					<input type="hidden" value="<?php echo $unique_id;?>" name="uid" id="uid">
					 <input type="hidden" value="<?php echo $_SESSION["email"] ?>" name="email" id="email" class="form-control" >
						<div class="row mb-3">
							
							<div class="col-sm-6"> 

										<label for="example-text-input" class="col-sm-8 col-form-label">Account ID<span class="error">*</span></label>
										<div class="mb-3">
											<select class="form-control select2" name="mt5_login_id" required>
												<option value="">Select account ID</option>
												<?php if (isset($dataItem)):foreach ($dataItem as $key=>$item):?>
													<option value="<?=$item->mt5_login_id?>" dataMinDeposit="<?=$item->minimum_deposit?>"><?=$item->mt5_login_id?></option>
												<?php endforeach; endif; ?>
											</select>
										</div>
										<span class="error"><?=isset($errorObject->mt5_login_id)?$errorObject->mt5_login_id:''?></span>
									

								
									<label for="bank_address" class="col-sm-6 col-form-label">Enter Amount in USD :<span style="color:red;">
								*<span/> </label>
								<input type="number" class="form-control" id="amount" name="amount" 
								required  placeholder="Enter In USD">
								<label for="coin_address" class="col-sm-2 col-form-label">Coin:<span style="color:red;">
								*<span/> </label>
								<select name = "coin_address" id="coin_address" class="form-control" required >
										<option>Select your Coin</option>
										<?php foreach($coin as $getcoin){ ?>
										<option value="<?php echo $getcoin->name ?>"
											><?php echo $getcoin->name ?></option>
										}
									<?php } ?>
									</select>
								
									
								
							</div>
							<div class="col-lg-6" style="margin-top: 38px;">
								<div class="card border border-danger">
									<div class="card-header bg-transparent border-danger">
										<h5 class="my-0 text-danger"><i class="mdi mdi-block-helper me-3"></i>PLEASE FOLLOW THE STEPS TO MAKE CRYPTO PAYMENT</h5>
									</div>
									<div class="card-body">
										<h5 class="card-title">1. Scan given QR code or copy wallet address to make the payment.</h5>
										<h5 class="card-title">2. Please be sure to make full payment, we will be not responsive in case of failure payment due to partial payment.</h5><br/>
										<h5 class="card-title">3. In case if you need any support than please mail to, <?=ConfigData['support_mail']?></h5><br/>
										
																				<br/>
									</div>
								</div>
							</div>
						</div>
					
					<div class="row" style="margin-left: 127px;margin-top: 25px;">
						<span style="color:green;"></span>
						<button class="btn btn-primary" type="submit" style="width: 120px;">Checkout</button>
					</div>
				</form>

						</div>
					</div>
				</div>
			</div>
			<!-- end row -->
		</div> <!-- container-fluid -->
	</div>
</div>
<!-- end main content-->





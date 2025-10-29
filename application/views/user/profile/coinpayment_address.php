<?php
/*===================GetUserInfo=========================*/
$unique_id=$_SESSION['unique_id'];
$getCountry = $this->db->query("SELECT id,nicename FROM country");
$walletaddress='';
if(isset($caddress) && !empty($caddress)){
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
						<h4 class="mb-sm-0">ADD YOUR CRYPTO WALLET ADDRESS</h4>
						<div class="page-title-right">
							<ol class="breadcrumb m-0">
								<li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
								<li class="breadcrumb-item active">Crypto Wallet Address</li>
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

							<form method="post" class="form-control" autocomplete="off" action="<?php echo base_url(); ?>submit-coinpayment-address"   data-key-index="0">

					<input type="hidden" value="<?php echo $unique_id;?>" name="uid" id="uid">
						<div class="row mb-3">
							
							<div class="col-sm-6">
                                <label for="coin_address" class="col-sm-2 col-form-label">Coin:<span style="color:red;">
								*<span/> </label>
                                <select name = "coin" id="coin" class="form-control" required >
                                    <option>Select your Coin</option>
                                    <?php foreach($coin as $getcoin){ ?>
                                        <option value="<?php echo $getcoin->coin_id ?>"
                                            <?php if($getcoin->coin_id == isset($caddress->coin_id)) { ?> selected <?php } ?>
                                        ><?php echo $getcoin->name ?></option>
                                        }
                                    <?php } ?>
                                </select>



                                <label for="bank_address" class="col-sm-6 col-form-label">Enter Wallet Address. :<span style="color:red;">
								*<span/> </label>
								<input type="text" class="form-control" id="coinpayment_address" name="coinpayment_address" value = "<?php if(isset($caddress) && !empty($caddress)){ echo $caddress->wallet_address; }  ?>"
								required  placeholder="Enter wallet address">

									
								
							</div>
						</div>
					
					<div class="row" style="margin-left: 127px;margin-top: 25px;">
						<span style="color:green;"></span>
						<button class="btn btn-primary" type="submit" style="width: 120px;">Submit</button>
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





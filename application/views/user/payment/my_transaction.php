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
							<div class="row">
								<div class="col-sm-6">
									
							    
                                            <b style="color:green"><?php echo $this->session->flashdata('msg'); ?></b>
                                            <h1>Pay with <?php echo $this->session->flashdata('p_rcurrency') ?></h1>
                                            <!--<h5 style="font-style: italic;">to <strong><?php echo $this->session->flashdata('p_username'); ?></strong></h5>-->
                                            <br><br>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>Amount (<?php echo $this->session->flashdata('p_rcurrency'); ?>):<?php echo $this->session->flashdata('p_camount'); ?> <?php echo $this->session->flashdata('p_rcurrency'); ?></p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>Amount (USD): <?php echo $this->session->flashdata('p_usd_amt'); ?>   USD</p>
                                                </div>
                                               
                                            </div>
                                            
                                            
                                            <center><img width="220" height="220" alt="" src="<?php echo $this->session->flashdata('p_qrcode'); ?>">
                                            </center></br><br>
                                            <div class="row">
                                                <div class="col-sm-1">
                                                </div>
                                                <div class="col-sm-7">
                                                    <input class="form-control" style="color:black" type="text" readonly name="address" id="address" value="<?php echo $this->session->flashdata('p_address'); ?>">
                                                </div>
                                                <div class="col-sm-4">
                                                    <button type="button" class="btn btn-sm btn-light" onclick="copyAddress()">
                                                        <b>Copy Address</b>
                                                    </button>
                                                </div>
                                            </div>
                                              
                                                
                                            <!--<a href="<?php echo $this->session->flashdata('p_status_url'); ?>" class="btn btn-warning btn-rounded" style="color:black;" target="_blank"><b><i class="fa fa-bitcoin"></i>    Pay Now</b></a>-->
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

                                            
                                        
                                

				

						</div>
					</div>
				</div>
			</div>
			<!-- end row -->
		</div> <!-- container-fluid -->
	</div>
</div>
<!-- end main content-->
 <script> 
    function copyAddress() { 
      var copyGfGText = document.getElementById("address"); 
      copyGfGText.select(); 
      document.execCommand("copy"); 
      alert("Copied the Address."); 
    }  
</script>






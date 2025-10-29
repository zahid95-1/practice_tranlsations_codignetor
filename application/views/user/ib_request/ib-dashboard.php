<?php
$sessionUser = $_SESSION['unique_id'];
 ?>
<style>
	.referal-sections{
		display: flex;
		justify-content: space-between;
	}
	.referal{
		margin-top: -9px;
		margin-bottom: 3px;
	}
	textarea#referral_link {
		line-height: 0!important;
		padding: 40px 8px;
		pointer-events: none;
	}
	.page-content {
		/*padding: calc(34px + 24px) calc(24px / 2) 60px calc(24px / 2) !important;*/
	}
	input#referral_link {
		margin-top: 16px;
	}
	.referal-sections {
		margin-top: 21px;
	}
</style>

<div class="main-content" id="result">

	<div class="page-content">
		<div class="container-fluid">

			<!-- start page title -->
			<div class="row">
				<div class="col-12">
					<div class="page-title-box d-flex align-items-center justify-content-between">
						<h4 class="mb-0"><?= lang('ib_dashboard') ?></h4>
						<div class="page-title-right">
							<ol class="breadcrumb m-0">
                                                                <li class="breadcrumb-item"><a href="user_dashboard.html"><?= lang('home') ?></a></li>
								<li class="breadcrumb-item active"><?= lang('ib_dashboard') ?></li>
							</ol>
						</div>

					</div>
				</div>
			</div>

			<!-- end page title -->
			<div class="row">
				<div class="col-xl-12">
					<div class="row">
						<div class="col-md-3">
						    <a href="<?php echo base_url()."user/ib-withdraw" ?>">
							<div class="card">
								<div class="card-body">
									<div class="d-flex">
										<div class="flex-1 overflow-hidden">
                                                                               <p class="text-truncate font-size-14 mb-2"><?= lang('withdraw_commission') ?></p>
											<h4 class="mb-0">$<?=$dataItem['withdraw_commission']?></h4>
										</div>
										<div class="text-primary ms-auto">
											<i class="ri-stack-line font-size-24"></i>
										</div>
									</div>
								</div>
							</div>
							</a>
						</div>
                        
                        
						<div class="col-md-3">
						    <a href="<?php echo base_url()."user/my-commission" ?>">
							<div class="card">
								<div class="card-body">
									<div class="d-flex">
										<div class="flex-1 overflow-hidden">
                                                                               <p class="text-truncate font-size-14 mb-2"><?= lang('available_commission') ?></p>
											<h4 class="mb-0">$<?=$dataItem['available_commission']?></h4>
										</div>
										<div class="text-primary ms-auto">
											<i class="ri-store-2-line font-size-24"></i>
										</div>
									</div>
								</div>
							</div>
							</a>
						</div>
                        
                        
						<div class="col-md-3">
							<div class="card">
								<div class="card-body">
									<div class="d-flex">
										<div class="flex-1 overflow-hidden">
                                                                               <p class="text-truncate font-size-14 mb-2"><?= lang('total_volume') ?></p>
											<h4 class="mb-0"><?=number_format($dataItem['total_volume'], 2);?></h4>
										</div>
										<div class="text-primary ms-auto">
											<i class="ri-briefcase-4-line font-size-24"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
                        
						<div class="col-md-3">
						    <a href="<?php echo base_url()."user/my-clients" ?>">
							<div class="card">
								<div class="card-body">
									<div class="d-flex">
										<div class="flex-1 overflow-hidden">
                                                                               <p class="text-truncate font-size-14 mb-2"><?= lang('total_clients') ?></p>
											<h4 class="mb-0"><?=$dataItem['total_client']?></h4>
										</div>
										<div class="text-primary ms-auto">
											<i class="ri-account-circle-fill font-size-24"></i>
										</div>
									</div>
								</div>
							</div>
							</a>
						</div>
                        
					</div>
					<!-- end row -->
				</div>
			</div>
			<!-- end row -->

			<!-- end page title -->
			<div class="row">
				<div class="col-xl-12">
					<div class="row">
						<div class="col-lg-6">
							<div class="card">
								<div class="card-body">
                                                                        <h4 class="card-title mb-4"><?= lang('monthly_commission') ?></h4>
									<canvas id="bar" height="300"></canvas>
								</div>
							</div>
						</div> <!-- end col -->

						<div class="col-md-6">
							<div class="card">
								<div class="card-body" style="min-height: 382px;">
                                                                        <h4 class="card-title mb-4"><?= lang('my_client_transaction') ?></h4>
									<canvas id="doughnut" height="260"></canvas>

								</div>
							</div>
						</div>
					</div>
					<!-- end row -->
				</div>
			</div>
			<!-- end row -->

			<div class="row">
				

					<div class="col-lg-3">
						<div class="card">
							<div class="card-body">
								<div class="d-flex">
									<div class="flex-1 overflow-hidden">
                                                                               <p class="text-truncate font-size-14 mb-2"><?= lang('active_traders') ?></p>
										<h4 class="mb-0"><?=$dataItem['active_traders']?></h4>
									</div>
									<div class="text-primary ms-auto">
										<i class="ri-stack-line font-size-24"></i>
									</div>
								</div>
							</div>
						</div>
					</div>

				 <?php if (ConfigData['prefix']!='TG'): ?>
					<div class="col-lg-3">
						<div class="card">
							<div class="card-body">
								<div class="d-flex">
									<div class="flex-1 overflow-hidden">
                                                                               <p class="text-truncate font-size-14 mb-2"><?= lang('upline') ?></p>
										<h4 class="mb-0"><?=$dataItem['upline']?></h4>
									</div>
									<div class="text-primary ms-auto">
										<i class="ri-stack-line font-size-24"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>

					<div class="col-lg-3">
						<div class="card">
							<div class="card-body">
								<div class="d-flex">
									<div class="flex-1 overflow-hidden">
                                                                               <p class="text-truncate font-size-14 mb-2"><?= lang('active_sub_ib') ?></p>
										<h4 class="mb-0"><?=$dataItem['active_sub_ib']?></h4>
									</div>
									<div class="text-primary ms-auto">
										<i class="ri-stack-line font-size-24"></i>
									</div>
								</div>
							</div>
						</div>
					</div>


					<!-- <div class="col-lg-6">
						<div class="card">
							<div class="card-body">
                                                                <span><i class=" fas fa-link"></i> <?= lang('referral_link') ?></span>
								<?php if($dataItem['ib_commission'] > 0){ ?>
								<div>
									
									<div class="referal-sections">
										
										<span class="referal">
											<span id="copiedMessage" style="color:green;margin-top: 10px; margin-left: 14px;"></span>
											<button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="button" aria-pressed="false" style="padding: 5px;" onclick="copyLink()">
                                                                               <i class="fas fa-copy"></i> <?= lang('copy') ?>
                                            </button>
											</span>
									</div>
									<div class="ginger-module-highlighter ginger-module-highlighter-ghost" style="height: 81.7656px; width: 755.5px; position: absolute; top: 668.359px; left: 20px;"></div>
									<input type="text" class="form-control" maxlength="225" placeholder="This textarea has a limit of 225 chars" id="referral_link" value="<?php echo base_url(); ?>register?reffid=<?php echo $_SESSION['unique_id']."&link=0" ?>">
								</div>
								<?php }else{ ?>
                                                                        <p style="color:red"><?= lang('please_goto_commission_setting') ?></p>
								<?php } ?>
							</div>
						</div>
					</div> -->


					<div class="col-lg-6">

						<div class="card">
							<div class="card-body">
                                                                <span><i class=" fas fa-link"></i> <?= lang('referral_link') ?></span>
								<?php if($dataItem['ib_commission'] > 0){ ?>
								
								<?php for($i = 1;$i <= $dataItem['ib_commission'] ;$i++){

									$getselfrr = $this->db->query("SELECT * FROM `ib_commission`where  unique_id = '$sessionUser' and ref_link_name = '$i' and level_no = (SELECT MAX(level_no) FROM `ib_commission`where  unique_id = '$sessionUser' and ref_link_name = '$i' )")->result(); 
								 ?>
								 

								 <?php 

											foreach($getselfrr as $getselfrdetails){

											
									 ?>
								<div>
									<!-- <div class="referal-sections">
										<span class="referal">
											<span id="copiedMessage" style="color:green;margin-top: 10px; margin-left: 14px;"></span>
											<button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="button" aria-pressed="false" style="padding: 5px;" onclick="copyLink()">
                                                                               <i class="fas fa-copy"></i> <?= lang('copy') ?>
                                            </button>
											</span>
									</div> -->
									<div class="col-sm-4 mt-2">
                                                                       <span><?= lang('downline_share_value') ?>:<input type="number" value="<?php echo $getselfrdetails->value; ?>" name="downline_share" id="downline_share"  class="form-control" <?php if(isset($getselfrdetails->value)){ ?> readonly <?php } ?> placeholder="Enter Downline Commission">
										</div>
									

									

									<img src="<?php echo base_url() ?>IBRequestController/QRcode" > ;
                                                                        <p><?= lang('referral_link') ?> : <?php echo base_url(); ?>register?reffid=<?php echo $_SESSION['unique_id'] ?>&link=<?php echo $i ?></p>

									<hr>
								
							<?php } 

							} ?>

                                                                <?php }else{ ?>
                                                                        <p style="color:red"><?= lang('please_goto_commission_setting') ?></p>
                                                                <?php } ?>
							</div>
						</div>
					</div>

				</div>

					<!-- <div class="col-lg-6">
						<div class="card">
							<div class="card-body">
							    <span><i class=" fas fa-link"></i>Referral QR</span><br>
								<?php if($dataItem['ib_commission'] > 0){ ?>
							   <img src="<?php echo base_url() ?>IBRequestController/QRcode" > ;
							   <?php } ?>
							</div>
						</div>
					</div>
 -->


				<div class="col-lg-9">
					<div class="card">
						<div class="card-body">

                                                        <h4 class="card-title mb-4"><?= lang('top_5_sub_ib_earning') ?></h4>

							<div class="table-responsive">
								<table class="table table-centered datatable dt-responsive nowrap" data-bs-page-length="5"
									   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
									<thead class="table-light">
									<tr>
                                                                               <th><?= lang('name') ?></th>
                                                                               <th><?= lang('emails') ?></th>
                                                                               <th><?= lang('total_lots') ?></th>
                                                                               <th><?= lang('commission_earned') ?></th>
									</tr>
									</thead>
									<tbody>
									<?php if ($dataItem['top_5_sub_ibs']){ foreach ($dataItem['top_5_sub_ibs'] as $key=>$topItem){?>
									<tr>
										<td><?=$topItem->name?></td>
										<td><?=$topItem->email?></td>
										<td><?=number_format($topItem->total_lot , 2);?></td>
										<td>$<?=number_format($topItem->commission , 2) ?></td>
									</tr>
									<?php  } }?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>

	</div>
	<!-- End Page-content -->
</div>
<!-- end main content-->

<script>
	function copyLink() {
		var copyGfGText = document.getElementById("referral_link");
		copyGfGText.select();
		document.execCommand("copy");
		$('#copiedMessage').html("Copied!");
		setTimeout(function() {
			$('#copiedMessage').html("");
		}, 2000);
	}
</script>

<script src="<?=base_url()?>assets/libs/chart.js/Chart.bundle.min.js"></script>
<script src="<?=base_url()?>assets/js/chartjs.js"></script>




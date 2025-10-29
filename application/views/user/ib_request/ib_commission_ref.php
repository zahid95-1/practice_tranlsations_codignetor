<link href="<?=base_url()?>assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

	<style>
		.rounded-pill {
			padding-right: 2.6em!important;
			padding-left: 2.6em!important;
			padding-top: 6px!important;
			padding-bottom: 4px!important;
		}
		.document-table,.document-table th,.document-table td {
			border: 2px solid gray!important;
		}
		span.select2.select2-container.select2-container--default {
			width: 100%!important;
		}
	</style>
	<div class="main-content" id="result">
		<div class="page-content">
			<div class="container-fluid">
				<?php 
				$c = 0;
				foreach ($UserIbCommissionRef as $getUserIbCommissionRef) {
				$c++;
				$sessionUser = $_SESSION['unique_id'];
				$getselfr = $this->db->query("SELECT * FROM `ib_commission_ref` where unique_id = '$sessionUser' ;")->result();
				$getself = $this->db->query("SELECT * FROM `ib_commission_ref` where unique_id = '$sessionUser' and plan_id = ".$getUserIbCommissionRef->plan_id." and group_id=".$getUserIbCommissionRef->group_id." and level_no = ".$getUserIbCommissionRef->downline_level." and ref_link_name = ".$getUserIbCommissionRef->ref_link_name." ;")->row();
				 ?>
				<div class="row">
					<div class="col-12">
						
						<div class="card">
							<div class="card-body">
								
								<h4 class="card-title mb-4">Edit Commission Group</h4>
								<form class="form-control" action="<?php echo base_url()."save-commission-ref"?>" method="post">
								<div class="row">
									<h3>Ref Link : <?php echo $getUserIbCommissionRef->ref_link_name ?> </h3>
									<div class="col-sm-6">
										<input type="text" class="form-control" name="" value="<?php echo $getUserIbCommissionRef->group_name ?> " readonly>
										<input type="hidden" name="group_id_<?php echo $c ?>" id="group_id" value="<?php echo $getUserIbCommissionRef->group_id ?>">
									</div>
									<div class="col-sm-6">
										<input type="text" class="form-control" name="" value="<?php echo $getUserIbCommissionRef->plan_name ?> " readonly>
										<input type="hidden" name="plan_id_<?php echo $c ?>" id="plan_id" value="<?php echo $getUserIbCommissionRef->plan_id ?>">
									</div>

									<input type="hidden" name="ref_link_name_<?php echo $c ?>" id="ref_link_name" value="<?php echo $getUserIbCommissionRef->ref_link_name ?>">
									
									<input type="hidden" name="downline_level_<?php echo $c ?>" id="downline_level" value="<?php echo $getUserIbCommissionRef->downline_level ?>">

									<label class="mt-4">Commission /lot from Upline</label>

										<div class="col-sm-2 mt-2">
											<input type="number" value="<?php echo $getUserIbCommissionRef->value ?>" name="sharefromupline_<?php echo $c ?>" class="form-control"readonly >
										</div>

									<label class="mt-4">Enter Downline share</label>
									<?php if(count($getselfr) > 0){ ?>
										<div class="col-sm-2 mt-2">
											<input type="number" value="<?php echo $getself->value; ?>" name="downline_share_<?php echo $c ?>" id="downline_share"  class="form-control" <?php if(isset($getself->value)){ ?> readonly <?php } ?> placeholder="Enter Downline Commission">
										</div>
									<?php }else{ ?>	
										
										<div class="col-sm-2 mt-2">
											<input type="number" max="<?php echo $getUserIbCommissionRef->value ?>" step = "0.1" name="downline_share_<?php echo $c ?>" id="downline_share"  class="form-control" placeholder="Enter Downline Commission">
										</div>
									<?php } ?>
									
							</div>
							
								<?php 	}  ?>
								
									<div class="row">
										<?php if(count($getselfr) <= 0){ ?>
										<div class="col-sm-2 mt-2">
												<button type="submit" class="btn btn-outline-success btn-sm edit  waves-effect waves-light mt-3" id="">Submit</button>
									</div>
								<?php } ?>
								</div>
							</div>
						</div>
						
					</div> <!-- end col -->
				</div> <!-- end row -->
				</form>
			</div>

		</div>
		<!-- End Page-content -->
	</div>
	<!-- end main content-->

	<!-- Sweet Alerts js -->
	<script src="<?=base_url()?>assets/libs/sweetalert2/sweetalert2.min.js"></script>

	<!-- Sweet alert init js-->
	<script src="<?=base_url()?>assets/js/pages/sweet-alerts.init.js"></script>

	<script>
		$('#removeIb').click(function () {
			Swal.fire(
				{
					title: "Remove IB?",
					text: 'Do you want to remove IB?',
					icon: 'question',
					confirmButtonColor: '#5664d2'
				}
			)
		});
	</script>

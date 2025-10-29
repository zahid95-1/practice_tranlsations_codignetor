<?php
$sessionUser = $_SESSION['unique_id'];
$getself = $this->db->query("SELECT * FROM `ib_commission` where unique_id = '$sessionUser' ;")->row();
$getselfr = $this->db->query("SELECT * FROM `ib_commission` where unique_id = '$sessionUser' ;")->result();


  $getReflinkCnt = $this->db->query("SELECT max(ref_link_name) as ref_link_cnt FROM `ib_commission`where  unique_id = '$sessionUser'")->row();

 ?>
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

				<div class="row">
					<div class="col-12">
						<div class="card">
							<div class="card-body">
								
								<h4 class="card-title mb-4">Edit Commission Group</h4>
								<form class="form-control" action="<?php echo base_url()."save-commission-group"?>" method="post">
									<?php 
									$var = '';
									foreach ($UserIbCommissionGroup as $getUserIbCommissionGroup_p) {
									    
									    $var = $var . $getUserIbCommissionGroup_p->group_name.'-'.$getUserIbCommissionGroup_p->value.'/';
									}
									$pname = $getUserIbCommissionGroup_p->plan_name;
										?>
								    <div class="row mt-2">
								        <?php if (ConfigData['prefix']!='TG'): ?>
								        <div class="col-sm-3">
    										<input type="text" class="form-control" name="" value="<?php echo $pname ?> " readonly>
    										<small>
    										    <b><?php echo $var; ?></b>
    										</small>
    									</div>
    									<?php endif; ?>
								    </div>
									<input type="hidden" name="downline_level" id="downline_level" value="<?php echo $getUserIbCommissionGroup_p->downline_level ?>">

									<div class="row mt-2">
									
									
									 <label class="mt-4">Create Link and Enter Downline share:</label>
										<div class="row">
										<div class="row mt-3 mb-5">
											<div class="col-md-8">
												<input type="number" name="ref_link_count" placeholder="HOW MANY LINKS" class="form-control input-lg levelGenerate" id="levelName-1">
											</div>
											<div class="col-md-4">
												<button type="button" class="btn btn-outline-success btn-md edit  waves-effect waves-light" data-packageType="1" id="generatesBtn" >GENERATE </button>
											</div>
										</div>
											<input type="hidden" name="_token" value="H3UArMNaAcwqea1MFbjRm7gZEeti0T6OZTbsJokU">
											<input type="hidden" name="commission_type" value="2">
											<div class="levelForm" id="levelForm-2">
												<div class="form-group">
													<div class="row">
														<div class="col-md-12">
															<div class="col-md-12" id="packageFormField-1">

															</div>
														</div>
													</div>
												</div>
											</div>
									</div>
									
									</div>
								
								<div class="row">
							
									<div class="col-sm-2 mt-2">
										<button type="submit" class="btn btn-outline-success btn-sm edit  waves-effect waves-light mt-3" id="">Submit</button>
									</div>
								
							</div>
								</form>
							</div>
						</div>
					</div> <!-- end col -->
				</div> <!-- end row -->
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

			$(document).on('click', 'button#generatesBtn', function() {
			var packageTypeName=$(this).data('packagetype');
			var levelNumber=$('input#levelName-'+packageTypeName+'').val()||'';

			if (levelNumber===''){
				$('input#levelName-'+packageTypeName+'').addClass("error-number");
			}else{
				$('input#levelName-'+packageTypeName+'').removeClass("error-number");
				var html='';
				for (var index=1;index<=levelNumber;index++){
					html+=`<div class="mainrow" id="maindivRow-`+index+`-`+packageTypeName+`">
								<div class="row mt-2">
								<div class="row mt-2">
								<div class="col-sm-2 mt-2">
    								<label>Plan Name: </label>
    							</div>
    							<div class="col-sm-3 mt-2">
    								<input type="text" class="form-control margin-top-10 no-left-border " name="p_name_`+index+`" id="p_name_`+index+`" value="" placeholder="Enter Plam Name" required>
								</div>
								</div>
									<div class="row mt-2">
									 
								       <?php foreach ($UserIbCommissionGroup as $getUserIbCommissionGroup) {
										?>
								        <div class="col-sm-2 mt-2">
    										<label><?php echo $getUserIbCommissionGroup->group_name.'/'.$getUserIbCommissionGroup->value ; ?>: </label>
    									</div>
    									<div class="col-sm-3 mt-2">
    									    	<input type="hidden" value="<?php echo $getUserIbCommissionGroup->value ?>" name="sharefromupline_`+index+`_`+<?php echo $getUserIbCommissionGroup->group_id ?>+`" class="form-control" readonly >
    									    <input type="hidden" name="plan_id_`+index+`" id="plan_id_`+index+`" value="<?php echo $getUserIbCommissionGroup->plan_id ?>">
									        <input type="hidden" name="group_id_`+index+`_`+<?php echo $getUserIbCommissionGroup->group_id ?>+`" id="group_id_`+index+`_`+<?php echo $getUserIbCommissionGroup->group_id ?>+`" value="<?php echo $getUserIbCommissionGroup->group_id ?>">
									        <input name="level_share_value_`+index+`_`+<?php echo $getUserIbCommissionGroup->group_id ?>+`" class="form-control margin-top-10 no-left-border " type="number"  value=""  step = "0.01"  max="<?php echo $getUserIbCommissionGroup->value ?>"  required placeholder="Share value">
                                        </div>
									    <div cass="col-sm-6">
									    </div>
    								    <?php } ?>
								    </div>
									
									<div class="col-md-4">
									<span class="input-group-btn">
								     <button class="btn btn-outline-danger btn-md edit  waves-effect waves-light" type="button" id="deleteOptions" data-index=`+index+` data-packagetype=`+packageTypeName+`><i class="fa fa-times"></i></button>
									</span>
									
									</div>
									<div>
									<hr mt-4 style="color:grey">
									</div>
									
								</div>
							</div>`;
				}
				$('div#packageFormField-'+packageTypeName+'').html(html);
				$('button#submitBtn').removeClass('d-none');
			}
		});

		$(document).on('click', 'button#deleteOptions', function() {
			var currentIndex=$(this).data('index');
			var packagetype=$(this).data('packagetype');
			$('div#maindivRow-'+currentIndex+'-'+packagetype+'').remove();
		});
	</script>

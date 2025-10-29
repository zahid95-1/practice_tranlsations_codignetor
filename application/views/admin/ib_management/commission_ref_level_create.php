
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
		div.mainrow {
			margin-bottom: 14px;
		}
	</style>
	<div class="main-content" id="result">
		<div class="page-content">
			<div class="container-fluid">

				<div class="row">
					<div class="col-12">
						<div class="card">
							<div class="card-body">
								<h4 class="card-title mb-4">Add New Commision Refferal level</h4>
								<p style="color:red;"><?php echo $this->session->flashdata('msg'); ?></p>
								<form class="form-control" action="<?php echo base_url()."admin/ib-management/commission-ref-level"?>" method="post">
									<div class="row">
										<div class="col-sm-6">
											<select class="form-control" name="plan_id" id="plan_id" required>
												<option>Select Plan</option>
												<?php foreach($ibplanlist as $getibplanlist){ ?>
													<option value="<?php echo $getibplanlist->plan_id ?>"><?php echo $getibplanlist->plan_name  ?></option>
												<?php } ?>
											</select>
										</div>
										<div class="col-sm-6">
											<select class="form-control" name="group_id" id="group_id">
												<option>Select Group</option>
												<?php foreach($grouplist as $getgrouplist){ ?>
													<option value="<?php echo $getgrouplist->id ?>"><?php echo $getgrouplist->mt5_group_name  ?></option>
												<?php } ?>

											</select>
										</div>
									</div>
									<label class="mt-4">Downline Level Setting</label>

									<div class="row">
										<div class="row mt-3 mb-5">
											<div class="col-md-8">
												<input type="number" name="level" placeholder="HOW MANY LINKS" class="form-control input-lg levelGenerate" id="levelName-1">
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
									<button type="submit" class="btn btn-outline-success btn-md edit  waves-effect waves-light mt-3 d-none" id="submitBtn">Submit</button>
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
								<div class="row">
									
									<div class="col-md-4">
										<input name="LevelSetting[`+index+`][share_value]" class="form-control margin-top-10 no-left-border " type="number"  value="" required placeholder="Share value">
									</div>
									<div class="col-md-4">
									<span class="input-group-btn">
								     <button class="btn btn-outline-danger btn-md edit  waves-effect waves-light" type="button" id="deleteOptions" data-index=`+index+` data-packagetype=`+packageTypeName+`><i class="fa fa-times"></i></button>
									</span></div>
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

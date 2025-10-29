	<?php
	/*===================GetUserInfo=========================*/
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
								<a href="<?php echo base_url()."admin/ib-management/edit-commission-group" ?>"><button type="button" class="btn btn-outline-secondary btn-sm edit  waves-effect waves-light " id="">
													 Add Commission Group
												</button></a>
								<h4 class="card-title mb-4">Edit Commission Group</h4>
								<form class="form-control" action="<?php echo base_url()."save-commission-group-admin"?>" method="post">
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
									<label class="mt-4">Downline Setting</label>

									<div class="row">
										<div class="col-sm-2 mt-2">
											<input type="number" name="downline_share" id="downline_share"  class="form-control" name="" placeholder="Enter Downline Commission">
										</div>
									</div>
									<button type="submit" class="btn btn-outline-success btn-sm edit  waves-effect waves-light mt-3" id="">Submit</button>
							
								
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

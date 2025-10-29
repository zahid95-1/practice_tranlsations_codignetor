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
								<h4 class="card-title mb-4">Add IB Plan</h4>
							<form class="form-control" action="<?php echo base_url()."save-ib-plan"?>" method="post">
								<div class="row">
									<div class="col-sm-6">
										<input type="text" name="ibplan" id="ibplan" class="form-control" placeholder="Enter Plan Name" required>
									</div>
									
								</div>

								<button type="submit" class="btn btn-outline-success btn-sm edit  waves-effect waves-light mt-3" id="">
													 Submit
												</button>
							
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



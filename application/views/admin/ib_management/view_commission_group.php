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
								<h4 class="card-title mb-4">Commission Group</h4>
								<p style="color:green;"><?php echo $this->session->flashdata('msg'); ?></p>



								<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
									   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
									<thead>
									<tr>
										<th>Sr No</th>
										<th>Master IB</th>
										<th>Account ID</th>
										<th>Phone</th>
										<th>Email</th>
										<th>Joining Date</th>
									</tr>
									</thead>
									<tbody>
										<?php
										$c = 0;
										 foreach($ComGroupData as $ComGroupDatavalue){
											$c++;
										 ?>
											<tr>
												<td><?php echo $c; ?></td>
												<td>
													<!--<a href="<?php echo base_url()."view-commission-group-master/". $ComGroupDatavalue->group_id ."/". $ComGroupDatavalue->plan_id."/". $ComGroupDatavalue->master_ib?>">
													<?php echo $ComGroupDatavalue->username ?>
													</a>-->
													<a href="<?php echo base_url()."view-commission-group-master/".  $ComGroupDatavalue->plan_id."/". $ComGroupDatavalue->master_ib?>">
													<?php echo $ComGroupDatavalue->username ?>
													</a>
												</td>
												<td><?php echo $ComGroupDatavalue->mt5_login_id ?></td>
												<td><?php echo $ComGroupDatavalue->mobile ?></td>
												<td><?php echo $ComGroupDatavalue->email ?></td>
												<td><?php echo date("d-m-Y H:i:s", strtotime($ComGroupDatavalue->joining_date));    ?>

												</td>
											</tr>
										<?php } ?>

									</tbody>
								</table>

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



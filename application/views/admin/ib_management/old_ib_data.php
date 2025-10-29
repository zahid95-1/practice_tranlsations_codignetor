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
							<h4 class="card-title mb-4">Old IB Listing</h4>
							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th>Sr No</th>
									<th>Company Referance Code</th>
									<th>Name</th>
									<th>Email</th>
									<th>Mobile No </th>
									<th>Gender</th>
									<th>Address</th>
									<th>Total Comm.</th>
									<th>Withdrawal Data</th>
									<th>Final IB Commission</th>
								</tr>
								</thead>
								<tbody>
									<?php 
									$c = 0;
									foreach($OldIbList as $OldIbListValue){
										$c++;
									 ?>
									<tr>
										<td><?php echo $c ?></td>
										<td><?php echo $OldIbListValue->company_referance_code ?></td>
										<td><?php echo $OldIbListValue->name ?></td>
										<td><?php echo $OldIbListValue->email ?></td>
										<td><?php echo $OldIbListValue->phone ?></td>
										<td><?php echo $OldIbListValue->gender ?></td>
										<td><?php echo $OldIbListValue->address ?></td>
										<td><?php echo $OldIbListValue->total_commission ?></td>
										<td><?php echo $OldIbListValue->withdrawal_data ?></td>
										<td><?php echo $OldIbListValue->final_ib_commission ?></td>
									<?php } ?>
									</tr>

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



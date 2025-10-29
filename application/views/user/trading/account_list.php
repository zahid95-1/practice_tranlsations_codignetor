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
</style>
<div class="main-content" id="result">
	<div class="page-content">
		<div class="container-fluid">

			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">
							<h4 class="card-title mb-4">Account  Listing</h4>
							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th>Sr No.</th>
									<th>Mt5 Id</th>
									<th>Account Type</th>
									<th>Name</th>
									<th>Date</th>
									<th>Action</th>
								</tr>
								</thead>
								<tbody>
								   <td>01</td>
								   <td>1000994</td>
								   <td>Gold+</td>
								   <td>7 Nutrients</td>
								   <td>01/07/1995</td>
								   <td>
									   <a class="btn btn-outline-secondary btn-sm edit" title="User View" href="#">
										   <i class="fas fa-eye"></i> View Details
									   </a>
								   </td>
								</tbody>
							</table>

						</div>
					</div>
				</div> <!-- end col -->
			</div> <!-- end row -->

			<div class="row">
				<div class="col-xl-12">
					<div class="card">
						<div class="card-body">
							<h4 class="card-title">User Details</h4>
							<hr/>
							<!-- Nav tabs -->
							<ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" data-bs-toggle="tab" href="#home1" role="tab" aria-selected="false">
										<span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
										<span class="d-none d-sm-block">Deals</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-bs-toggle="tab" href="#profile1" role="tab" aria-selected="false">
										<span class="d-block d-sm-none"><i class="far fa-user"></i></span>
										<span class="d-none d-sm-block">Deposits</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-bs-toggle="tab" href="#messages1" role="tab" aria-selected="true">
										<span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
										<span class="d-none d-sm-block">Withdraw</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-bs-toggle="tab" href="#settings1" role="tab">
										<span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
										<span class="d-none d-sm-block">Bank Details</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-bs-toggle="tab" href="#activitylog" role="tab">
										<span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
										<span class="d-none d-sm-block">Activity Log</span>
									</a>
								</li>
							</ul>

							<!-- Tab panes -->
							<div class="tab-content p-3 text-muted">
								<div class="tab-pane active" id="home1" role="tabpanel">
									<table id="datatable1" class="table table-bordered dt-responsive nowrap"
										   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
										<thead>
										<tr>
											<th>ID</th>
											<th>Date</th>
											<th>Dealer</th>
											<th>Symbol</th>
											<th>Price</th>
											<th>Profit</th>
											<th>ExpertPositionID</th>
											<th>Comment</th>
											<th>Action</th>
										</tr>
										</thead>
										<tbody>
										<td>1000994</td>
										<td>22 Aug 2022</td>
										<td>0</td>
										<td>285263</td>
										<td>USOil.Xg</td>
										<td>88.995</td>
										<td>11.12</td>
										<td>285260</td>
										<td>Buy</td>
										</tbody>
									</table>
								</div>
								<div class="tab-pane" id="profile1" role="tabpanel">
									<h4 class="mb-3">Deposit Listing</h4>
									<table id="datatable2" class="table table-bordered dt-responsive nowrap"
										   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
										<thead>
										<tr>
											<th>ID</th>
											<th>Deposit Amount</th>
											<th>Create Date</th>
											<th>Status</th>
										</tr>
										</thead>
										<tbody>
										<td>1000994</td>
										<td>22 Aug 2022</td>
										<td>$1000</td>
										<td>active</td>
										</tbody>
									</table>
								</div>
								<div class="tab-pane" id="messages1" role="tabpanel">
									<h4 class="mb-3">Withdraw Listing</h4>
									<table id="datatable3" class="table table-bordered dt-responsive nowrap"
										   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
										<thead>
										<tr>
											<th>ID</th>
											<th>Withdraw Amount</th>
											<th>Create Date</th>
											<th>Status</th>
										</tr>
										</thead>
										<tbody>
										<td>1000994</td>
										<td>22 Aug 2022</td>
										<td>$1000</td>
										<td>active</td>
										</tbody>
									</table>
								</div>
								<div class="tab-pane" id="settings1" role="tabpanel">
									<h4 class="mb-3">Bank Details</h4>
								</div>
								<div class="tab-pane" id="activitylog" role="tabpanel">
									<h4 class="mb-3">Activity Details</h4>
									<table id="datatable4" class="table table-bordered dt-responsive nowrap"
										   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
										<thead>
										<tr>
											<th>Sr</th>
											<th>Date</th>
											<th>Username</th>
											<th>User agent</th>
											<th>Ip address</th>
										</tr>
										</thead>
										<tbody>
										<td>1</td>
										<td>22 Aug 2022</td>
										<td>zadd</td>
										<td>test</td>
										<td>172.0.10.1</td>
										</tbody>
									</table>
								</div>
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
	$(document).ready(function () {
		$('#datatable1,#datatable2,#datatable3,#datatable4').DataTable({
			"language": {
				"paginate": {
					"previous": "<i class='mdi mdi-chevron-left'>",
					"next": "<i class='mdi mdi-chevron-right'>"
				}
			},
			"drawCallback": function () {
				$('.dataTables_paginate > .pagination').addClass('pagination-rounded');
			}

		});
	});
</script>

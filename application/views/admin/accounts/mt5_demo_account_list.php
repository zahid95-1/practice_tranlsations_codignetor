<?php
$errorObject=$requestData='';
if (isset($_SESSION['error_open_account'])){
	$errorObject	=json_decode($_SESSION['error_open_account']);
}
?>
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
							<?php if (isset($_SESSION['success_trading_account'])):?>
								<div class="alert alert-success alert-dismissible fade show" role="alert">
									<?=$_SESSION['success_trading_account']?>
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
								</div>
								<?php unset($_SESSION['success_trading_account']); endif; ?>

							<h4 class="card-title mb-4">My MT5 Demo Account  List</h4>
							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th>Sr No.</th>
									<th>Mt5 Account ID</th>
									<th>Group Name</th>
									<th>Balance</th>
									<th>Date</th>
								</tr>
								</thead>
								<tbody>
								<?php if (isset($dataItem) && $dataItem): foreach ($dataItem as $key=>$data):
									$mtId=$data->mt5_login_id;
									?>
									<tr>
										<td><?=++$key;?></td>
										<td><?=$data->mt5_login_id?></td>
										<td><?=$data->group_name?></td>
										<td><?=$data->balance?></td>
										<td><?=date('d-m-Y',strtotime($data->created_at))?></td>
									</tr>
								<?php endforeach; endif; ?>
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

<script>
	$(document).ready(function () {
		$('#datatable1').DataTable({
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

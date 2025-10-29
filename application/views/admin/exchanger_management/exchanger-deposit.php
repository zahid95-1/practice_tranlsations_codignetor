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
							<?php if (isset($_SESSION['success_exchanger'])):?>
							<div class="alert alert-success alert-dismissible fade show" role="alert">
								<?=$_SESSION['success_exchanger']?>
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
							<?php unset($_SESSION['success_exchanger']); endif; ?>
							<h4 class="card-title mb-4">Exchanger Listing</h4>
							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th>Investor</th>
									<th>Amount</th>
									<th>Currency</th>
									<th>Note</th>
									<th>Exchanger</th>
									<th>Date</th>
								</tr>
								</thead>
								<tbody>

								<?php if ($dataItem){
									foreach ($dataItem as $key=>$item):
									?>
									<tr>
										<td><?=$item->invester ?></td>
										<td><?=$item->amount ?></td>
										<td><?=$item->from_currency ?></td>
										<td><?=$item->note ?></td>
										<td><?=$item->exchanger ?></td>
										<td><?=$item->created_datetime ?></td>
										
									</tr>
								<?php endforeach; } ?>

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


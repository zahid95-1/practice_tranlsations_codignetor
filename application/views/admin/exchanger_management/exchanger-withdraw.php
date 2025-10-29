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
							<h4 class="card-title mb-4">Exchanger Withdraw</h4>
							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th>Bank Account No</th>
									<th>IFSC</th>
									<th>Bank Name</th>
									<th>Branch Name</th>
									<th>Coverage Account No</th>
									<th>Amount</th>
									<th>Currency</th>
									<th>Note</th>
								</tr>
								</thead>
								<tbody>

								<?php if ($dataItem){
									foreach ($dataItem as $key=>$item):
									?>
									<tr>
										<td><?=$item->account_no ?></td>
										<td><?=$item->ifsc_code ?></td>
										<td><?=$item->bank_name ?></td>
										<td><?=$item->branch_name ?></td>
										<td><?=$item->coverage_account_no ?></td>
										<td><?=$item->amount ?></td>
										<td><?=$item->currency ?></td>
										<td><?=$item->note ?></td>
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


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
									<th>Name</th>
									<th>Email</th>
									<th>Phone</th>
									<th>Total Deposit</th>
									<th>Total Withdraw</th>
									<th>Remaining</th>
									<th>Action</th>
								</tr>
								</thead>
								<tbody>

								<?php if ($dataItem){
									foreach ($dataItem as $key=>$item):
									?>
									<tr>
										<td><?=$item->name ?></td>
										<td><?=$item->email ?></td>
										<td><?=$item->mobile ?></td>
										<td><?=$item->total_deposit ?></td>
										<td><?=$item->total_withdrawal ?></td>
										<td><?=$item->remaining ?></td>
										<td><a class="btn btn-outline-secondary btn-sm edit" title="Edit" href="<?php echo base_url(); ?>edit-exchanger?exchangerid=<?=$item->exchanger_id?>">
											<i class=" fas fa-edit"></i>
										</a></td>
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


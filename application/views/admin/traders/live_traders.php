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

							<h4 class="card-title mb-4"><?=isset($dataItem)?count($dataItem):'0'?> Live Traders Listing</h4>
							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th>SR</th>
									<th>MT5 AC</th>
									<th>Usename</th>
									<th>Symbol</th>
									<th>Volume</th>
									<th>Open Price</th>
									<th>Floating P/L</th>
									
									<th>Status</th>
								</tr>
								</thead>
								<tbody>

								<?php if (isset($dataItem) && $dataItem){
									foreach ($dataItem as $key=>$item):
									?>
									<tr>
										<td><?=++$key?></td>
										<td><?=$item->mt5_login_id?></td>
										<td><?=$item->first_name.' '.$item->last_name?></td>
										<td><?=$item->symbol?></td>
										<td><?=number_format($item->volume , 2)?></td>
										<td>$<?=number_format($item->price , 2)?></td>
										<td><?=$item->floating_pl?></td>
										<td>
											<?php
											echo "<span style='color: green'>Buy</span>";
											?>
										</td>
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

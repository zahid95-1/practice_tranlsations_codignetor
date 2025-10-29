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

							<h4 class="card-title mb-4"><?=$this->lang->line('mt5_transaction_total_summary')?></h4>

							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th><?=$this->lang->line('sr')?></th>
									<th><?=$this->lang->line('user_id')?></th>
									<th><?=$this->lang->line('email')?></th>
									<th><?=$this->lang->line('mt5_id')?></th>
									<th><?=$this->lang->line('leverage')?></th>
									<th><?=$this->lang->line('total_deposit')?></th>
									<th><?=$this->lang->line('status')?></th>
								</tr>
								</thead>
								<tbody>

								<?php if (isset($dataItem) && $dataItem){
									foreach ($dataItem as $key=>$item):

										?>
										<tr>
											<td><?=++$key?></td>
											<td><?=$item->unique_id?></td>
											<td><?=$item->email?></td>
											<td><?=$item->mt5_login_id?></td>
											<td><?=$item->leverage?></td>
											<td>$<?=$item->total_payment?></td>
											<td>
												<?php
												if ($item->ib_status==1){
													echo "<span style='color: green'>IB</span>";
												}else{
													echo "<span style='color: gray'>Client</span>";
												}
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

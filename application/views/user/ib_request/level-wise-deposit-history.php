<?php
/*===================GetUserInfo=========================*/
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
	.page-content {
		/*padding: calc(20px + 24px) calc(24px / 2) 60px calc(24px / 2) !important;*/
	}
	.level-info {
		display: flex;
		justify-content: center;
		align-content: space-between;
	}
	.level-info  .single-btn {
		margin-right: 23px;
	}
	.reportSections {
		display: flex;
		justify-content: space-around;
		margin: 34px 282px;
	}
</style>
<div class="main-content" id="result">
	<div class="page-content">
		<div class="container-fluid">.


			<div class="row">
				<div class="col-12">
					<div class="card">

						<div class="card-body">




								<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
									   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
									<thead>
                                                                        <tr>
                                                                                <th><?= lang('level') ?> #</th>
                                                                                <th><?= lang('amount') ?></th>
                                                                        </tr>
									</thead>
									<tbody>
									<?php foreach($dataItem['DepositHistory'] as $deposithistory){ 

										
										?>
										<tr>
											<td><?php echo $deposithistory->level_no ?></td>
											<td><?php echo $deposithistory->total_deposit ?></td>
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




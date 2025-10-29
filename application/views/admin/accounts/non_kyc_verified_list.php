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
	#datatable-buttons_wrapper .row:nth-child(3) {
		display: none;
	}
</style>

<div class="main-content" id="result">
	<div class="page-content">
		<div class="container-fluid">

			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">
							<h4 class="card-title mb-4"><?=$this->lang->line('non_kyc_verified_list')?></h4>
							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th><?=$this->lang->line('sr_no')?></th>
									<th><?=$this->lang->line('unique_id')?></th>
									<th><?=$this->lang->line('user_name')?></th>
									<th><?=$this->lang->line('email')?></th>
									<th><?=$this->lang->line('mobile')?></th>
								</tr>
								</thead>
								<tbody>
								<?php if (isset($dataItem) && $dataItem): foreach ($dataItem['kyc_list'] as $key=>$data):
									?>
									<tr>
										<td><?=++$key;?></td>
										<td><?=$data->unique_id?></td>
										<td><?=$data->first_name.' '.$data->last_name?></td>
										<td><?=$data->email?></td>
										<td><?=$data->mobile?></td>
									</tr>
								<?php endforeach; endif; ?>
								</tbody>
							</table>

							<!-- Custom Paginations -->
							<div class="dataTables_wrapper dt-bootstrap4 no-footer">
								<div class="row">
									<div class="col-sm-12 col-md-5">
										<div class="dataTables_info" style="display: block!important;" id="datatable-buttons_info" role="status" aria-live="polite">Showing <?=($dataItem['current_page'])?$dataItem['current_page']:0?> to 10 of <?=($dataItem['total_pages'])?$dataItem['total_pages']:'0'*10?> entries</div>
									</div>
									<div class="col-sm-12 col-md-7">
										<div class="dataTables_paginate paging_simple_numbers" id="datatable-buttons_paginate" style="display: block!important;">
											<ul class="pagination pagination-rounded">
												<?php if ($dataItem['current_page'] > 1) { ?>
													<li class="paginate_button page-item previous" id="datatable-buttons_previous">
														<a href="?page=<?php echo $dataItem['current_page'] - 1; ?>" aria-controls="datatable-buttons" data-dt-idx="0" tabindex="0" class="page-link">
															<i class="mdi mdi-chevron-left"></i>
														</a>
													</li>
												<?php } ?>
												<?php for ($i = 1; $i <= $dataItem['total_pages']; $i++) { ?>
													<?php if ($i == $dataItem['current_page']) { ?>
														<li class="paginate_button page-item active">
															<a href="#" aria-controls="datatable-buttons" data-dt-idx="1" tabindex="0" class="page-link"><?php echo $i; ?></a>
														</li>
													<?php } else { ?>
														<?php if ($i == 1 || $i == $dataItem['total_pages'] || ($i >= $dataItem['current_page'] - 2 && $i <= $dataItem['current_page'] + 2)) { ?>
															<li class="paginate_button page-item">
																<a href="?page=<?php echo $i; ?>" aria-controls="datatable-buttons" data-dt-idx="1" tabindex="0" class="page-link"><?php echo $i; ?></a>
															</li>
														<?php } else if ($i == $dataItem['current_page'] - 3 || $i == $dataItem['current_page'] + 3) { ?>
															<li class="paginate_button page-item disabled" id="datatable-buttons_ellipsis"><a href="#" aria-controls="datatable-buttons" data-dt-idx="2" tabindex="0" class="page-link">…</a></li>
														<?php } ?>
													<?php } ?>
												<?php } ?>

												<?php if ($dataItem['current_page'] < $dataItem['total_pages']) { ?>
													<li class="paginate_button page-item next" id="datatable-buttons_next">
														<a href="?page=<?php echo $dataItem['current_page'] + 1; ?>" aria-controls="datatable-buttons" data-dt-idx="2" tabindex="0" class="page-link">
															<i class="mdi mdi-chevron-right"></i>
														</a>
													</li>
												<?php } ?>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<!-- Custom Paginations End -->

						</div>
					</div>
				</div> <!-- end col -->
			</div> <!-- end row -->


		</div>
	</div>
	<!-- End Page-content -->
</div>
<!-- end main content-->

<div class="modal fade" id="accountListDetails" aria-hidden="true" aria-labelledby="..." tabindex="-1">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Account Details</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModal-<?=$key?>"></button>
			</div>

			<table class="table mb-0" id="accountDetailsTable">

			</table>
		</div>
	</div>
</div>

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

		// Account Live Details
		$(document).on('click', 'a#mt5AccountListDetails', function () {

			var currentIndex	=$(this).data('key-index');
			var accountId		=$(this).data('account-id');
			let loader=`<div class="loading-data">
					<span class="loader"></span>
				</div>`;

			$('#accountDetailsTable').html(loader);

			$('#accountListDetails').modal('show');

			var url = "<?php echo base_url(); ?>user/my-mt5-account-list/details";
			var post_data = {
				'accountId': accountId,
				'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
			};

			$.ajax({
				url : url,
				type : 'POST',
				data: post_data,
				success : function(response)
				{
					var obj = JSON.parse(response);
					if (response!=0){
						let html=`<tbody>
									<tr>
										<th scope="row">Login :</th>
										<td>${obj.Login}</td>
										<th scope="row">Balance :</th>
										<td>$${obj.Balance}</td>
										<td>Margin Free :</td>
										<td>${obj.MarginFree}</td>
									</tr>
									<tr>
										<td>Margin Leverage  :</td>
										<td>${obj.MarginLeverage}</td>
										<th scope="row">Credit  :</th>
										<td>${obj.Credit}</td>
										<th scope="row">Equity   :</th>
										<td>${obj.Equity}</td>
									</tr>
									</tbody>`;

						$('#accountDetailsTable').html(html)
					}
				}
			});
		});

	});

	$(document).on('click', 'button#markIdentityVerified', function () {

		var currentIndex	=$(this).data('key-index');
		var userid			=$(this).data('unique-id');

		var url = "<?php echo base_url(); ?>kyc-attachment-verified";
		var post_data = {
			'userid': userid,
			'type': 1,
			'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
		};
		$.ajax({
			url : url,
			type : 'POST',
			data: post_data,
			success : function(response)
			{
				if (response){
					var html='<span class="badge rounded-pill bg-success">Verified</span>';
					$('td#markIdentityVerifiedTd-'+currentIndex+'').html(html);
				}
			}
		});
	});

	$(document).on('click', 'button#markResidencyVerified', function () {

		var currentIndex	=$(this).data('key-index');
		var userid			=$(this).data('unique-id');

		var url = "<?php echo base_url(); ?>kyc-residency-attachment-verified";
		var post_data = {
			'userid': userid,
			'type': 1,
			'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
		};
		$.ajax({
			url : url,
			type : 'POST',
			data: post_data,
			success : function(response)
			{
				if (response){
					var html='<span class="badge rounded-pill bg-success">Verified</span>';
					$('td#markResidencyVerifiedTd-'+currentIndex+'').html(html);
				}
			}
		});

	});

</script>

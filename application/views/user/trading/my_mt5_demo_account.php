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

                                                        <h4 class="card-title mb-4"><?= lang('my_mt5_demo_account_list') ?></h4>
							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
                                                                        <th><?= lang('sr_no') ?></th>
                                                                        <th><?= lang('mt5_account_id') ?></th>
                                                                        <th><?= lang('group_name') ?></th>
									<?php if (ConfigData['enable_deposit_withdraw_rate']): ?>
                                                                               <th><?= lang('account_type') ?></th>
									<?php endif; ?>
                                                                        <th><?= lang('name') ?></th>
                                                                        <th><?= lang('withdraw') ?></th>
								</tr>
								</thead>
								<tbody>
								<?php if (isset($dataItem) && $dataItem): foreach ($dataItem as $key=>$data):
									?>
									<tr>
										<td><?=++$key;?></td>
										<td><?=$data->mt5_login_id?></td>
										<td><?=$data->group_name?></td>
										<?php if (ConfigData['enable_deposit_withdraw_rate']): ?>
											<td><?php if ($data->account_type_status){if ($data->account_type_status==1){echo "Live Rate";}elseif ($data->account_type_status==2){echo "<span style='color: green'>Fixed Rate</span>";}}else{echo "Live Rate";}?></td>
										<?php endif; ?>
										<td><?=$data->first_name.' '.$data->last_name?></td>
										<td>
											<a class="btn btn-outline-secondary btn-sm edit" title="User View" style="margin-right: 1px;" href="javascript:void(0)" id="mt5AccountListDetails" data-key-index="<?=$key?>" data-account-id="<?=$data->mt5_login_id?>">
                                                                               <i class="fas fa-eye"></i> <?= lang('view_details') ?>
											</a>
										</td>
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
<div class="modal fade" id="accountListDetails" aria-hidden="true" aria-labelledby="..." tabindex="-1">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
                                <h5 class="modal-title"><?= lang('account_details') ?></h5>
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
</script>

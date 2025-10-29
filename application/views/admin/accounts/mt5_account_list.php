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
							<h4 class="card-title mb-4"><?=$this->lang->line('my_mt5_account_list')?></h4>
							<p style="color:green;"><?php echo $this->session->flashdata('msg'); ?></p>
							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th><?=$this->lang->line('sr_no')?></th>
									<th><?=$this->lang->line('mt5_account_id')?></th>
									<th><?=$this->lang->line('account_type')?></th>
									<th><?=$this->lang->line('name')?></th>
									<th><?=$this->lang->line('main_pass')?></th>
									<th><?=$this->lang->line('investor_pass')?></th>
									<th><?=$this->lang->line('date')?></th>
									<th><?=$this->lang->line('action')?></th>
								</tr>
								</thead>
								<tbody>
								<?php if (isset($dataItem) && $dataItem): foreach ($dataItem as $key=>$data):?>
									<tr>
										<td><?=++$key;?></td>
										<td><?=$data->mt5_login_id?></td>
										<td><?=$data->group_name?></td>
										<td><?=$data->first_name.' '.$data->last_name?></td>
										<td><?=$data->pass_main?></td>
										<td><?=$data->pass_investor?></td>
										<td><?=date('d-m-Y',strtotime($data->created_at))?></td>
										<td>
											<a class="btn btn-outline-secondary btn-sm edit" title="<?=$this->lang->line('user_view')?>" style="margin-right: 1px;" href="javascript:void(0)" id="mt5AccountListDetails" data-key-index="<?=$key?>" data-account-id="<?=$data->mt5_login_id?>">
												<i class="fas fa-eye"></i> <?=$this->lang->line('view_details')?>
											</a>

											<a class="btn btn-outline-secondary btn-sm edit" title="<?=$this->lang->line('resend_mail')?>" href="<?=base_url()?>resend-trading-account-opening-mail/<?php echo $data->mt5_login_id ?>">
												<i class="fas fa-mail-bulk"></i> <?=$this->lang->line('resend_opening_mail')?>
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
</script>

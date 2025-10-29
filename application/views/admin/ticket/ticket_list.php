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
							<?php if (isset($_SESSION['success_ticket_creations'])):?>
								<div class="alert alert-success alert-dismissible fade show" role="alert">
									<?=$_SESSION['success_ticket_creations']?>
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
								</div>
								<?php unset($_SESSION['success_ticket_creations']); endif; ?>

							<h4 class="card-title mb-4"><?=$this->lang->line('tickets_list')?></h4>

							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th><?=$this->lang->line('sl')?></th>
									<th><?=$this->lang->line('user_name')?></th>
									<th><?=$this->lang->line('user_email')?></th>
									<th><?=$this->lang->line('ticket_number')?></th>
									<th><?=$this->lang->line('title')?></th>
									<th><?=$this->lang->line('department')?></th>
									<th><?=$this->lang->line('date')?></th>
									<th><?=$this->lang->line('action')?></th>
								</tr>
								</thead>
								<tbody>
								<?php
								 foreach ($dataItem as $key=>$item):
								?>
								<tr>
									<td><?=++$key;?></td>
									<td><?=$item->first_name?></td>
									<td><?=$item->email?></td>
									<td><?=$item->ticket_id?></td>
									<td><?=$item->title?></td>
									<td><?=$item->department?></td>
									<td><?=$item->created_at?></td>
									<td>
										<?php if ($item->status == 1): ?>
											<a class="btn btn-outline-secondary btn-sm edit"
											   title="<?=$this->lang->line('view_tickets')?>"
											   href="<?=base_url()?>admin/ticket/ticket-list/<?=$item->id?>">
												<i class="fas fa-eye"></i> <?=$this->lang->line('view_details')?>
											</a>
										<?php else: ?>
											<a class="btn btn-outline-secondary btn-sm edit"
											   title="<?=$this->lang->line('view_tickets')?>"
											   href="<?=base_url()?>admin/ticket/ticket-list/<?=$item->id?>"
											   style="color: red">
												<i class="fas fa-eye"></i> <?=$this->lang->line('close_ticket')?>
											</a>
										<?php endif; ?>
									</td>

								</tr>
								<?php endforeach; ?>
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

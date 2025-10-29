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

							<h4 class="card-title mb-4"><?= lang('ticket_list') ?></h4>
							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th>SL</th>
									<th>Ticket Number</th>
									<th>Title</th>
									<th>Department</th>
									<th>Date</th>
									<th>Action</th>
								</tr>
								</thead>
								<tbody>
								<?php
								 foreach ($dataItem as $key=>$item):
								?>
								<tr>
									<td><?=++$key;?></td>
									<td><?=$item->ticket_id?></td>
									<td><?=$item->title?></td>
									<td><?=$item->department?></td>
									<td><?=$item->created_at?></td>
									<td>
										<?php if ($item->status==1): ?>
											<a class="btn btn-outline-secondary btn-sm edit" title="View Tickets" href="<?=base_url()?>user/ticket-list/<?=$item->id?>">
												<i class="fas fa-eye"></i> View Details
											</a>
										<?php else: ?>
											<a class="btn btn-outline-secondary btn-sm edit" title="View Tickets" href="<?=base_url()?>user/ticket-list/<?=$item->id?>" style="color: red">
												<i class="fas fa-eye"></i> Close Ticket
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

	<?php
	/*===================GetUserInfo=========================*/
	?>
	<link href="<?=base_url()?>assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

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
		span.select2.select2-container.select2-container--default {
			width: 100%!important;
		}
	</style>
	<div class="main-content" id="result">
		<div class="page-content">
			<div class="container-fluid">

				<div class="row">
					<div class="col-12">
						<div class="card">
							<div class="card-body">
								<h4 class="card-title mb-4"><?=$this->lang->line('remove_ib')?></h4>
								<p style="color:green;"><?=$this->session->flashdata('msg')?></p>
								<form action="<?=base_url('remove-ib')?>" method="post" id="removeIbForm" enctype="multipart/form-data">
									<div class="row">
										<div class="col-sm-6">
											<select class="form-control select2" name="unique_id" id="selectedUser">
												<option value=""><?=$this->lang->line('select_ib')?></option>
												<?php if (isset($ibuserlist)): foreach ($ibuserlist as $item): ?>
													<option value="<?=$item->unique_id?>">
														<?=$item->first_name?> <?=$item->last_name?> (<?=$item->unique_id?>) (<?=$item->email?>)
													</option>
												<?php endforeach; endif; ?>
											</select>
										</div>
									</div>
									<button type="submit"
											class="btn btn-outline-success btn-sm edit waves-effect waves-light mt-3">
										<?=$this->lang->line('submit')?>
									</button>
								</form>
							</div>
						</div>
					</div> <!-- end col -->
				</div> <!-- end row -->
				<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">
							<h4 class="card-title mb-4"><?=$this->lang->line('ib_blocked_listing')?></h4>
							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th><?=$this->lang->line('sr_no')?></th>
									<th><?=$this->lang->line('name')?></th>
									<th><?=$this->lang->line('email')?></th>
									<th><?=$this->lang->line('blocked_date')?></th>
								</tr>
								</thead>
								<tbody>
									<?php 
									$c = 0;
									foreach($ibblockeduserlist as $getibblockeduserlist){
										$c++;
									 ?>
									<tr>
										<td><?php echo $c ?></td>
										<td><?php echo $getibblockeduserlist->first_name . " ".  $getibblockeduserlist->last_name ?></td>
										<td><?php echo $getibblockeduserlist->email ?></td>
										<td><?php echo $getibblockeduserlist->ib_block_date ?></td>
										
										
									<?php } ?>
									</tr>


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

	<!-- Sweet Alerts js -->
	<script src="<?=base_url()?>assets/libs/sweetalert2/sweetalert2.min.js"></script>

	<!-- Sweet alert init js-->
	<script src="<?=base_url()?>assets/js/pages/sweet-alerts.init.js"></script>


